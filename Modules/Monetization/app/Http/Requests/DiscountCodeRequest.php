<?php

namespace Modules\Monetization\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Monetization\Domain\Entities\DiscountCode;

class DiscountCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin') ?? false;
    }

    public function rules(): array
    {
        /** @var DiscountCode|null $discountCode */
        $discountCode = $this->route('discountCode');

        return [
            'code' => ['required', 'string', 'max:64', Rule::unique('discount_codes', 'code')->ignore($discountCode?->id)],
            'description' => ['nullable', 'string', 'max:255'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'usage_cap' => ['nullable', 'integer', 'min:1'],
            'per_user_cap' => ['nullable', 'integer', 'min:1'],
            'is_stackable' => ['boolean'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
