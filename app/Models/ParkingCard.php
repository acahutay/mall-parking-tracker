<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingCard extends Model
{
    protected $fillable = [
        'parking_section_id',
        'plate_number',
        'is_active',
        'checked_out_at',
    ];

    public function parkingSection()
    {
        return $this->belongsTo(ParkingSection::class);
    }
}
