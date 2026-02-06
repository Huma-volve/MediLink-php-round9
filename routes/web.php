<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChattingController;


Route::get('/', function () {
    return view('welcome');
});