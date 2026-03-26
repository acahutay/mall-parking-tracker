<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParkingSessionRequest;
use App\Models\ParkingSession;
use App\Models\ParkingSpot;
use Illuminate\Http\JsonResponse;

class ParkingSessionController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            ParkingSession::with(['parkingSpot', 'vehicle'])->latest()->get()
        );
    }

    public function store(StoreParkingSessionRequest $request): JsonResponse
    {
        $spot = ParkingSpot::findOrFail($request->validated()['parking_spot_id']);

        if (! $spot->is_available) {
            return response()->json(['message' => 'The selected parking spot is not available.'], 422);
        }

        $session = ParkingSession::create([
            'parking_spot_id' => $spot->id,
            'vehicle_id'      => $request->validated()['vehicle_id'],
            'entry_time'      => $request->validated()['entry_time'] ?? now(),
        ]);

        $spot->update(['is_available' => false]);

        return response()->json($session->load(['parkingSpot', 'vehicle']), 201);
    }

    public function show(ParkingSession $parkingSession): JsonResponse
    {
        return response()->json($parkingSession->load(['parkingSpot', 'vehicle']));
    }

    /**
     * Check out a vehicle: record exit time and compute the fee.
     */
    public function checkout(ParkingSession $parkingSession): JsonResponse
    {
        if ($parkingSession->exit_time !== null) {
            return response()->json(['message' => 'This session has already been checked out.'], 422);
        }

        $parkingSession->exit_time = now();
        $parkingSession->fee = $parkingSession->calculateFee();
        $parkingSession->save();

        $parkingSession->parkingSpot->update(['is_available' => true]);

        return response()->json($parkingSession->load(['parkingSpot', 'vehicle']));
    }
}
