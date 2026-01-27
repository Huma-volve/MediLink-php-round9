<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Doctors\DoctorController;






// Doctor Routes
Route::get('/top-rated-doctors', [DoctorController::class, 'topRatedDoctors']);
