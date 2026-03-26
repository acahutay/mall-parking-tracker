<?php

namespace Tests\Feature;

use App\Models\ParkingLot;
use App\Models\ParkingSpot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParkingSpotTest extends TestCase
{
    use RefreshDatabase;

    private ParkingLot $lot;

    protected function setUp(): void
    {
        parent::setUp();
        $this->lot = ParkingLot::create(['name' => 'Test Lot', 'address' => '1 Test St']);
    }

    public function test_can_list_parking_spots(): void
    {
        ParkingSpot::create([
            'parking_lot_id' => $this->lot->id,
            'spot_number'    => 'A1',
            'type'           => 'regular',
        ]);

        $response = $this->getJson('/api/parking-spots');

        $response->assertStatus(200)->assertJsonCount(1);
    }

    public function test_can_filter_spots_by_availability(): void
    {
        ParkingSpot::create(['parking_lot_id' => $this->lot->id, 'spot_number' => 'A1', 'is_available' => true]);
        ParkingSpot::create(['parking_lot_id' => $this->lot->id, 'spot_number' => 'A2', 'is_available' => false]);

        $response = $this->getJson('/api/parking-spots?is_available=true');

        $response->assertStatus(200)->assertJsonCount(1);
    }

    public function test_can_create_a_parking_spot(): void
    {
        $response = $this->postJson('/api/parking-spots', [
            'parking_lot_id' => $this->lot->id,
            'spot_number'    => 'B1',
            'type'           => 'electric',
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['spot_number' => 'B1', 'type' => 'electric']);
    }

    public function test_spot_number_must_be_unique_within_lot(): void
    {
        ParkingSpot::create(['parking_lot_id' => $this->lot->id, 'spot_number' => 'A1']);

        $response = $this->postJson('/api/parking-spots', [
            'parking_lot_id' => $this->lot->id,
            'spot_number'    => 'A1',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['spot_number']);
    }

    public function test_can_update_a_parking_spot(): void
    {
        $spot = ParkingSpot::create([
            'parking_lot_id' => $this->lot->id,
            'spot_number'    => 'C1',
            'type'           => 'regular',
        ]);

        $response = $this->putJson("/api/parking-spots/{$spot->id}", ['type' => 'handicap']);

        $response->assertStatus(200)->assertJsonFragment(['type' => 'handicap']);
    }

    public function test_can_delete_a_parking_spot(): void
    {
        $spot = ParkingSpot::create(['parking_lot_id' => $this->lot->id, 'spot_number' => 'D1']);

        $this->deleteJson("/api/parking-spots/{$spot->id}")->assertStatus(204);
        $this->assertDatabaseMissing('parking_spots', ['id' => $spot->id]);
    }
}
