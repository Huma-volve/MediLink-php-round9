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
        $doctors = Doctor::with(['user:id,name', 'specialization:id,name'])
            ->where('is_verified', true)
            ->when($request->name, function ($query) use ($request) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->name . '%');
                });
            })
            ->when($request->specialization->id, function ($query) use ($request) {
                $query->where('specialization->id', $request->specialization->id);
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
            'medications'         => $request->medications,
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
            'download_url' => route('prescriptions.download', $prescription->id)
        ], 201);
    }

    /**
     * Download prescription PDF
     */
    public function download($id)
    {
        $prescription = Prescription::with([
            'appointment.doctor.user',
            'appointment.patient.user'
        ])->findOrFail($id);

        //  السماح للطبيب أو المريض فقط
        $userId = auth()->id();

        if (
            $prescription->appointment->doctor->user_id !== $userId &&
            $prescription->appointment->patient->user_id !== $userId
        ) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بتحميل هذه الروشتة'
            ], 403);
        }

        $pdf = Pdf::loadView('pdf.prescription', [
            'prescription' => $prescription
        ]);

        return $pdf->download(
            "Prescription-{$prescription->prescription_number}.pdf"
        );
    }
}
