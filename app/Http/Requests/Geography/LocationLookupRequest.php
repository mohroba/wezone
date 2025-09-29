<?php

namespace App\Http\Requests\Geography;

use Illuminate\Foundation\Http\FormRequest;

class LocationLookupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'radius_km' => ['nullable', 'numeric', 'min:0', 'max:1000'],
            'city_limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'province_limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
