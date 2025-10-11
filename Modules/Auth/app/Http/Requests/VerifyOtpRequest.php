<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mobile' => ['required', 'string', 'regex:/^[0-9]{10,15}$/'],
            'otp' => ['required', 'string', 'digits:6'],
            'username' => ['nullable', 'string', 'max:191'],
            'email' => ['nullable', 'email', 'max:191'],
            'first_name' => ['nullable', 'string', 'max:191'],
            'last_name' => ['nullable', 'string', 'max:191'],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'national_id' => ['nullable', 'string', 'max:191'],
            'residence_city_id' => ['nullable', 'integer', 'min:1'],
            'residence_province_id' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'mobile'                => ['description' => 'Mobile number (10–15 digits).', 'example' => '09123456789'],
            'otp'                   => ['description' => '6-digit OTP code.', 'example' => '123456'],
            'username'              => ['description' => 'Optional username to set on first login.', 'example' => 'majid'],
            'email'                 => ['description' => 'Optional email to set.', 'example' => 'majid@example.com'],
            'first_name'            => ['description' => 'Optional first name.', 'example' => 'Majid'],
            'last_name'             => ['description' => 'Optional last name.', 'example' => 'Sabet'],
            'birth_date'            => ['description' => 'Optional DOB (YYYY-MM-DD).', 'example' => '1990-05-12'],
            'national_id'           => ['description' => 'Optional national ID.', 'example' => '1234567890'],
            'residence_city_id'     => ['description' => 'Optional city ID.', 'example' => 101],
            'residence_province_id' => ['description' => 'Optional province ID.', 'example' => 23],
        ];
    }

}
