<?php

namespace Database\Seeders;

use App\Models\ParkingSection;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        ParkingSection::create([
            'floor' => '1',
            'section_name' => 'A',
            'max_slots' => 5,
            'available_slots' => 5,
        ]);

        ParkingSection::create([
            'floor' => '1',
            'section_name' => 'B',
            'max_slots' => 5,
            'available_slots' => 5,
        ]);

        ParkingSection::create([
            'floor' => '2',
            'section_name' => 'C',
            'max_slots' => 5,
            'available_slots' => 5,
        ]);
    }
}
