<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Doctor;
use App\Models\Favorite;

class DoctormanagmentController extends Controller
{

    public function index(Request $request)
    {
        $patient = Auth::user()?->patient;

        $doctors = Doctor::filter($request)
            ->with([
                'favorites' => fn($q) => $patient
                    ? $q->where('patient_id', $patient->id)->where('is_favorite', true)
                    : $q->whereRaw('0=1')
            ])
            ->paginate(10);

        return response()->json($doctors);
    }

    public function toggleFavorite($doctorId)
    {
        $doctor = Doctor::findOrFail($doctorId);
        $patient = Auth::user()?->patient;

        if (!$patient) {
            return response()->json([
                'message' => 'Guests cannot mark favorite'
            ], 401);
        }

        $favorite = $doctor->favorites()->where('patient_id', $patient->id)->first();

        if ($favorite) {
            $favorite->update(['is_favorite' => !$favorite->is_favorite]);
            $isFavorite = $favorite->is_favorite;
        } else {
            $doctor->favorites()->create([
                'patient_id'  => $patient->id,
                'is_favorite' => true
            ]);
            $isFavorite = true;
        }

        return response()->json([
            'doctor_id'   => $doctor->id,
            'is_favorite' => $isFavorite
        ]);
    }
}
