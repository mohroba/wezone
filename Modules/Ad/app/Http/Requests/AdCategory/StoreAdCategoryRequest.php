<?php

namespace Modules\Ad\Http\Requests\AdCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Ad\Models\AdCategory;

class StoreAdCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parent_id' => ['nullable', 'integer', 'exists:ad_categories,id'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique((new AdCategory())->getTable(), 'slug')],
            'name' => ['required', 'string', 'max:255'],
            'name_localized' => ['nullable', 'array'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer'],
            'filters_schema' => ['nullable', 'array'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->toBoolean($this->input('is_active')),
        ]);
    }

    private function toBoolean(mixed $value): ?bool
    {
        if ($value === null) {
            return null;
        }

        return filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
    }
}
