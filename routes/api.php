<?php

use App\Http\Controllers\Api\AppointmentController;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
// use App\Http\Controllers\Api\PatientController;



// use Illuminate\Support\Facades\Request;
// use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\api\DoctorProfileController;
use App\Http\Controllers\api\WithdrawalController;
use App\Http\Controllers\Api\SettingController;


use App\Http\Controllers\api\SpecializationController;
use App\Http\Controllers\api\NotificationController;


use App\Http\Controllers\api\PatientController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\DoctorSearchController;
// use App\Http\Controllers\Api\StatisticsController;
// use App\Http\Controllers\Api\PrescriptionController;
use App\Http\Controllers\Api\DoctormanagmentController;
use App\Http\Controllers\Api\RecentActivitiesController;
use App\Http\Controllers\Api\DoctorFilteringController;
// use App\Http\Controllers\api\DoctorProfileController;

use App\Http\Controllers\Api\TopRatedDoctorsController;
// use App\Http\Controllers\api\WithdrawalController;
use App\Http\Controllers\Api\StatisticsController;
// use App\Http\Controllers\Api\DoctormanagmentController;



// Abdulgaffr controllers
// use App\Http\Controllers\api\NotificationController;
use App\Http\Controllers\Api\PrescriptionController;
// use App\Http\Controllers\Api\PaymentController;

// use App\Http\Controllers\Api\SettingController;

// doctors searching
Route::get('/doctors/search', [DoctorSearchController::class, 'search']);


// top rated doctors
Route::get('/top-rated-doctors', [DoctorSearchController::class, 'topRatedDoctors']);






// search routes
Route::get('/doctors', [DoctorFilteringController::class, 'index']);
Route::get('/doctors/{id}', [DoctorFilteringController::class, 'show']);
Route::get('/doctors/{id}/reviews', [DoctorFilteringController::class, 'reviews']);
Route::get('/doctors/{id}/doctor-working-hours', [DoctorFilteringController::class, 'workingHours']);



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

    // doctor request withdrawal
    Route::post('doctor/{doctor}/request/withdrawal', [WithdrawalController::class, 'store']);


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
    

Route::get('/doctor/patient/{patient_id}', [PatientController::class, 'doctorView']);


// Statistics Routes
Route::get('/statistics/totals', [StatisticsController::class, 'totals']);

// appointment APIs
Route::get('/appointments', [AppointmentController::class, 'index']);
Route::patch('/appointments/{appointment}/confirm', [AppointmentController::class, 'confirmAppointment']);
Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancelAppointment']);




//Route::post('/logout', [AuthController::class, 'logout']);
});




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');






Route::get('/top-rated-doctors', TopRatedDoctorsController::class);


//AbdulGaffar APIs
// doctors searching



Route::get('/doctors/search', [DoctorSearchController::class, 'search']);


Route::middleware('auth:sanctum')->group(function () {
    // doctor diagnosis summary creation
    Route::post('/doctor/prescriptions', [PrescriptionController::class, 'store']);

    // payments  peoccessing
    Route::post('/payments/checkout', [PaymentController::class, 'store']);

    // profile settings




    Route::put('/user/profile-settings', [SettingController::class, 'updateProfile']);




    // appointment APIs

    Route::get('/appointments', [AppointmentController::class, 'index']);
    Route::patch('/appointments/{appointment}/confirm', [AppointmentController::class, 'confirmAppointment']);
    Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancelAppointment']);



    // profile settings
    Route::put('/user/profile-settings', [SettingController::class, 'updateProfile']);
});