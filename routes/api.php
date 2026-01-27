<?php

use App\Http\Controllers\api\v1\GeneralController;
use App\Http\Controllers\api\v1\PatientController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::get('spelizations', [GeneralController::class, 'spelizations']);
});
