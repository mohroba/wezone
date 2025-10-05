<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mobile' => ['required', 'string', 'regex:/^[0-9]{10,15}$/'],
        ];
    }
    public function bodyParameters(): array
    {
        return [
            'mobile' => ['description' => 'Mobile number to receive the OTP (10–15 digits).', 'example' => '09123456789'],
        ];
    }

}
