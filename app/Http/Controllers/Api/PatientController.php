<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function profile(Request $request)
    {
        $user_id = $request->user()->id;
        $patient = Patient::where('user_id', $user_id)->first();

        if (!$patient) {
            return response()->json([
                'message' => 'Patient not found'
            ], 404);
        }
        $data = [
            'patient' => new PatientResource($patient)
        ];
        return response()->json($data);
    }
}
