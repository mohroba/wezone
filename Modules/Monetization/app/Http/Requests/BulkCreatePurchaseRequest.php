<?php

namespace Modules\Monetization\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkCreatePurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ad_id' => ['required', 'integer'],
            'advertisable_type_id' => ['required', 'integer', 'exists:advertisable_types,id'],
            'ad_category_id' => ['nullable', 'integer', 'exists:ad_categories,id'],
            'plans' => ['required', 'array', 'min:1'],
            'plans.*.plan_id' => ['nullable', 'integer', 'required_without:plans.*.plan_slug'],
            'plans.*.plan_slug' => ['nullable', 'string', 'required_without:plans.*.plan_id'],
            'plans.*.gateway' => ['nullable', 'string'],
            'plans.*.discount_code' => ['nullable', 'string'],
            'plans.*.pay_with_wallet' => ['sometimes', 'boolean'],
            'plans.*.idempotency_key' => ['nullable', 'string'],
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
            'advertisable_type_id' => [
                'description' => 'Identifier for the advertisable type to derive contextual pricing.',
                'example' => 2,
                'required' => true,
            ],
            'ad_category_id' => [
                'description' => 'Optional category identifier used to target price rules.',
                'example' => 14,
            ],
            'plans' => [
                'description' => 'Collection of plans to purchase for the ad.',
                'example' => [
                    [
                        'plan_id' => 5,
                        'gateway' => 'payping',
                        'discount_code' => 'SPRING24',
                        'pay_with_wallet' => true,
                    ],
                    [
                        'plan_slug' => 'highlight-weekly',
                        'gateway' => 'stripe',
                        'pay_with_wallet' => false,
                    ],
                ],
            ],
        ];
    }
}
