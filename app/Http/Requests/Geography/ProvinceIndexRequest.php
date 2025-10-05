<?php

namespace App\Http\Requests\Geography;

use Illuminate\Foundation\Http\FormRequest;

class ProvinceIndexRequest extends FormRequest
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
            'country_id' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function queryParameters(): array
    {
        return [
            'id'        => ['description' => 'Filter by province ID.', 'example' => 23],
            'name'      => ['description' => 'Filter by localized name.', 'example' => 'Tehran'],
            'name_en'   => ['description' => 'Filter by English name.', 'example' => 'Tehran'],
            'country_id'=> ['description' => 'Only provinces in this country ID.', 'example' => 1],
            'per_page'  => ['description' => 'Items per page (1–100).', 'example' => 25],
        ];
    }

}
