<?php

namespace Tests\Feature;

use App\Models\ParkingLot;
use App\Models\ParkingSession;
use App\Models\ParkingSpot;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParkingSessionTest extends TestCase
{
    use RefreshDatabase;

    private ParkingSpot $spot;
    private Vehicle $vehicle;

    protected function setUp(): void
    {
        parent::setUp();

        $lot = ParkingLot::create(['name' => 'Test Lot', 'address' => '1 Test St']);
        $this->spot = ParkingSpot::create([
            'parking_lot_id' => $lot->id,
            'spot_number'    => 'A1',
            'type'           => 'regular',
            'is_available'   => true,
        ]);
        $this->vehicle = Vehicle::create(['license_plate' => 'TST-001', 'type' => 'car']);
    }

    public function test_can_check_in_a_vehicle(): void
    {
        $response = $this->postJson('/api/parking-sessions', [
            'parking_spot_id' => $this->spot->id,
            'vehicle_id'      => $this->vehicle->id,
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['vehicle_id' => $this->vehicle->id]);

        $this->assertDatabaseHas('parking_sessions', [
            'parking_spot_id' => $this->spot->id,
            'vehicle_id'      => $this->vehicle->id,
        ]);

        $this->assertDatabaseHas('parking_spots', [
            'id'           => $this->spot->id,
            'is_available' => false,
        ]);
    }

    public function test_cannot_check_in_to_occupied_spot(): void
    {
        $this->spot->update(['is_available' => false]);

        $response = $this->postJson('/api/parking-sessions', [
            'parking_spot_id' => $this->spot->id,
            'vehicle_id'      => $this->vehicle->id,
        ]);

        $response->assertStatus(422)
                 ->assertJsonFragment(['message' => 'The selected parking spot is not available.']);
    }

    public function test_can_check_out_a_vehicle_and_calculates_fee(): void
    {
        $session = ParkingSession::create([
            'parking_spot_id' => $this->spot->id,
            'vehicle_id'      => $this->vehicle->id,
            'entry_time'      => Carbon::now()->subHours(2),
        ]);
        $this->spot->update(['is_available' => false]);

        $response = $this->patchJson("/api/parking-sessions/{$session->id}/checkout");

        $response->assertStatus(200)
                 ->assertJsonStructure(['id', 'exit_time', 'fee'])
                 ->assertJsonPath('fee', '4.00');

        $this->assertDatabaseHas('parking_spots', [
            'id'           => $this->spot->id,
            'is_available' => true,
        ]);
    }

    public function test_cannot_checkout_already_completed_session(): void
    {
        $session = ParkingSession::create([
            'parking_spot_id' => $this->spot->id,
            'vehicle_id'      => $this->vehicle->id,
            'entry_time'      => Carbon::now()->subHours(1),
            'exit_time'       => Carbon::now(),
            'fee'             => 2.00,
        ]);

        $response = $this->patchJson("/api/parking-sessions/{$session->id}/checkout");

        $response->assertStatus(422)
                 ->assertJsonFragment(['message' => 'This session has already been checked out.']);
    }

    public function test_fee_is_minimum_one_hour(): void
    {
        $session = ParkingSession::create([
            'parking_spot_id' => $this->spot->id,
            'vehicle_id'      => $this->vehicle->id,
            'entry_time'      => Carbon::now()->subMinutes(15),
        ]);
        $this->spot->update(['is_available' => false]);

        $response = $this->patchJson("/api/parking-sessions/{$session->id}/checkout");

        $response->assertStatus(200)
                 ->assertJsonPath('fee', '2.00');
    }

    public function test_can_list_parking_sessions(): void
    {
        ParkingSession::create([
            'parking_spot_id' => $this->spot->id,
            'vehicle_id'      => $this->vehicle->id,
            'entry_time'      => now(),
        ]);

        $response = $this->getJson('/api/parking-sessions');

        $response->assertStatus(200)->assertJsonCount(1);
    }

    public function test_can_show_a_parking_session(): void
    {
        $session = ParkingSession::create([
            'parking_spot_id' => $this->spot->id,
            'vehicle_id'      => $this->vehicle->id,
            'entry_time'      => now(),
        ]);

        $response = $this->getJson("/api/parking-sessions/{$session->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure(['id', 'parking_spot', 'vehicle', 'entry_time']);
    }
}
