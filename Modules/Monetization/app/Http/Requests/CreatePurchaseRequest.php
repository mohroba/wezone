<?php

namespace Modules\Monetization\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ad_id' => ['required', 'integer'],
            'plan_id' => ['nullable', 'integer'],
            'plan_slug' => ['nullable', 'string'],
            'gateway' => ['nullable', 'string'],
            'pay_with_wallet' => ['sometimes', 'boolean'],
        ];
    }
}
