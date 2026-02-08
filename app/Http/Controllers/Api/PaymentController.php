<?php

namespace App\Http\Controllers\Api;

use Exception;
use Stripe\Charge;
use Stripe\Stripe;
use App\Models\Payment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Stripe\Exception\ApiErrorException;
use App\Models\Appointment;
use App\Models\Doctor;

class PaymentController extends Controller
{

    public function index(Request $request)
    {

        $query = Payment::with(['appointment', 'patient', 'patientInsurance']);

        // Filter by patient
        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        // Filter by appointment
        if ($request->filled('appointment_id')) {
            $query->where('appointment_id', $request->appointment_id);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }


        $payments = $query->latest('payment_date')->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Payments retrieved successfully',
            'data' => $payments
        ], 200);
    }

    public function show($id)
    {
        $payment = Payment::with(['appointment', 'patient', 'patientInsurance'])->find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment retrieved successfully',
            'data' => $payment
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_id' => 'nullable|exists:appointments,id',
            'patient_id' => 'required|exists:patients,id',
            'patient_insurance_id' => 'nullable|exists:patient_insurances,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:credit_card,debit_card,bank_transfer,cash,insurance,wallet',
            'payment_status' => 'nullable|in:pending,completed,failed,refunded,cancelled,partial',
            'currency' => 'nullable|string|max:3',
        ]);

        // Generate unique transaction ID
        $validated['transaction_id'] = 'TXN-' . strtoupper(Str::random(10));
        $validated['payment_date'] = now();
        $validated['payment_status'] = $validated['payment_status'] ?? 'pending';
        $validated['currency'] = $validated['currency'] ?? 'EGP';

        $payment = Payment::create($validated);

        // إذا كان الدفع مكتمل، قم بتحديث رصيد الدكتور
        if ($payment->payment_status === 'completed' && $payment->appointment_id) {
            $this->updateDoctorBalance($payment);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment created successfully',
            'data' => $payment->load(['appointment', 'patient', 'patientInsurance'])
        ], 201);
    }


    // Stripe::setApiKey(config('services.stripe.secret') ?? env('STRIPE_SECRET'));

    // stripe integration
    public function processStripePayment(Request $request)
    {
        // التحقق من البيانات
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'amount' => 'required|numeric',
            'stripeToken' => 'required',
        ]);

        // إعداد المفتاح setApiKey
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $charge = Charge::create([
                "amount" => $request->amount * 100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Payment for MediLink Appointment #" . $request->appointment_id
            ]);

            // إنشاء السجل في جدول المدفوعات
            $payment = Payment::create([
                'appointment_id' => $request->appointment_id,
                'patient_id'     => Appointment::find($request->appointment_id)->patient_id, // جلب id المريض تلقائياً
                'amount'         => $request->amount,
                'payment_status' => 'completed',
                'transaction_id' => $charge->id,
                'payment_method' => 'stripe',
                //  'payment_method' => 'credit_card',
                'payment_date'   => now(),
            ]);

            // تحديث حالة الموعد
            Appointment::where('id', $request->appointment_id)->update(['status' => 'paid']);

            // تحديث رصيد الدكتور
            $this->updateDoctorBalance($payment);

            return response()->json(['success' => true, 'data' => $payment]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }


    /**
     *  - pending ->completed  تحويل الحالة إلى مكتمل بعد الدفع
     */
    public function processPayment($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }

        if ($payment->payment_status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Payment already completed'
            ], 400);
        }

        $payment->update([
            'payment_status' => 'completed',
            'payment_date' => now()
        ]);

        // تحديث رصيد الدكتور عند إكمال الدفع
        if ($payment->appointment_id) {
            $this->updateDoctorBalance($payment);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment processed successfully',
            'data' => $payment->load(['appointment', 'patient', 'patientInsurance'])
        ], 200);
    }

    /**
     *   ترجيع المبلغ- فى حالة الالغاء او الاسترجاع- لو كان مكتمل
     */
    public function refund($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }

        if ($payment->payment_status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Only completed payments can be refunded'
            ], 400);
        }

        $payment->update([
            'payment_status' => 'refunded'
        ]);

        // خصم المبلغ من رصيد الدكتور عند الاسترجاع
        if ($payment->appointment_id) {
            $this->updateDoctorBalance($payment, true);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment refunded successfully',
            'data' => $payment->load(['appointment', 'patient', 'patientInsurance'])
        ], 200);
    }


    private function updateDoctorBalance(Payment $payment, bool $isRefund = false)
    {
        $appointment = Appointment::find($payment->appointment_id);

        if (!$appointment || !$appointment->doctor_id) return;

        $doctor = Doctor::find($appointment->doctor_id);
        if (!$doctor) return;

        if ($isRefund) {
            $doctor->decrement('current_balance', $payment->amount);
        } else {
            $doctor->increment('current_balance', $payment->amount);
        }
    }

    // عرض رصيد الدكتور الحالي

    public function getDoctorBalance($doctorId)
    {
        $doctor = Doctor::find($doctorId);

        if (!$doctor) {
            return response()->json([
                'success' => false,
                'message' => 'Doctor not found'
            ], 404);
        }

        // حساب الرصيد من المدفوعات المكتملة
        $totalEarnings = Payment::whereHas('appointment', function ($query) use ($doctorId) {
            $query->where('doctor_id', $doctorId);
        })
            ->where('payment_status', 'completed')
            ->sum('amount');

        // حساب المبالغ المسترجعة
        $totalRefunded = Payment::whereHas('appointment', function ($query) use ($doctorId) {
            $query->where('doctor_id', $doctorId);
        })
            ->where('payment_status', 'refunded')
            ->sum('amount');

        $calculatedBalance = $totalEarnings - $totalRefunded;

        return response()->json([
            'success' => true,
            'message' => 'Doctor balance retrieved successfully',
            'data' => [
                'doctor_id' => $doctor->id,
                'doctor_name' => $doctor->name ?? 'N/A',
                'current_balance' => $doctor->current_balance,
                'calculated_balance' => $calculatedBalance,
                'total_earnings' => $totalEarnings,
                'total_refunded' => $totalRefunded,
            ]
        ], 200);
    }

    // إعادة حساب رصيد جميع الأطباء من الصفر

    public function recalculateAllDoctorsBalance()
    {
        $doctors = Doctor::all();
        $updated = 0;

        foreach ($doctors as $doctor) {
            // حساب الرصيد الصحيح
            $totalEarnings = Payment::whereHas('appointment', function ($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            })
                ->where('payment_status', 'completed')
                ->sum('amount');

            $totalRefunded = Payment::whereHas('appointment', function ($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            })
                ->where('payment_status', 'refunded')
                ->sum('amount');

            $correctBalance = $totalEarnings - $totalRefunded;

            // تحديث الرصيد
            $doctor->update(['current_balance' => $correctBalance]);
            $updated++;
        }

        return response()->json([
            'success' => true,
            'message' => 'All doctors balances recalculated successfully',
            'data' => [
                'doctors_updated' => $updated
            ]
        ], 200);
    }
}
