<?php

namespace App\Http\Controllers;

use App\Models\ParkingCard;
use App\Models\ParkingSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ParkingController extends Controller
{
    public function dashboard()
    {
        return view('parking.dashboard', [
            'sections' => ParkingSection::orderBy('floor')->orderBy('section_name')->get(),
            'activeCards' => ParkingCard::where('is_active', true)->latest()->paginate(50),
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

        return redirect(route('dashboard'))->with('status', $result['message']);
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

        return redirect(route('dashboard'))->with('status', $result['message']);
    }

    private function handlePark(Request $request): array
    {
        $request->validate([
            'section_id' => ['required', 'integer', 'exists:parking_sections,id'],
            'plate_number' => ['nullable', 'string', 'max:20'],
        ]);

        return DB::transaction(function () use ($request) {
            $section = ParkingSection::lockForUpdate()->find($request->section_id);

            if ($section->available_slots <= 0) {
                return [
                    'message' => 'No available parking space in this section.',
                    'status' => 422,
                    'card_id' => null,
                    'floor' => $section->floor,
                    'section' => $section->section_name,
                ];
            }

            $section->available_slots = $section->available_slots - 1;
            $section->save();

            $card = ParkingCard::create([
                'parking_section_id' => $section->id,
                'plate_number' => $request->plate_number,
                'is_active' => true,
            ]);

            return [
                'message' => 'Parking granted.',
                'status' => 200,
                'card_id' => $card->id,
                'floor' => $section->floor,
                'section' => $section->section_name,
            ];
        });
    }

    private function handleCheckout(Request $request): array
    {
        $request->validate([
            'card_id' => [
                'required',
                'integer',
                Rule::exists('parking_cards', 'id')->where('is_active', true),
            ],
        ]);

        return DB::transaction(function () use ($request) {
            $card = ParkingCard::lockForUpdate()->find($request->card_id);

            if (! $card || ! $card->is_active) {
                return [
                    'message' => 'Card not found or already checked out.',
                    'status' => 404,
                    'section_id' => null,
                ];
            }

            $card->is_active = false;
            $card->checked_out_at = now();
            $card->save();

            $section = ParkingSection::lockForUpdate()->find($card->parking_section_id);

            if ($section) {
                $section->available_slots = min($section->available_slots + 1, $section->max_slots);
                $section->save();
            }

            return [
                'message' => 'Checkout successful.',
                'status' => 200,
                'section_id' => $card->parking_section_id,
            ];
        });
    }
}
