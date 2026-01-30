<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Favorite;

class DoctormanagmentController extends Controller
{

    public function index(Request $request)
    {
        $query = Doctor::query();


        if ($request->has('spelization_id') && $request->spelization_id != '') {
            $query->where('spelization_id', $request->spelization_id);
        }


        if ($request->has('location') && $request->location != '') {
            $query->where('location', $request->location);
        }


        if ($request->has('experience_years') && $request->experience_years != '') {
            $query->where('experience_years',  $request->experience_years);
        }


        if ($request->has('is_verified') && $request->is_verified != '') {
            $query->where('is_verified', $request->is_verified);
        }

        if ($request->has('user_id') && $request->user_id != '') {
            $query->where('user_id', $request->user_id);
        }


        $doctors = $query->get();


        $patientId = $request->patient_id ?? null;

        if ($patientId) {
            $patient = Patient::find($patientId);
            if ($patient) {
                $patientFavorites = $patient->favorites()->pluck('doctor_id')->toArray();

                $doctors->transform(function ($doctor) use ($patientFavorites) {
                    $doctor->is_favorite = in_array($doctor->id, $patientFavorites);
                    return $doctor;
                });
            } else {

                $doctors->transform(function ($doctor) {
                    $doctor->is_favorite = false;
                    return $doctor;
                });
            }
        } else {

            $doctors->transform(function ($doctor) {
                $doctor->is_favorite = false;
                return $doctor;
            });
        }

        return response()->json($doctors);
    }


    public function toggleFavorite(Request $request, $doctorId)
    {
        $doctor = Doctor::find($doctorId);
        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }
        $patientId = $request->patient_id;

        if (!$patientId) {
            return response()->json(['message' => 'Patient ID required'], 400);
        }

        $favorite = Favorite::where('patient_id', $patientId)
            ->where('doctor_id', $doctor->id)
            ->first();

        if ($favorite) {
            $favorite->update([
                'is_favorite' => !$favorite->is_favorite
            ]);

            $status = $favorite->is_favorite
                ? 'added to favorites'
                : 'deleted from favorites';
        } else {
            Favorite::create([
                'patient_id' => $patientId,
                'doctor_id'  => $doctor->id,
                'is_favorite' => true
            ]);

            $status = 'added to favorites';
        }

        return response()->json([
            'message' => $status,
            'is_favorite' => $status === 'added to favorites'
        ]);
    }
}
