<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Spelization;
use App\Helper\ApiResponse;

class SpelizationController extends Controller
{
    public function show()
    {
        $spelizations = Spelization::all();
           return ApiResponse::sendResponse(
                200,
                'null',
                $spelizations
            );
    }
}
