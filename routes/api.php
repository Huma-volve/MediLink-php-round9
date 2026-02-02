<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\DoctorFilteringController;
use App\Http\Controllers\Api\StatisticsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SpelizationController;
use App\Http\Controllers\Api\PatientController;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DoctormanagmentController;
use App\Http\Controllers\Api\DoctorProfileController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PaymentController;

use App\Http\Controllers\Api\SettingController;


use App\Http\Controllers\Api\PrescriptionController;
use App\Http\Controllers\Api\RecentActivitiesController;

use App\Http\Controllers\Api\DoctorSearchController;
use App\Http\Controllers\Api\SpecializationController;
use App\Http\Controllers\Api\TopRatedDoctorsController;
use App\Http\Controllers\Api\WithdrawalController;

use App\Http\Controllers\SettingPatient;

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



// show specializations
Route::get('specializations', [SpecializationController::class, 'index']);


Route::prefix('settings')->group(function () {
    // show languages
    Route::get('languages', [SettingController::class, 'languages']);
    // help & support
    Route::get('help-item', [SettingController::class, 'helpItem']);
    // about app
    Route::get('app-settings', [SettingController::class, 'appSetting']);
});


// APIs
Route::middleware('auth:sanctum')->group(function () {
    // payments  peoccessing
    Route::prefix('payments')->group(function () {
        Route::get('/', [PaymentController::class, 'index']);
        Route::post('/store', [PaymentController::class, 'store']);
        Route::get('/show/{id}', [PaymentController::class, 'show']);
        Route::post('/{id}/process', [PaymentController::class, 'processPayment']);
        Route::post('/{id}/refund', [PaymentController::class, 'refund']);

        Route::post('/payments/stripe', [PaymentController::class, 'processStripePayment']);
    });

    // Patient settings
    Route::prefix('patient')->group(function () {
        Route::get('/profile', [SettingPatient::class, 'index']);
        Route::post('/profile/update', [SettingPatient::class, 'updateSettings']);
        Route::post('/change-password', [SettingPatient::class, 'changePassword']);
        Route::get('/paymentHistory', [SettingPatient::class, 'paymentHistory']);
        Route::delete('/delete-account', [SettingPatient::class, 'destroy']);
    });

    // doctor diagnosis summary creation
    Route::post('/doctor/prescriptions', [PrescriptionController::class, 'store']);
    Route::get('/prescriptions/{id}/download', [PrescriptionController::class, 'download']);

    // profile settings
    Route::put('/user/profile-settings', [SettingController::class, 'updateProfile']);
});


// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {

    // settings privacy & security
    Route::get('settings/privacy-settings', [SettingController::class, 'privacySetting']);

    // show patient info
    Route::get('patient/profile', [PatientController::class, 'profile']);

    // delete patient account
    Route::delete('proflie/delete', [SettingController::class, 'deleteAccount']);

    // show all user notification
    Route::get('user/notifications', [NotificationController::class, 'index']);

    // mark notification as read
    Route::post('notification/read/{id}', [NotificationController::class, 'isRead']);

    // show doctor profile
    Route::get('doctor/{id}/profile', [DoctorProfileController::class, 'profile']);

    // show doctor withdrawals
    Route::get('doctor/{doctor}/withdrawals', [WithdrawalController::class, 'index']);

    // show doctor current balance
    Route::get('doctor/{doctor}/balance', [WithdrawalController::class, 'showBalance']);

    // doctor request withdrawal
    Route::post('doctor/{doctor}/request/withdrawal', [WithdrawalController::class, 'store']);

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
    // Route::get('spelizations', [GeneralController::class, 'spelizations']);
    Route::get('/doctors/search', [DoctorSearchController::class, 'search']);

    Route::get('/doctors/search', [DoctorSearchController::class, 'search']);
});


Route::get('/doctors', [DoctormanagmentController::class, 'index']);
Route::post('/doctors/{doctor}/favorite', [DoctormanagmentController::class, 'toggleFavorite']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/top-rated-doctors', [DoctorSearchController::class, 'topRatedDoctors']);


// doctors searching
Route::get('/doctors/search', [DoctorSearchController::class, 'search']);

Route::middleware('auth:sanctum')->group(function () {
    // APIs
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

    Route::post('/logout', [AuthController::class, 'logout']);

    // current user info
    Route::get('/me', function (Request $request) {
        return response()->json([
            'user' => $request->user()

        ]);
    });
    Route::get('/doctor/patient/{patient_id}', [PatientController::class, 'doctorView']);



    Route::get('/doctor/patient/{patient_id}', [PatientController::class, 'doctorView']);


    // Statistics Routes
    Route::get('/statistics/totals', [StatisticsController::class, 'totals']);

    // appointment APIs
    Route::get('/appointments', [AppointmentController::class, 'index']);
    Route::patch('/appointments/{appointment}/confirm', [AppointmentController::class, 'confirmAppointment']);
    Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancelAppointment']);
});





Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');








//Route::post('/logout', [AuthController::class, 'logout']);
// });


Route::get('/top-rated-doctors', TopRatedDoctorsController::class);

// doctors searching
Route::get('/doctors/search', [DoctorSearchController::class, 'search']);


Route::middleware('auth:sanctum')->group(function () {

    // appointment APIs

    Route::get('/appointments', [AppointmentController::class, 'index']);
    Route::patch('/appointments/{appointment}/confirm', [AppointmentController::class, 'confirmAppointment']);
    Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancelAppointment']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
