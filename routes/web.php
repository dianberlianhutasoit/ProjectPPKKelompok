<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacilityController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('facilities', FacilityController::class);
