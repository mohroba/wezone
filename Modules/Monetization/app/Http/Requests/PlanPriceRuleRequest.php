<?php

namespace Modules\Monetization\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlanPriceRuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin') ?? false;
    }

    public function rules(): array
    {
        return [
            'advertisable_type_id' => ['required', 'integer', 'exists:advertisable_types,id'],
            'ad_category_id' => ['nullable', 'integer', 'exists:ad_categories,id'],
            'override_price' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'discount_type' => ['required', Rule::in(['none', 'percent', 'fixed'])],
            'discount_value' => ['nullable', 'numeric', 'min:0', 'required_unless:discount_type,none'],
            'discount_starts_at' => ['nullable', 'date'],
            'discount_ends_at' => ['nullable', 'date', 'after_or_equal:discount_starts_at'],
            'usage_cap' => ['nullable', 'integer', 'min:1'],
            'is_stackable' => ['boolean'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
