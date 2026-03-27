<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingSection extends Model
{
    protected $fillable = [
        'floor',
        'section_name',
        'max_slots',
        'available_slots',
    ];

    public function parkingCards()
    {
        return $this->hasMany(ParkingCard::class);
    }
}
