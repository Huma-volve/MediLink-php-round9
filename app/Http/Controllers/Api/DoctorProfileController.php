<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorProfileResource;
use Illuminate\Http\Request;
use App\Helper\ApiResponse;
use App\Models\Doctor;

class DoctorProfileController extends Controller
{
    public function profile()
    {
        $user_id = auth()->id();

        $doctor = Doctor::where('user_id', $user_id)->first();

        if (!$doctor) {
            return ApiResponse::sendResponse(
                404,
                'doctor not found',
                $doctor
            );
        }
        $data = [
            'doctor' => new DoctorProfileResource($doctor)
        ];
        return ApiResponse::sendResponse(
            200,
            null,
            $data
        );
    }
}
