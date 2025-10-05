<?php

namespace Modules\Ad\Http\Requests\AdAttributeGroup;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Ad\Support\AdvertisableType;

class StoreAdAttributeGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'advertisable_type' => ['nullable', 'string', Rule::in(AdvertisableType::allowed())],
            'category_id' => ['nullable', 'integer', 'exists:ad_categories,id'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'Display name of the attribute group.',
                'example' => 'Engine specifications',
            ],
            'advertisable_type' => [
                'description' => 'Advertisable model class this group applies to.',
                'example' => 'Modules\\Ad\\Models\\AdCar',
            ],
            'category_id' => [
                'description' => 'Optional category scope for the group.',
                'example' => 7,
            ],
            'display_order' => [
                'description' => 'Numeric sorting weight for UI rendering.',
                'example' => 1,
            ],
        ];
    }
}
