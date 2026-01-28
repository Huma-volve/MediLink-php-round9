<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\api\SpelizationController;
use App\Http\Controllers\api\PatientController;
use App\Http\Controllers\ApiControllers\DoctorFilteringController;
use App\Http\Controllers\Api\RecentActivitiesController;
use App\Http\Controllers\ApiDoctorController;

// Abdulgaffr controllers
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\api\NotificationController;
use App\Http\Controllers\Api\PrescriptionController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\StatisticsController;

// search routes
Route::get('/doctors', [DoctorFilteringController::class, 'index']);
Route::get('/doctors/{id}', [DoctorFilteringController::class, 'show']);
Route::get('/doctors/{id}/reviews', [DoctorFilteringController::class, 'reviews']);
Route::get('/doctors/{id}/doctor-working-hours', [DoctorFilteringController::class, 'workingHours']);


Route::get('/doctors', [ApiDoctorController::class, 'index']);
Route::post('/doctors/{doctor}/favorite', [ApiDoctorController::class, 'toggleFavorite']);


// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // show patient info
    Route::get('patient/profile', [PatientController::class, 'profile']);
    // delete patient account
    Route::delete('proflie/delete', [SettingController::class, 'deleteAccount']);
    Route::get('user/notifications', [NotificationController::class, 'index']);
    Route::post('notification/read/{id}', [NotificationController::class, 'isRead']);



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
// Recent Activities Routes
Route::middleware('auth:sanctum')->get(
    '/recent-activities/latest',
    [RecentActivitiesController::class, 'latest']
);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// show spelizations 
Route::get('spelizations', [SpelizationController::class, 'show']);
// show languages 
Route::get('languages', [SettingController::class, 'languages']);


Route::get('/top-rated-doctors', [DoctorController::class, 'topRatedDoctors']);


//AbdulGaffar APIs
// doctors searching
Route::get('/doctors/search', [DoctorController::class, 'search']);

// doctor diagnosis summary creation
Route::post('/doctor/prescriptions', [PrescriptionController::class, 'store']);

// payments  peoccessing
Route::post('/payments/checkout', [PaymentController::class, 'store']);

// profile settings
Route::put('/user/profile-settings', [SettingController::class, 'updateProfile']);
