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
            'advertisable_type_id' => ['required', 'integer', 'exists:advertisable_types,id'],
            'ad_category_id' => ['nullable', 'integer', 'exists:ad_categories,id'],
            'discount_code' => ['nullable', 'string'],
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
            'advertisable_type_id' => [
                'description' => 'Identifier for the advertisable type to derive contextual pricing.',
                'example' => 2,
                'required' => true,
            ],
            'ad_category_id' => [
                'description' => 'Optional category identifier used to target price rules.',
                'example' => 14,
            ],
            'discount_code' => [
                'description' => 'Optional discount code used when a pricing rule requires it.',
                'example' => 'SPRING24',
            ],
            'pay_with_wallet' => [
                'description' => 'Whether to charge the wallet balance immediately.',
                'example' => false,
            ],
        ];
    }
}
