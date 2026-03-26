<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreParkingSpotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parking_lot_id' => ['required', 'integer', 'exists:parking_lots,id'],
            'spot_number'    => [
                'required',
                'string',
                'max:20',
                Rule::unique('parking_spots')->where('parking_lot_id', $this->parking_lot_id),
            ],
            'type'           => ['sometimes', 'string', Rule::in(['regular', 'handicap', 'electric'])],
        ];
    }
}
