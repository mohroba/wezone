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

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'order' => [
                'description' => 'Array of image identifiers and their new ordering priorities.',
                'example' => [
                    [
                        'media_id' => 10,
                        'display_order' => 0,
                    ],
                    [
                        'media_id' => 11,
                        'display_order' => 1,
                    ],
                ],
            ],
            'order.*.media_id' => [
                'description' => 'Identifier of the existing media record.',
                'example' => 10,
            ],
            'order.*.display_order' => [
                'description' => 'New 0-based display order for the provided media.',
                'example' => 1,
            ],
        ];
    }
}
