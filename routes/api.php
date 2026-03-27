<?php

use App\Http\Controllers\ParkingController;
use Illuminate\Support\Facades\Route;

Route::get('/sections', [ParkingController::class, 'sections']);
Route::post('/park', [ParkingController::class, 'park']);
Route::post('/checkout', [ParkingController::class, 'checkout']);
