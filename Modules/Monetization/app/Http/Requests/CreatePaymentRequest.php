<?php

namespace Modules\Monetization\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'purchase_id' => ['required', 'integer', 'exists:ad_plan_purchases,id'],
            'gateway' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'purchase_id' => [
                'description' => 'Identifier of the ad plan purchase to pay for.',
                'example' => 10,
                'required' => true,
            ],
            'gateway' => [
                'description' => 'Optional override for the payment gateway key.',
                'example' => 'zarinpal',
            ],
        ];
    }
}
