<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\api\v1\GeneralController;
use App\Http\Controllers\api\v1\PatientController;


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
});


Route::get('/doctors', [DoctorController::class, 'index']);
Route::post('/doctors/{doctor}/favorite', [DoctorController::class, 'toggleFavorite']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/doctors/search', [DoctorController::class, 'search']);

