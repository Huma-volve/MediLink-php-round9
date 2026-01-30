<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ApiDoctorController;
use App\Http\Controllers\api\DoctorProfileController;

// Abdulgaffr controllers
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\api\PatientController;

use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\DoctorSearchController;

use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\api\v1\GeneralController;
// use App\Http\Controllers\api\v1\PatientController;


use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\api\SpelizationController;
use App\Http\Controllers\api\NotificationController;
use App\Http\Controllers\Api\PrescriptionController;
use App\Http\Controllers\Api\DoctormanagmentController;
use App\Http\Controllers\Api\RecentActivitiesController;
use App\Http\Controllers\ApiControllers\DoctorFilteringController;

// doctors searching
Route::get('/doctors/search', [DoctorSearchController::class, 'search']);


// search routes
Route::get('/doctors', [DoctorFilteringController::class, 'index']);
Route::get('/doctors/{id}', [DoctorFilteringController::class, 'show']);
Route::get('/doctors/{id}/reviews', [DoctorFilteringController::class, 'reviews']);
Route::get('/doctors/{id}/doctor-working-hours', [DoctorFilteringController::class, 'workingHours']);


// search routes
Route::get('/doctors_search', [DoctorFilteringController::class, 'search']);
Route::get('/doctor/{id}', [DoctorFilteringController::class, 'doctorsInformation']);
Route::get('/doctor/{id}/reviews', [DoctorFilteringController::class, 'patientReviews']);
Route::get('/doctor/{id}/doctor-working-hours', [DoctorFilteringController::class, 'workingHours']);
Route::get('/doctor/{id}/doctor-working-hours_online', [DoctorFilteringController::class, 'workingHoursOnline']);




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
    // Route::get('/doctors/search', [DoctorController::class, 'search']);
});


Route::get('/doctors', [DoctormanagmentController::class, 'index']);
Route::post('/doctors/{doctor}/favorite', [DoctormanagmentController::class, 'toggleFavorite']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/top-rated-doctors', [DoctorSearchController::class, 'topRatedDoctors']);


//AbdulGaffar APIs
Route::middleware('auth:sanctum')->group(function () {
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
    Route::delete('/user/profile-delete', [SettingController::class, 'deleteAccount']);
});

// appointment APIs
Route::get('/appointments', [AppointmentController::class, 'index'])->middleware('auth:sanctum');
Route::patch('/appointments/{appointment}/confirm', [AppointmentController::class, 'confirmAppointment'])->middleware('auth:sanctum');
Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancelAppointment'])->middleware('auth:sanctum');
