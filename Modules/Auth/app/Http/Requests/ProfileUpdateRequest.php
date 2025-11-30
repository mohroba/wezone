<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['nullable', 'string', 'max:191'],
            'last_name' => ['nullable', 'string', 'max:191'],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'national_id' => ['nullable', 'string', 'max:191'],
            'residence_city_id' => ['nullable', 'integer', 'min:1'],
            'residence_province_id' => ['nullable', 'integer', 'min:1'],
            'profile_image' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp,gif',
                'mimetypes:image/jpeg,image/png,image/webp,image/gif',
                'max:5120',
            ],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'first_name'            => ['description' => 'First name.', 'example' => 'Majid'],
            'last_name'             => ['description' => 'Last name.', 'example' => 'Sabet'],
            'birth_date'            => ['description' => 'Date of birth (YYYY-MM-DD).', 'example' => '1990-05-12'],
            'national_id'           => ['description' => 'National ID.', 'example' => '1234567890'],
            'residence_city_id'     => ['description' => 'City ID of residence.', 'example' => 101],
            'residence_province_id' => ['description' => 'Province ID of residence.', 'example' => 23],
            'profile_image'         => ['description' => 'Profile image file (JPEG, PNG, WebP, or GIF; max 5MB).', 'example' => null],
        ];
    }

}
