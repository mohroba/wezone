<?php

namespace Modules\Monetization\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payload' => ['nullable', 'array'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'payload' => [
                'description' => 'Optional gateway callback payload forwarded to the validation endpoint.',
                'example' => ['authority' => 'A0001'],
            ],
        ];
    }
}
