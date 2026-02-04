<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientResource;
use App\Models\Patient;

use Illuminate\Http\Request;
use App\Helper\ApiResponse;



class PatientController extends Controller
{
    public function profile()
    {
        $user_id = auth()->id();

        $patient = Patient::where('user_id', $user_id)->first();

        if (!$patient) {
            return ApiResponse::sendResponse(
                404,
                'Patient not found',
                $patient
            );
        }
        $data = [
            'patient' => new PatientResource($patient)
        ];
        return ApiResponse::sendResponse(
            200,
            null,
            $data
        );
    }
    public function doctorView($patient_id)
    {
        $patient = Patient::with([
            'medicalHistories.doctor',
            'medicalHistories.prescription.items',
            'prescriptions.items',
            'insurance',
            'user'
        ])->find($patient_id);

        if (!$patient) {
            return response()->json([
                'message' => 'Patient not found'
            ], 404);
        }

        return response()->json([
            'patient' => new PatientResource($patient)
        ]);
    }
}
