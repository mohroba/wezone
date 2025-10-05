<?php
// app/Http/Requests/Geography/LocationLookupRequest.php

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
            'latitude'        => ['required', 'numeric', 'between:-90,90'],
            'longitude'       => ['required', 'numeric', 'between:-180,180'],
            'radius_km'       => ['nullable', 'numeric', 'min:0', 'max:1000'],
            'city_limit'      => ['nullable', 'integer', 'min:1', 'max:100'],
            'province_limit'  => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    /**
     * For Scribe v5.x: tell Scribe these are QUERY params + provide examples.
     */
    public function queryParameters(): array
    {
        return [
            'latitude' => [
                'description' => 'Latitude in degrees (-90 to 90).',
                'example'     => 35.6892,
            ],
            'longitude' => [
                'description' => 'Longitude in degrees (-180 to 180).',
                'example'     => 51.3890,
            ],
            'radius_km' => [
                'description' => 'Search radius in kilometers (0–1000). Defaults to 50.',
                'example'     => 75,
            ],
            'city_limit' => [
                'description' => 'Max number of cities (1–100). Defaults to 10.',
                'example'     => 5,
            ],
            'province_limit' => [
                'description' => 'Max number of provinces (1–100). Defaults to 10.',
                'example'     => 5,
            ],
        ];
    }
}
