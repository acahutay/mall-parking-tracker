<?php

namespace App\Http\Controllers;

use App\Models\ParkingCard;
use App\Models\ParkingSection;
use Illuminate\Http\Request;

class ParkingController extends Controller
{
    public function dashboard()
    {
        return view('parking.dashboard', [
            'sections' => ParkingSection::orderBy('floor')->orderBy('section_name')->get(),
            'activeCards' => ParkingCard::where('is_active', true)->latest()->get(),
            'lastFiveCards' => ParkingCard::latest()->take(5)->get(),
        ]);
    }

    public function sections()
    {
        return response()->json(ParkingSection::orderBy('floor')->get());
    }

    public function park(Request $request)
    {
        $result = $this->handlePark($request);

        return response()->json([
            'message' => $result['message'],
            'card_id' => $result['card_id'],
            'floor' => $result['floor'],
            'section' => $result['section'],
        ], $result['status']);
    }

    public function parkFromDashboard(Request $request)
    {
        $result = $this->handlePark($request);

        return redirect('/')->with('status', $result['message']);
    }

    public function checkout(Request $request)
    {
        $result = $this->handleCheckout($request);

        return response()->json([
            'message' => $result['message'],
            'section_id' => $result['section_id'],
        ], $result['status']);
    }

    public function checkoutFromDashboard(Request $request)
    {
        $result = $this->handleCheckout($request);

        return redirect('/')->with('status', $result['message']);
    }

    private function handlePark(Request $request): array
    {
        $section = ParkingSection::find($request->section_id);
        if (! $section) {
            return [
                'message' => 'Section not found.',
                'status' => 404,
                'card_id' => null,
                'floor' => null,
                'section' => null,
            ];
        }

        if ($section->available_slots <= 0) {
            return [
                'message' => 'No available parking space in this section.',
                'status' => 422,
                'card_id' => null,
                'floor' => $section->floor,
                'section' => $section->section_name,
            ];
        }

        $card = ParkingCard::create([
            'parking_section_id' => $section->id,
            'plate_number' => $request->plate_number,
            'is_active' => true,
        ]);

        $section->available_slots = $section->available_slots - 1;
        $section->save();

        return [
            'message' => 'Parking granted.',
            'status' => 200,
            'card_id' => $card->id,
            'floor' => $section->floor,
            'section' => $section->section_name,
        ];
    }

    private function handleCheckout(Request $request): array
    {
        $card = ParkingCard::find($request->card_id);

        if (! $card || ! $card->is_active) {
            return [
                'message' => 'Card not found or already checked out.',
                'status' => 404,
                'section_id' => null,
            ];
        }

        $section = ParkingSection::find($card->parking_section_id);

        $card->is_active = false;
        $card->checked_out_at = now();
        $card->save();

        if ($section) {
            $section->available_slots = $section->available_slots + 1;
            $section->save();
        }

        return [
            'message' => 'Checkout successful.',
            'status' => 200,
            'section_id' => $card->parking_section_id,
        ];
    }
}
