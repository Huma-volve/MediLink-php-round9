<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Services\DoctorService;
use Illuminate\Http\Request;

class TopRatedDoctorsController extends Controller
{

    protected DoctorService $doctorService;

    public function __construct(DoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
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
