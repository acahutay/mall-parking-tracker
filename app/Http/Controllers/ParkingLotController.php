<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParkingLotRequest;
use App\Http\Requests\UpdateParkingLotRequest;
use App\Models\ParkingLot;
use Illuminate\Http\JsonResponse;

class ParkingLotController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(ParkingLot::all());
    }

    public function store(StoreParkingLotRequest $request): JsonResponse
    {
        $lot = ParkingLot::create($request->validated());

        return response()->json($lot, 201);
    }

    public function show(ParkingLot $parkingLot): JsonResponse
    {
        return response()->json($parkingLot->load('spots'));
    }

    public function update(UpdateParkingLotRequest $request, ParkingLot $parkingLot): JsonResponse
    {
        $parkingLot->update($request->validated());

        return response()->json($parkingLot);
    }

    public function destroy(ParkingLot $parkingLot): JsonResponse
    {
        $parkingLot->delete();

        return response()->json(null, 204);
    }
}
