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

    public function bodyParameters(): array
    {
        return [
            'ad_id' => [
                'description' => 'Identifier of the ad to promote.',
                'example' => 41,
                'required' => true,
            ],
            'plan_id' => [
                'description' => 'Optional identifier of the plan being purchased.',
                'example' => 5,
            ],
            'plan_slug' => [
                'description' => 'Optional slug alternative to `plan_id`.',
                'example' => 'premium-weekly',
            ],
            'gateway' => [
                'description' => 'Preferred payment gateway key.',
                'example' => 'payping',
            ],
            'pay_with_wallet' => [
                'description' => 'Whether to charge the wallet balance immediately.',
                'example' => false,
            ],
        ];
    }
}
