<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParkingSession extends Model
{
    /** Hourly rate in dollars */
    const HOURLY_RATE = 2.00;

    protected $fillable = [
        'parking_spot_id',
        'vehicle_id',
        'entry_time',
        'exit_time',
        'fee',
    ];

    protected $casts = [
        'entry_time' => 'datetime',
        'exit_time' => 'datetime',
        'fee' => 'decimal:2',
    ];

    public function parkingSpot(): BelongsTo
    {
        return $this->belongsTo(ParkingSpot::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Calculate the parking fee based on duration.
     * Minimum 1 hour, rounded up to the nearest hour.
     */
    public function calculateFee(): float
    {
        $entry = Carbon::parse($this->entry_time);
        $exit = Carbon::parse($this->exit_time ?? now());

        $hours = (int) ceil(max(1, $entry->diffInMinutes($exit) / 60));

        return round($hours * self::HOURLY_RATE, 2);
    }
}
