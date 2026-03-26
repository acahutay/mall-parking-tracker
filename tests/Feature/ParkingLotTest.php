<?php

namespace Tests\Feature;

use App\Models\ParkingLot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParkingLotTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_parking_lots(): void
    {
        ParkingLot::create(['name' => 'Lot A', 'address' => '123 Main St']);

        $response = $this->getJson('/api/parking-lots');

        $response->assertStatus(200)
                 ->assertJsonCount(1);
    }

    public function test_can_create_a_parking_lot(): void
    {
        $response = $this->postJson('/api/parking-lots', [
            'name'    => 'North Wing',
            'address' => '1 Mall Drive',
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'North Wing']);

        $this->assertDatabaseHas('parking_lots', ['name' => 'North Wing']);
    }

    public function test_create_parking_lot_validates_required_fields(): void
    {
        $response = $this->postJson('/api/parking-lots', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'address']);
    }

    public function test_can_show_a_parking_lot_with_spots(): void
    {
        $lot = ParkingLot::create(['name' => 'Lot B', 'address' => '2 Mall Drive']);

        $response = $this->getJson("/api/parking-lots/{$lot->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Lot B'])
                 ->assertJsonStructure(['id', 'name', 'address', 'spots']);
    }

    public function test_can_update_a_parking_lot(): void
    {
        $lot = ParkingLot::create(['name' => 'Old Name', 'address' => 'Old Address']);

        $response = $this->putJson("/api/parking-lots/{$lot->id}", [
            'name'    => 'New Name',
            'address' => 'New Address',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'New Name']);
    }

    public function test_can_delete_a_parking_lot(): void
    {
        $lot = ParkingLot::create(['name' => 'Lot C', 'address' => '3 Mall Drive']);

        $response = $this->deleteJson("/api/parking-lots/{$lot->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('parking_lots', ['id' => $lot->id]);
    }
}
