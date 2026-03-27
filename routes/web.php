<?php

use App\Http\Controllers\ParkingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ParkingController::class, 'dashboard'])->name('dashboard');
Route::post('/park', [ParkingController::class, 'parkFromDashboard'])->name('park');
Route::post('/checkout', [ParkingController::class, 'checkoutFromDashboard'])->name('checkout');
