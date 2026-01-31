<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\api\DoctorProfileController;
use App\Http\Controllers\api\WithdrawalController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\api\SpelizationController;
use App\Http\Controllers\api\NotificationController;

use App\Http\Controllers\api\PatientController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\DoctorSearchController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\PrescriptionController;
use App\Http\Controllers\Api\DoctormanagmentController;
use App\Http\Controllers\Api\RecentActivitiesController;
use App\Http\Controllers\Api\DoctorFilteringController;

// doctors searching
Route::get('/doctors/search', [DoctorSearchController::class, 'search']);

// top rated doctors
Route::get('/top-rated-doctors', [DoctorSearchController::class, 'topRatedDoctors']);


Route::get('/doctors', [DoctormanagmentController::class, 'index']);
Route::post('/doctors/{doctor}/favorite', [DoctormanagmentController::class, 'toggleFavorite']);

// search routes
Route::get('/doctors_search', [DoctorFilteringController::class, 'search']);
Route::get('/doctor/{id}', [DoctorFilteringController::class, 'doctorsInformation']);
Route::get('/doctor/{id}/reviews', [DoctorFilteringController::class, 'patientReviews']);
Route::get('/doctor/{id}/doctor-working-hours', [DoctorFilteringController::class, 'workingHours']);
Route::get('/doctor/{id}/doctor-working-hours_online', [DoctorFilteringController::class, 'workingHoursOnline']);

// show spelizations
Route::get('spelizations', [SpelizationController::class, 'show']);

// show languages
Route::get('languages', [SettingController::class, 'languages']);

// settings help
Route::get('settings/help', [SettingController::class, 'help']);

// settings privacy
Route::get('settings/privacy', [SettingController::class, 'privacy']);

// settings about app
Route::get('settings/about', [SettingController::class, 'about']);



// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // show patient info
    Route::get('patient/profile', [PatientController::class, 'profile']);

    // delete patient account
    Route::delete('proflie/delete', [SettingController::class, 'deleteAccount']);

    // show all user notification
    Route::get('user/notifications', [NotificationController::class, 'index']);

    // mark notification as read
    Route::post('notification/read/{id}', [NotificationController::class, 'isRead']);

    // show doctor profile
    Route::get('doctor/profile', [DoctorProfileController::class, 'profile']);

    // show doctor withdrawals
    Route::get('doctor/{doctor}/withdrawals', [WithdrawalController::class, 'show']);

    //AbdulGaffar APIs
    // doctor diagnosis summary creation
    Route::post('/doctor/prescriptions', [PrescriptionController::class, 'store']);

    // payments  peoccessing
    Route::prefix('payments')->group(function () {
        Route::get('/', [PaymentController::class, 'index']);
        Route::post('/store', [PaymentController::class, 'store']);
        Route::get('/show/{id}', [PaymentController::class, 'show']);
        Route::put('/update/{id}', [PaymentController::class, 'update']);
        Route::delete('/delete/{id}', [PaymentController::class, 'destroy']);
        Route::post('/{id}/process', [PaymentController::class, 'processPayment']);
        Route::post('/{id}/refund', [PaymentController::class, 'refund']);
    });

    // profile settings
    Route::put('/user/profile-settings', [SettingController::class, 'updateProfile']);


    // current user info
    Route::get('/me', function (Request $request) {
        return response()->json([
            'user' => $request->user()
        ]);
    });

    // Statistics Routes
    Route::get('/statistics/totals', [StatisticsController::class, 'totals']);

    // appointment APIs
    Route::get('/appointments', [AppointmentController::class, 'index']);
    Route::patch('/appointments/{appointment}/confirm', [AppointmentController::class, 'confirmAppointment']);
    Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancelAppointment']);

    Route::post('/logout', [AuthController::class, 'logout']);
});



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
