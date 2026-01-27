<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\favorite;

class DoctorController extends Controller
{

    public function index(Request $request)
    {
        $query = Doctor::query();


        if ($request->has('specialization') && $request->specialization != '') {
            $query->where('spelization_id', $request->specialization);
        }


        if ($request->has('location') && $request->location != '') {
            $query->where('location', $request->location);
        }


        if ($request->has('min_experience') && $request->min_experience != '') {
            $query->where('experience_years', '>=', $request->min_experience);
        }


        if ($request->has('is_verified') && $request->is_verified != '') {
            $query->where('is_verified', $request->is_verified);
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


    public function toggleFavorite(Request $request, Doctor $doctor)
    {
        $patientId = $request->patient_id;

        if (!$patientId) {
            return response()->json(['message' => 'Patient ID required'], 400);
        }

        $favorite = favorite::where('patient_id', $patientId)
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
            favorite::create([
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
