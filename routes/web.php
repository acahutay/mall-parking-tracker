<?php

use App\Http\Controllers\ParkingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ParkingController::class, 'dashboard']);
Route::post('/park', [ParkingController::class, 'parkFromDashboard']);
Route::post('/checkout', [ParkingController::class, 'checkoutFromDashboard']);
