<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use App\Services\DoctorService;
use Illuminate\Http\Request;

class DoctorController extends Controller
{

    protected DoctorService $doctorService;

    public function __construct(DoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }

    public function search(Request $request)
    {

        $doctors = Doctor::with(['user:id,name', 'specialization:id,name'])
            ->where('is_verified', true)
            ->when($request->name, function ($query) use ($request) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('users.name', 'like', '%' . $request->name . '%');
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
        $topDoctors = $this->doctorService->getTopRatedDoctors();

        if ($topDoctors->isEmpty()) {
            return ApiResponse::sendResponse(200, 'No top-rated doctors found', null);
        }

        return ApiResponse::sendResponse(
            200,
            'Top rated doctors fetched successfully',
            DoctorResource::collection($topDoctors)
        );
    }
}
