<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreParkingSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parking_spot_id' => ['required', 'integer', 'exists:parking_spots,id'],
            'vehicle_id'      => ['required', 'integer', 'exists:vehicles,id'],
            'entry_time'      => ['sometimes', 'date'],
        ];
    }
}
