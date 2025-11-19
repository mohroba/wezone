<?php

namespace Modules\Monetization\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InitiatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'gateway' => ['nullable', 'string'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'gateway' => [
                'description' => 'Optional override for the payment gateway key.',
                'example' => 'stripe',
            ],
        ];
    }
}
