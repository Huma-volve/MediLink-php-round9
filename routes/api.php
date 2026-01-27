<?php

use App\Http\Controllers\ApiControllers\DoctorController;
use Illuminate\Support\Facades\Route;


Route::get('/doctors' , [DoctorController::class , 'index']);

Route::get('/doctors/{id}' , [DoctorController::class, 'show']);
Route::get('/doctors/{id}/reviews' , [DoctorController::class, 'reviews']);
Route::get('/doctors/{id}/doctor-working-hours' , [DoctorController::class, 'workingHours']);