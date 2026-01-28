<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\PrescriptionController;

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // doctors searching
    Route::get('/doctors/search', [DoctorController::class, 'search']);

    // 2. doctor diagnosis summary creation
    Route::post('/doctor/prescriptions', [PrescriptionController::class, 'store']);

    // payments  peoccessing
    Route::post('/payments/checkout', [PaymentController::class, 'store']);

    // profile settings
    Route::put('/user/profile-settings', [SettingController::class, 'updateProfile']);
});
