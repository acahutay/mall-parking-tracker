<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParkingSpotRequest;
use App\Http\Requests\UpdateParkingSpotRequest;
use App\Models\ParkingSpot;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ParkingSpotController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = ParkingSpot::query();

        if ($request->filled('parking_lot_id')) {
            $query->where('parking_lot_id', $request->integer('parking_lot_id'));
        }

        if ($request->has('is_available')) {
            $query->where('is_available', filter_var($request->input('is_available'), FILTER_VALIDATE_BOOLEAN));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        return response()->json($query->get());
    }

    public function store(StoreParkingSpotRequest $request): JsonResponse
    {
        $spot = ParkingSpot::create($request->validated());

        return response()->json($spot, 201);
    }

    public function show(ParkingSpot $parkingSpot): JsonResponse
    {
        return response()->json($parkingSpot->load('parkingLot'));
    }

    public function update(UpdateParkingSpotRequest $request, ParkingSpot $parkingSpot): JsonResponse
    {
        $parkingSpot->update($request->validated());

        return response()->json($parkingSpot);
    }

    public function destroy(ParkingSpot $parkingSpot): JsonResponse
    {
        $parkingSpot->delete();

        return response()->json(null, 204);
    }
}
