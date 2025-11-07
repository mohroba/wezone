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
}
