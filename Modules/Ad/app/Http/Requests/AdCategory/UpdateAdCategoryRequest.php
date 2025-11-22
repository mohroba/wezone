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
        // Always available (even during scribe:generate)
        $table = (new AdCategory())->getTable();

        // Route param may be a model, a numeric ID, or null (eg. docs generation)
        $routeParam = $this->route('ad_category');
        $categoryId = $routeParam instanceof AdCategory
            ? $routeParam->getKey()
            : (is_numeric($routeParam) ? (int) $routeParam : null);

        // Unique rule that safely ignores current row only when we actually have an ID
        $slugUnique = Rule::unique($table, 'slug');
        if ($categoryId !== null) {
            $slugUnique->ignore($categoryId);
        }

        return [
            'parent_id'        => ['nullable', 'integer', 'exists:ad_categories,id'],
            'slug'             => ['sometimes', 'required', 'string', 'max:255', 'alpha_dash', $slugUnique],
            'name'             => ['sometimes', 'required', 'string', 'max:255'],
            'name_localized'   => ['nullable', 'array'],
            'is_active'        => ['boolean'],
            'sort_order'       => ['nullable', 'integer'],
            'advertisable_type_id' => ['sometimes', 'required', 'integer', 'exists:advertisable_types,id'],
            'icon' => ['sometimes', 'nullable', 'image', 'max:2048'],
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
        $routeParam = $this->route('ad_category');
        $category = $routeParam instanceof AdCategory ? $routeParam : null;

        $validator->after(function ($validator) use ($category): void {
            // Skip extra checks during docs generation (no bound model)
            if (! $category) {
                return;
            }

            $parentId = $this->input('parent_id');

            if ($parentId) {
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
                    return;
                }

                $parent = AdCategory::query()->find($parentId);
                $targetTypeId = (int) ($this->input('advertisable_type_id') ?? $category->advertisable_type_id);

                if ($parent && (int) $parent->advertisable_type_id !== $targetTypeId) {
                    $validator->errors()->add('parent_id', 'Parent category must belong to the same advertisable type.');
                }
            }

            if ($this->has('advertisable_type_id')) {
                $typeId = (int) $this->input('advertisable_type_id');

                $hasMismatchedChildren = $category->children()
                    ->where('advertisable_type_id', '!=', $typeId)
                    ->exists();

                if ($hasMismatchedChildren) {
                    $validator->errors()->add('advertisable_type_id', 'Cannot change type while children exist with a different advertisable type.');
                }
            }
        });
    }

    /**
     * Scribe v5.x: describe/typed body fields so the docs UI renders correctly.
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'parent_id' => [
                'description' => 'Identifier of the parent category.',
                'type'        => 'integer',
                'example'     => 1,
            ],
            'slug' => [
                'description' => 'Unique slug for the category.',
                'type'        => 'string',
                'example'     => 'vehicles',
                'required'    => false, // because rules() use sometimes|required
            ],
            'name' => [
                'description' => 'Display name of the category.',
                'type'        => 'string',
                'example'     => 'Vehicles',
                'required'    => false,
            ],
            'name_localized' => [
                'description' => 'Localized translations for the category name.',
                'type'        => 'object',
                'example'     => ['fa' => 'وسایل نقلیه'],
            ],
            'is_active' => [
                'description' => 'Toggle to activate or deactivate the category.',
                'type'        => 'boolean',
                'example'     => true,
            ],
            'sort_order' => [
                'description' => 'Custom ordering index.',
                'type'        => 'integer',
                'example'     => 5,
            ],
            'advertisable_type_id' => [
                'description' => 'Identifier of the advertisable type this category belongs to.',
                'type'        => 'integer',
                'example'     => 2,
            ],
            'icon' => [
                'description' => 'Icon image representing the category (JPEG, PNG, BMP, GIF, SVG, or WebP).',
                'type'        => 'file',
                'required'    => false,
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
