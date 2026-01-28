<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecentActivitiesController extends Controller
{
    public function latest(Request $request)
    {
        $user = $request->user();

        if (! $user->patient) {
            return response()->json([
                'message' => 'Patient profile not found'
            ], 404);
        }

        $patientId = $user->patient->id;

        $lastAppointment = DB::table('appointments')
            ->join('doctors', 'doctors.id', '=', 'appointments.doctor_id')
            ->where('appointments.patient_id', $patientId)
            ->orderBy('appointments.created_at', 'desc')
            ->select(
                'appointments.id',
                'doctors.name as doctor_name',
                'appointments.appointment_date',
                'appointments.appointment_time',
                'appointments.status',
                'appointments.created_at'
            )
            ->first();

        $lastPrescription = DB::table('prescriptions')
            ->join('appointments', 'appointments.id', '=', 'prescriptions.appointment_id')
            ->join('doctors', 'doctors.id', '=', 'appointments.doctor_id')
            ->where('appointments.patient_id', $patientId)
            ->orderBy('prescriptions.created_at', 'desc')
            ->select(
                'prescriptions.id',
                'prescriptions.prescription_number',
                'doctors.name as doctor_name',
                'prescriptions.prescription_date',
                'prescriptions.created_at'
            )
            ->first();

        $lastFavorite = DB::table('favorites')
            ->join('doctors', 'doctors.id', '=', 'favorites.doctor_id')
            ->where('favorites.patient_id', $patientId)
            ->where('favorites.is_favorite', true)
            ->orderBy('favorites.created_at', 'desc')
            ->select(
                'favorites.id',
                'doctors.name as doctor_name',
                'favorites.created_at'
            )
            ->first();

        return response()->json([
            'last_appointment' => $lastAppointment,
            'last_prescription' => $lastPrescription,
            'last_favorite' => $lastFavorite,
        ]);
    }
}
