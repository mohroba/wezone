<?php
// app/Http/Requests/Geography/ResolveCityRequest.php

namespace App\Http\Requests\Geography;

use Illuminate\Foundation\Http\FormRequest;

class ResolveCityRequest extends FormRequest
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
            'max_distance_km' => ['nullable', 'numeric', 'min:0', 'max:1000'],
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
                'example'     => 35.7000,
            ],
            'longitude' => [
                'description' => 'Longitude in degrees (-180 to 180).',
                'example'     => 51.4000,
            ],
            'max_distance_km' => [
                'description' => 'Maximum search distance in kilometers (0–1000). Defaults to 50.',
                'example'     => 30,
            ],
        ];
    }
}
