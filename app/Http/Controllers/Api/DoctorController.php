<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{

    public function search(Request $request)
    {

        $doctors = Doctor::with(['user:id,name', 'specialization:id,name'])
            ->where('is_verified', true)
            ->when($request->name, function ($query) use ($request) {

                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->name . '%');
                });
            })
            ->when($request->speciality_id, function ($query) use ($request) {

                $query->where('spelization_id', $request->speciality_id);
            })
            ->when($request->city, function ($query) use ($request) {

                $query->where('location', 'like', '%' . $request->city . '%');
            })
            ->get();

        return response()->json([
            'success' => true,
            'data' => $doctors
        ], 200);
    }

    public function topRatedDoctors()
    {
        $doctors = Doctor::with('reviews')->get();

        $topDoctors = $doctors->sortByDesc(function ($doctor) {
            return $doctor->reviews->avg('rating');
        })->take(5)->values();

        return ApiResponse::sendResponse(200, 'Top rated doctors fetched successfully', DoctorResource::collection($topDoctors));
    }
}
