<?php

namespace Tests\Feature;

use App\Models\ParkingSection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParkingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guard_can_view_sections_api(): void
    {
        ParkingSection::create([
            'floor' => '1',
            'section_name' => 'A',
            'max_slots' => 5,
            'available_slots' => 5,
        ]);

        $response = $this->getJson('/api/sections');

        $response->assertStatus(200);
        $response->assertJsonCount(1);
    }

    public function test_guard_can_park_driver(): void
    {
        $section = ParkingSection::create([
            'floor' => '1',
            'section_name' => 'B',
            'max_slots' => 5,
            'available_slots' => 5,
        ]);

        $response = $this->postJson('/api/park', [
            'section_id' => $section->id,
            'plate_number' => 'ABC-101',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Parking granted.',
            'floor' => '1',
            'section' => 'B',
        ]);

        $section->refresh();
        $this->assertEquals(4, $section->available_slots);
    }

    public function test_guard_cannot_park_if_section_is_full(): void
    {
        $section = ParkingSection::create([
            'floor' => '2',
            'section_name' => 'C',
            'max_slots' => 5,
            'available_slots' => 0,
        ]);

        $response = $this->postJson('/api/park', [
            'section_id' => $section->id,
            'plate_number' => 'XYZ-202',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'No available parking space in this section.',
        ]);
    }

    public function test_guard_can_checkout_driver(): void
    {
        $section = ParkingSection::create([
            'floor' => '3',
            'section_name' => 'D',
            'max_slots' => 5,
            'available_slots' => 3,
        ]);

        $parkResponse = $this->postJson('/api/park', [
            'section_id' => $section->id,
            'plate_number' => 'AAA-303',
        ]);

        $cardId = $parkResponse->json('card_id');

        $checkoutResponse = $this->postJson('/api/checkout', [
            'card_id' => $cardId,
        ]);

        $checkoutResponse->assertStatus(200);
        $checkoutResponse->assertJson([
            'message' => 'Checkout successful.',
            'section_id' => $section->id,
        ]);

        $this->assertDatabaseHas('parking_cards', [
            'id' => $cardId,
            'is_active' => false,
        ]);

        $card = \App\Models\ParkingCard::find($cardId);
        $this->assertNotNull($card->checked_out_at);

        $section->refresh();
        $this->assertEquals(3, $section->available_slots);
    }
}
