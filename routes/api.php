<?php


use App\Http\Controllers\ApiControllers\DoctorFilteringController;


use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\api\v1\GeneralController;
use App\Http\Controllers\api\v1\PatientController;

use App\Http\Controllers\Api\StatisticsController;

// Abdulgaffr controllers
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\PrescriptionController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\SettingController;



//AbdulGaffar APIs
// doctors searching
Route::get('/doctors/search', [DoctorController::class, 'search']);
Route::middleware('auth:sanctum')->group(function () {
    // doctor diagnosis summary creation
    Route::post('/doctor/prescriptions', [PrescriptionController::class, 'store']);
    // payments  peoccessing
    Route::post('/payments/checkout', [PaymentController::class, 'store']);
    // profile settings
    Route::put('/user/profile-settings', [SettingController::class, 'updateProfile']);
});


// search routes
Route::get('/doctors', [DoctorFilteringController::class, 'index']);
Route::get('/doctors/{id}', [DoctorFilteringController::class, 'show']);
Route::get('/doctors/{id}/reviews', [DoctorFilteringController::class, 'reviews']);
Route::get('/doctors/{id}/doctor-working-hours', [DoctorFilteringController::class, 'workingHours']);


Route::post('/doctors/{doctor}/favorite', [DoctorController::class, 'toggleFavorite']);
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


// Statistics Routes
Route::middleware('auth:sanctum')->get(
    '/statistics/totals',
    [StatisticsController::class, 'totals']
);


Route::group(['prefix' => 'v1'], function () {
    Route::get('spelizations', [GeneralController::class, 'spelizations']);
    Route::get('/doctors/search', [DoctorController::class, 'search']);
    Route::get('/doctors/search', [DoctorController::class, 'search']);
});


Route::get('/doctors', [DoctorController::class, 'index']);
Route::post('/doctors/{doctor}/favorite', [DoctorController::class, 'toggleFavorite']);


Route::get('/top-rated-doctors', [DoctorController::class, 'topRatedDoctors']);
