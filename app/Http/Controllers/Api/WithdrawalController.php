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
    public function index()
    {
        $user_id = auth()->id();

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


    public function showBalance()
    {
        $user_id = auth()->id();

        $doctor = Doctor::where('user_id', $user_id)->first();

        $current_balance = $doctor->current_balance;

        return ApiResponse::sendResponse(
            200,
            null,
            $current_balance
        );
    }

    public function store(Request $request)
    {
        $user_id = auth()->id();

        $doctor = Doctor::where('user_id', $user_id)->first();

        if (!$doctor) {
            return ApiResponse::sendResponse(
                404,
                'doctor not found',
                null
            );
        }

        $doctor_current_balance = $doctor->current_balance;

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        if ($validated['amount'] > $doctor_current_balance) {
            return ApiResponse::sendResponse(
                400,
                'Your current balance is not sufficient for this withdrawal',
                null
            );
        } else {

            Withdrawal::create([
                'doctor_id' => $doctor->id,
                'amount' => $validated['amount'],
            ]);

            return ApiResponse::sendResponse(
                201,
                'Withdrawal request submitted successfully',
                null
            );
        }
    }
}
