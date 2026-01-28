<?php

namespace App\Http\Controllers\Api;

use App\Models\Appointment;
use App\Models\Prescription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PrescriptionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            // 'medications' => 'required|array',
            'medications' => 'required|string',
            // 'diagnosis' => 'required|string|min:10',
            'diagnosis' => 'required|string',
            'frequency' => 'required|string',
            'duration_days' => 'required',
            'prescription_date' => 'required|date',
        ]);

        $appointment = Appointment::findOrFail($request->appointment_id);

        $prescription = Prescription::create([
            'appointment_id' => $appointment->id,
            'prescription_number' => 'RX-' . strtoupper(uniqid()),
            'medications' => $request->medications,
            'diagnosis' => $request->diagnosis,
            'frequency' => $request->frequency,
            'duration_days' => $request->duration_days,
            'prescription_date' => $request->prescription_date,
            'expiry_date' => $request->expiry_date ?? now()->addMonths(6),
        ]);

        $appointment->update(['status' => 'completed']);

        return response()->json(
            [
                'message' => 'Diagnosis Summery created successfully',
                'data' => $prescription
            ],
            201 // يعنى تم الإنشاء بنجاح
        );
    }
}
