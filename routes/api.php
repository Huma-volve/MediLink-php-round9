<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;

Route::get('/doctors', [DoctorController::class, 'index']);
Route::post('/doctors/{doctor}/favorite', [DoctorController::class, 'toggleFavorite']);
