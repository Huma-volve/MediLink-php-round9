<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\api\SpelizationController;
use App\Http\Controllers\api\PatientController;
use App\Http\Controllers\api\SettingController;

// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
        // show patient info
    Route::get('patient/profile', [PatientController::class, 'profile']);
        // delete patient account
    Route::delete('proflie/delete', [SettingController::class, 'deleteAccount']);



    Route::post('/logout', [AuthController::class, 'logout']);
    // current user info
    Route::get('/me', function (Request $request) {
        return response()->json([
            'user' => $request->user()
        ]);
    });
});


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

        // show spelizations 
Route::get('spelizations', [SpelizationController::class, 'show']);
Route::get('/doctors/search', [DoctorController::class, 'search']);
        // show languages 
Route::get('languages', [SettingController::class, 'languages']);
