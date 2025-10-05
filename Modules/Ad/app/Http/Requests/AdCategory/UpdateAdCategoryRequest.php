<?php

namespace Modules\Ad\Http\Requests\AdCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Ad\Models\AdCategory;
use Modules\Ad\Models\AdCategoryClosure;

class UpdateAdCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var AdCategory $category */
        $category = $this->route('ad_category');

        return [
            'parent_id' => ['nullable', 'integer', 'exists:ad_categories,id'],
            'slug' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique($category->getTable(), 'slug')->ignore($category->id),
            ],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
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

    public function withValidator($validator): void
    {
        /** @var AdCategory $category */
        $category = $this->route('ad_category');

        $validator->after(function ($validator) use ($category): void {
            $parentId = $this->input('parent_id');

            if (! $parentId) {
                return;
            }

            if ((int) $parentId === (int) $category->id) {
                $validator->errors()->add('parent_id', 'A category cannot be its own parent.');

                return;
            }

            $isDescendant = AdCategoryClosure::query()
                ->where('ancestor_id', $category->id)
                ->where('descendant_id', $parentId)
                ->exists();

            if ($isDescendant) {
                $validator->errors()->add('parent_id', 'A category cannot be assigned to one of its descendants.');
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
            'filters_schema' => [
                'description' => 'JSON schema describing available filters.',
                'example' => ['color' => ['type' => 'enum', 'options' => ['red', 'blue']]],
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
