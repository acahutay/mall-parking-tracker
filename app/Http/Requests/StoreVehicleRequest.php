<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'license_plate' => ['required', 'string', 'max:20', 'unique:vehicles,license_plate'],
            'type'          => ['sometimes', 'string', Rule::in(['car', 'motorcycle', 'truck'])],
        ];
    }
}
