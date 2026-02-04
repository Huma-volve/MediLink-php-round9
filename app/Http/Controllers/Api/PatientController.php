<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use App\Models\Review;

use Illuminate\Http\Request;


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

    public function createReview(Request $request)
    {
        $patient = $request->user()->patient; 

        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
        ]);

        $review = Review::create([
            'doctor_id' => $request->doctor_id,
            'patient_id' => $patient->id,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return ApiResponse::sendResponse(200 , "review added successfully" , $review);
    }

    public function patientReviews($id)
    {
        $reviews = Review::with(['patient.user' => function($query)
        {
            $query->select('id' , 'name');
        }])
        
        ->where('doctor_id' , $id)
        ->paginate();

        if ($reviews->isEmpty()) {

            return ApiResponse::error(404 , 'Reviews Not Found');

        } else {

            return ApiResponse::sendResponse(200 , 'Patients Reviews' , $reviews);
        }
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
