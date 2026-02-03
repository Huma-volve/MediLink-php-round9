<?php

namespace App\Http\Controllers\Api;

use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Prescription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;

class PrescriptionController extends Controller
{
    /**
     * Search doctors
     */
    public function search(Request $request)
    {
        $doctors = Doctor::with(['user:id,name', 'spelization:id,name'])
            ->where('is_verified', true)
            ->when($request->name, function ($query) use ($request) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->name . '%');
                });
            })
            ->when($request->spelization_id, function ($query) use ($request) {
                $query->where('spelization_id', $request->spelization_id);
            })
            ->when($request->city, function ($query) use ($request) {
                $query->where('location', 'like', '%' . $request->city . '%');
            })
            ->get();

        return response()->json([
            'success' => true,
            'data' => $doctors
        ]);
    }

    /**
     * Store prescription (Doctor only)
     */
    public function store(Request $request)
    {
        // عشان تتأكد من العلاقة بين الدكتور والموعد
        // $appointment = Appointment::with('doctor')
        //     ->findOrFail($request->appointment_id);
        // return response()->json([
        //     'logged_in_user_id' => auth()->id(),
        //     'doctor_associated_with_appointment' => $appointment->doctor->user_id
        // ]);

        $request->validate([
            'appointment_id'     => 'required|exists:appointments,id',
            'medications'        => 'required|array',
            'diagnosis'          => 'required|string|min:5',
            'frequency'          => 'required|string',
            'duration_days'      => 'required|integer',
            'prescription_date'  => 'required|date',
            'expiry_date'        => 'nullable|date|after:prescription_date',
        ]);

        $appointment = Appointment::with('doctor')
            ->findOrFail($request->appointment_id);

        //  تأكد إن الدكتور هو صاحب الموعد
        if ($appointment->doctor->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بإنشاء روشتة لهذا الموعد'
            ], 403);
        }

        $prescription = Prescription::create([
            'appointment_id'      => $appointment->id,
            'prescription_number' => 'RX-' . strtoupper(uniqid()),
            'medications' => $request->medications,

            'diagnosis'           => $request->diagnosis,
            'frequency'           => $request->frequency,
            'duration_days'       => $request->duration_days,
            'prescription_date'   => $request->prescription_date,
            'expiry_date'         => $request->expiry_date ?? now()->addMonths(6),
        ]);

        // تحديث حالة الموعد
        $appointment->update(['status' => 'completed']);

        return response()->json([
            'success' => true,
            'message' => 'Diagnosis summary created successfully',
            'data' => $prescription,
            // 'download_url' => route('prescriptions.download', $prescription->id)
        ], 201);
    }

    /**
     * Download prescription PDF
     */
    public function download($id)
    {
        // تحميل العلاقات بشكل متسلسل

        $prescription = Prescription::with([
            'appointment.doctor.user',
            'appointment.doctor.specialization',
            'appointment.patient.user'
        ])->findOrFail($id);



        $pdf = \PDF::loadView('pdf.prescription', [
            'prescription' => $prescription,
            'appointment'  => $prescription->appointment
        ]);

        // عشان يدعم اللغة العربية
        $pdf->getDomPDF()->set_option("enable_remote", true);
        $pdf->getDomPDF()->set_option("defaultFont", "DejaVu Sans");

        return $pdf->download("prescription_{$id}.pdf");
    }
}
