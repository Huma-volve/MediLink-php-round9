<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;


// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    // current user info
    Route::get('/me', function (Request $request) {
        return response()->json([
            'user' => $request->user()
        ]);
    });
});

use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\api\v1\GeneralController;
use App\Http\Controllers\api\v1\PatientController;

Route::group(['prefix' => 'v1'], function () {
    Route::get('spelizations', [GeneralController::class, 'spelizations']);
    Route::get('/doctors/search', [DoctorController::class, 'search']);
});


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




Route::get('/top-rated-doctors', [DoctorController::class, 'topRatedDoctors']);
