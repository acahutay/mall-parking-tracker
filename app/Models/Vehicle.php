<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    protected $fillable = [
        'license_plate',
        'type',
    ];

    public function parkingSessions(): HasMany
    {
        return $this->hasMany(ParkingSession::class);
    }
}
