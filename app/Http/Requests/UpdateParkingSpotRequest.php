<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateParkingSpotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'spot_number'  => ['sometimes', 'required', 'string', 'max:20'],
            'type'         => ['sometimes', 'required', 'string', Rule::in(['regular', 'handicap', 'electric'])],
            'is_available' => ['sometimes', 'required', 'boolean'],
        ];
    }
}
