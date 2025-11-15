<?php

namespace Modules\Ad\Http\Requests\AdImage;

use Illuminate\Foundation\Http\FormRequest;

class ReorderAdImagesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order' => ['required', 'array'],
            'order.*.media_id' => ['required', 'integer', 'exists:media,id'],
            'order.*.display_order' => ['required', 'integer', 'min:0'],
        ];
    }
}
