<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParkingLot extends Model
{
    protected $fillable = [
        'name',
        'address',
    ];

    public function spots(): HasMany
    {
        return $this->hasMany(ParkingSpot::class);
    }
}
