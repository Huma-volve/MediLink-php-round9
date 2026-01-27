<?php

namespace App\Http\Controllers\Doctors;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Doctor;

class DoctorController extends Controller
{
    public function topRatedDoctors()
    {
        $doctors = Doctor::with('reviews')
            ->get()
            ->map(function ($doctor) {
                $doctor->average_rating = $doctor->reviews->avg('rating');
                return $doctor;
            })
            ->sortByDesc('average_rating')
            ->take(5)
            ->values();

        return ApiResponse::sendResponse(200, 'Top rated doctors fetched successfully', $doctors);
    }
}
