<?php

namespace Tests\Feature;

use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_vehicles(): void
    {
        Vehicle::create(['license_plate' => 'ABC-123', 'type' => 'car']);

        $response = $this->getJson('/api/vehicles');

        $response->assertStatus(200)->assertJsonCount(1);
    }

    public function test_can_register_a_vehicle(): void
    {
        $response = $this->postJson('/api/vehicles', [
            'license_plate' => 'XYZ-789',
            'type'          => 'motorcycle',
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['license_plate' => 'XYZ-789']);

        $this->assertDatabaseHas('vehicles', ['license_plate' => 'XYZ-789']);
    }

    public function test_license_plate_must_be_unique(): void
    {
        Vehicle::create(['license_plate' => 'DUP-001']);

        $response = $this->postJson('/api/vehicles', ['license_plate' => 'DUP-001']);

        $response->assertStatus(422)->assertJsonValidationErrors(['license_plate']);
    }

    public function test_can_show_a_vehicle_with_sessions(): void
    {
        $vehicle = Vehicle::create(['license_plate' => 'SHW-999']);

        $response = $this->getJson("/api/vehicles/{$vehicle->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure(['id', 'license_plate', 'type', 'parking_sessions']);
    }

    public function test_can_delete_a_vehicle(): void
    {
        $vehicle = Vehicle::create(['license_plate' => 'DEL-000']);

        $this->deleteJson("/api/vehicles/{$vehicle->id}")->assertStatus(204);
        $this->assertDatabaseMissing('vehicles', ['id' => $vehicle->id]);
    }
}
