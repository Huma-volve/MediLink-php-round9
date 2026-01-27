<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\Favorite;

class StatisticsController extends Controller
{
    public function totals(Request $request)
    {
        $user = $request->user();

        if (! $user->patient) {
            return response()->json([
                'message' => 'Patient profile not found'
            ], 404);
        }

        $patient = $user->patient;

        return response()->json([
            'total_appointments' => Appointment::where('patient_id', $patient->id)->count(),

            'upcoming_appointments' => Appointment::where('patient_id', $patient->id)
                ->where('status', 'upcoming')
                ->count(),

            'prescriptions' => Prescription::whereIn(
                'appointment_id',
                $patient->appointments()->pluck('id')
            )->count(),

            'favorite_doctors' => Favorite::where('patient_id', $patient->id)
                ->where('is_favorite', true)
                ->count(),
        ]);
    }
}
