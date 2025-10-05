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
}
