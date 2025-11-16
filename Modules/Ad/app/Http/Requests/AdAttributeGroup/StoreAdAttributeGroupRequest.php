<?php

namespace Modules\Ad\Http\Requests\AdAttributeGroup;

use Illuminate\Foundation\Http\FormRequest;

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
            'advertisable_type_id' => ['required', 'integer', 'exists:advertisable_types,id'],
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
            'advertisable_type_id' => [
                'description' => 'Identifier of the advertisable type this group belongs to.',
                'example' => 2,
            ],
            'display_order' => [
                'description' => 'Numeric sorting weight for UI rendering.',
                'example' => 1,
            ],
        ];
    }
}
