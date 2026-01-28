<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\PrescriptionController;

Route::get('/user', function (Request $request) {
    return $request->user();

    Route::get('/doctors/search', [DoctorController::class, 'search']);

    Route::post('/doctor/prescriptions', [PrescriptionController::class, 'store']);

    Route::put('/user/profile-settings', [SettingController::class, 'updateProfile']);
})->middleware('auth:sanctum');
