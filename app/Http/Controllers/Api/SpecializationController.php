<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use App\Models\Specialization;
use App\Helper\ApiResponse;

class SpecializationController extends Controller
{
      public function show()
    {
        $specializations = Specialization::all();
           return ApiResponse::sendResponse(
                200,
                'null',
                $specializations
            );
    }

}
