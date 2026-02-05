<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Specialization;
use App\Helper\ApiResponse;

class SpecializationController extends Controller
{
      public function index()
    {
        $specializations = Specialization::all();
           return ApiResponse::sendResponse(
                200,
                'null',
                $specializations
            );
    }

}
