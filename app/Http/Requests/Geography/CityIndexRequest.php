<?php

namespace App\Http\Requests\Geography;

use Illuminate\Foundation\Http\FormRequest;

class CityIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['nullable', 'integer', 'min:1'],
            'name' => ['nullable', 'string'],
            'name_en' => ['nullable', 'string'],
            'province_id' => ['nullable', 'integer', 'min:1'],
            'country_id' => ['nullable', 'integer', 'min:1'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function queryParameters(): array
    {
        return [
            'id'          => ['description' => 'Filter by city ID.', 'example' => 1234],
            'name'        => ['description' => 'Filter by localized name.', 'example' => 'Karaj'],
            'name_en'     => ['description' => 'Filter by English name.', 'example' => 'Karaj'],
            'province_id' => ['description' => 'Only cities in this province ID.', 'example' => 23],
            'country_id'  => ['description' => 'Only cities in this country ID.', 'example' => 1],
            'latitude'    => ['description' => 'Filter by latitude (exact/your logic).', 'example' => 35.8000],
            'longitude'   => ['description' => 'Filter by longitude (exact/your logic).', 'example' => 51.0000],
            'per_page'    => ['description' => 'Items per page (1–100).', 'example' => 25],
        ];
    }

}
