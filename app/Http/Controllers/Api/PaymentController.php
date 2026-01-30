<?php

namespace App\Http\Controllers\Api;

use App\Models\Payment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

        return response()->json([
            'success' => true,
            'message' => 'Payment created successfully',
            'data' => $payment->load(['appointment', 'patient', 'patientInsurance'])
        ], 201);
    }

    /* Update payment */
    public function update(Request $request, $id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }

        $validated = $request->validate([
            'amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|in:credit_card,debit_card,bank_transfer,cash,insurance,wallet',
            'payment_status' => 'nullable|in:pending,completed,failed,refunded,cancelled,partial',
            'currency' => 'nullable|string|max:3',
        ]);

        $payment->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Payment updated successfully',
            'data' => $payment->load(['appointment', 'patient', 'patientInsurance'])
        ], 200);
    }

    /* Delete payment */
    public function destroy($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }

        $payment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Payment deleted successfully'
        ], 200);
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

        return response()->json([
            'success' => true,
            'message' => 'Payment refunded successfully',
            'data' => $payment->load(['appointment', 'patient', 'patientInsurance'])
        ], 200);
    }
}
