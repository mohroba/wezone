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
            'advertisable_type_id' => ['required', 'integer', 'exists:advertisable_types,id'],
            'parent_id' => ['nullable', 'integer', 'exists:ad_categories,id'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique((new AdCategory())->getTable(), 'slug')],
            'name' => ['required', 'string', 'max:255'],
            'name_localized' => ['nullable', 'array'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer'],
            'icon' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->toBoolean($this->input('is_active')),
        ]);
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $parentId = $this->input('parent_id');

            if (! $parentId) {
                return;
            }

            $parent = AdCategory::query()->find($parentId);

            if ($parent && (int) $parent->advertisable_type_id !== (int) $this->input('advertisable_type_id')) {
                $validator->errors()->add('parent_id', 'Parent category must belong to the same advertisable type.');
            }
        });
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'parent_id' => [
                'description' => 'Identifier of the parent category.',
                'example' => 1,
            ],
            'advertisable_type_id' => [
                'description' => 'Identifier of the advertisable type this category belongs to.',
                'example' => 2,
            ],
            'slug' => [
                'description' => 'Unique slug for the category.',
                'example' => 'vehicles',
            ],
            'name' => [
                'description' => 'Display name of the category.',
                'example' => 'Vehicles',
            ],
            'name_localized' => [
                'description' => 'Localized translations for the category name.',
                'example' => ['fa' => 'وسایل نقلیه'],
            ],
            'is_active' => [
                'description' => 'Toggle to activate or deactivate the category.',
                'example' => true,
            ],
            'sort_order' => [
                'description' => 'Custom ordering index.',
                'example' => 5,
            ],
            'icon' => [
                'description' => 'Icon image representing the category (JPEG, PNG, BMP, GIF, SVG, or WebP).',
                'type' => 'file',
            ],
        ];
    }

    private function toBoolean(mixed $value): ?bool
    {
        if ($value === null) {
            return null;
        }

        return filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
    }
}
