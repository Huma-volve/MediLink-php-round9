<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\DoctorFilteringController;
use App\Http\Controllers\Api\StatisticsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
// Abdulgaffr controllers
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PrescriptionController;
use App\Http\Controllers\Api\DoctormanagmentController;
use App\Http\Controllers\Api\DoctorProfileController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\ChattingController;
use App\Http\Controllers\Api\DoctorSearchController;
use App\Http\Controllers\Api\SpecializationController;
use App\Http\Controllers\Api\TopRatedDoctorsController;
use App\Http\Controllers\Api\WithdrawalController;
use App\Http\Controllers\SettingPatient;



// top rated doctors
Route::get('/top-rated-doctors', [DoctorSearchController::class, 'topRatedDoctors']);


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

        Route::delete('payments/delete/{id}', [PaymentController::class, 'destroy']);

        Route::post('/payments/stripe', [PaymentController::class, 'processStripePayment']);
        Route::get('/doctor/{doctorId}/balance', [PaymentController::class, 'getDoctorBalance']);
        Route::post('/stripe', [PaymentController::class, 'processStripePayment']);
        Route::post('/recalculate-balances', [PaymentController::class, 'recalculateAllDoctorsBalance']);
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
    Route::get('/prescriptions/{id}/download', [PrescriptionController::class, 'download'])
        ->name('prescriptions.download');

    // profile settings
    Route::put('/user/profile-settings', [SettingController::class, 'updateProfile']);
});


// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::prefix('doctor')->group(function () {
    // show doctor schedules
    Route::get('{id}/schedules', [BookingController::class, 'getDoctorSchedules']);

    // show doctor available slots 
    Route::get('{id}/slots', [BookingController::class, 'getSlots']);
});

Route::middleware('auth:sanctum')->group(function () {
    // patient booking an appointment 
    Route::post('doctor/{id}/booking', [BookingController::class, 'store']);

    // patient confirm booking  
    Route::patch('appointments/{id}/confirm', [BookingController::class, 'confirm']);

    // patient cancel appointment 
    Route::patch('appointments/{id}/cancel', [BookingController::class, 'cancel']);

    //show all patient appointments
    Route::get('patient/appointments', [BookingController::class, 'showAppointments']);


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
});

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
Route::post('/logout', [AuthController::class, 'logout']);
// current user info
Route::get('/me', function (Request $request) {
    return response()->json([
        'user' => $request->user()
    ]);
});


// Statistics Routes
Route::middleware('auth:sanctum')->get(
    '/statistics/totals',
    [StatisticsController::class, 'totals']
);


Route::get('/doctors', [DoctormanagmentController::class, 'index']);
Route::post('/doctors/{doctor}/favorite', [DoctormanagmentController::class, 'toggleFavorite'])->middleware("auth:sanctum");

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/top-rated-doctors', [DoctorSearchController::class, 'topRatedDoctors']);



Route::middleware('auth:sanctum')->group(function () {


    Route::post('/logout', [AuthController::class, 'logout']);

    // current user info
    Route::get('/me', function (Request $request) {
        return response()->json([
            'user' => $request->user()

        ]);
    });

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


Route::get('/top-rated-doctors', TopRatedDoctorsController::class);

// doctors searching
Route::get('/doctors/search', [DoctorSearchController::class, 'search']);


Route::middleware('auth:sanctum')->group(function () {

    // profile settings
    Route::put('/user/profile-settings', [SettingController::class, 'updateProfile']);

    // profile settings
    Route::put('/user/profile-settings', [SettingController::class, 'updateProfile']);
    Route::get('/appointments', [AppointmentController::class, 'index']);
    Route::patch('/appointments/{appointment}/confirm', [AppointmentController::class, 'confirmAppointment']);
    Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancelAppointment']);
});
