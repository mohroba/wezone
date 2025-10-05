<?php

namespace App\Http\Requests\Geography;

use Illuminate\Foundation\Http\FormRequest;

class CountryIndexRequest extends FormRequest
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
            'capital_city' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function queryParameters(): array
    {
        return [
            'id'          => ['description' => 'Filter by country ID.', 'example' => 1],
            'name'        => ['description' => 'Filter by localized name.', 'example' => 'Iran'],
            'name_en'     => ['description' => 'Filter by English name.', 'example' => 'Iran'],
            'capital_city'=> ['description' => 'Filter by ID of the capital city.', 'example' => 1001],
            'per_page'    => ['description' => 'Items per page (1–100).', 'example' => 50],
        ];
    }

}
