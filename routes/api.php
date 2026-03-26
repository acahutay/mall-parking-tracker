<?php

use App\Http\Controllers\ParkingLotController;
use App\Http\Controllers\ParkingSessionController;
use App\Http\Controllers\ParkingSpotController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::apiResource('parking-lots', ParkingLotController::class);

Route::apiResource('parking-spots', ParkingSpotController::class);

Route::apiResource('vehicles', VehicleController::class)->only([
    'index', 'store', 'show', 'destroy',
]);

Route::apiResource('parking-sessions', ParkingSessionController::class)->only([
    'index', 'store', 'show',
]);
Route::patch('parking-sessions/{parkingSession}/checkout', [ParkingSessionController::class, 'checkout'])
    ->name('parking-sessions.checkout');
