<?php

namespace Modules\Monetization\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateDiscountCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'plan_id' => ['required_without:plan_slug', 'nullable', 'integer'],
            'plan_slug' => ['required_without:plan_id', 'nullable', 'string'],
            'advertisable_type_id' => ['required', 'integer', 'exists:advertisable_types,id'],
            'ad_category_id' => ['nullable', 'integer', 'exists:ad_categories,id'],
            'discount_code' => ['required', 'string'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'plan_id' => [
                'description' => 'Identifier of the plan to validate against.',
                'example' => 3,
            ],
            'plan_slug' => [
                'description' => 'Slug of the plan as an alternative to `plan_id`.',
                'example' => 'premium-weekly',
            ],
            'advertisable_type_id' => [
                'description' => 'Contextual advertisable type identifier.',
                'example' => 2,
            ],
            'ad_category_id' => [
                'description' => 'Optional category used to refine price rules.',
                'example' => 14,
            ],
            'discount_code' => [
                'description' => 'Discount code to validate for the selected plan and context.',
                'example' => 'LOYALTY20',
                'required' => true,
            ],
        ];
    }
}
