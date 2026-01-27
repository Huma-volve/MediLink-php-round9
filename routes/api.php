<?php


use App\Http\Controllers\ApiControllers\DoctorFilteringController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\api\v1\GeneralController;
use App\Http\Controllers\api\v1\PatientController;


// search routes
Route::get('/doctors' , [DoctorFilteringController::class , 'index']);
Route::get('/doctors/{id}' , [DoctorFilteringController::class, 'show']);
Route::get('/doctors/{id}/reviews' , [DoctorFilteringController::class, 'reviews']);
Route::get('/doctors/{id}/doctor-working-hours' , [DoctorFilteringController::class, 'workingHours']);


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


Route::group(['prefix' => 'v1'], function () {
    Route::get('spelizations', [GeneralController::class, 'spelizations']);
  Route::get('/doctors/search', [DoctorController::class, 'search']);
});


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

