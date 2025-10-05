<?php

namespace App\Http\Requests\Geography;

use Illuminate\Foundation\Http\FormRequest;

class NearbyCitiesRequest extends FormRequest
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
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function queryParameters(): array
    {
        return [
            'latitude'  => ['description' => 'Latitude in degrees (-90..90).', 'example' => 35.6892],
            'longitude' => ['description' => 'Longitude in degrees (-180..180).', 'example' => 51.3890],
            'radius_km' => ['description' => 'Search radius in km (0–1000). Defaults to 50.', 'example' => 100],
            'limit'     => ['description' => 'Max cities to return (1–100). Defaults to 10.', 'example' => 8],
        ];
    }

}
