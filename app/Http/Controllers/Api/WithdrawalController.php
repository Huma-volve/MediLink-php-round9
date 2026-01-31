<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use App\Helper\ApiResponse;
use App\Http\Resources\WithdrawalResource;
use App\Models\Doctor;

class WithdrawalController extends Controller
{
    public function show(Request $request)
    {
        $user_id = $request->user()->id;

        $doctor_id = Doctor::where('user_id', $user_id)->first()->id;

        $doctor_withdrawals = Withdrawal::where('doctor_id', $doctor_id)
            ->orderBy('processed_at', 'desc')
            ->get();

        if ($doctor_withdrawals->isNotEmpty()) {
            $data = [
                'doctor_withdrawals' => WithdrawalResource::collection($doctor_withdrawals)
            ];
            return ApiResponse::sendResponse(
                200,
                null,
                $data
            );
        }
        return ApiResponse::sendResponse(
            200,
            null,
            []
        );
    }
}
