<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\DoctorFilteringController;
use App\Http\Controllers\Api\StatisticsController;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PatientController;

// Abdulgaffr controllers
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PrescriptionController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\ChattingController;





Route::get('/doctors', [DoctorController::class, 'index']);
Route::post('/doctors/{doctor}/favorite', [DoctorController::class, 'toggleFavorite']);
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


    // search routes
    Route::get('/doctors_search', [DoctorFilteringController::class, 'search']);
    Route::get('/doctor/{id}', [DoctorFilteringController::class, 'doctorsInformation']);
    Route::get('/doctor/{id}/doctor-working-hours', [DoctorFilteringController::class, 'workingHours']);
    Route::get('/doctor/{id}/doctor-working-hours_online', [DoctorFilteringController::class, 'workingHoursOnline']);

    // create working hours
    Route::post('/doctor/{id}/create-working-hours', [DoctorFilteringController::class, 'createWorkingDays']);

    // patient reviews
    Route::get('/doctor/{id}/reviews', [PatientController::class, 'patientReviews']);
    Route::post('/create_review', [PatientController::class, 'createReview']);

    // Apis For Chat
    Route::post('/chat/send' , [ChattingController::class , 'createMessage']);
    Route::get('/chat/{userId}' , [ChattingController::class, 'showMessage']);
    Route::post('/chat/read/{userId}' , [ChattingController::class, 'markAsRead']);
    Route::get('/chat/count_unread_messages/{userId}' , [ChattingController::class, 'countMessage']);
});

// Statistics Routes
Route::middleware('auth:sanctum')->get(
    '/statistics/totals',
    [StatisticsController::class, 'totals']
);


Route::group(['prefix' => 'v1'], function () {
    // Route::get('spelizations', [GeneralController::class, 'spelizations']);
    Route::get('/doctors/search', [DoctorController::class, 'search']);
    Route::get('/doctors/search', [DoctorController::class, 'search']);
});


Route::get('/doctors', [DoctorController::class, 'index']);
Route::post('/doctors/{doctor}/favorite', [DoctorController::class, 'toggleFavorite']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




Route::get('/top-rated-doctors', [DoctorController::class, 'topRatedDoctors']);
Route::get('/doctors/search', [DoctorController::class, 'search']);


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




    // appointment APIs
    Route::get('/appointments', [AppointmentController::class, 'index'])->middleware('auth:sanctum');
    Route::patch('/appointments/{appointment}/confirm', [AppointmentController::class, 'confirmAppointment'])->middleware('auth:sanctum');
    Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancelAppointment'])->middleware('auth:sanctum');
    // profile settings
    Route::put('/user/profile-settings', [SettingController::class, 'updateProfile']);
});
