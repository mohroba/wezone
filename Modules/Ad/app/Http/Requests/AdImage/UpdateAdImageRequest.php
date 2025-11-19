<?php

namespace Modules\Ad\Http\Requests\AdImage;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateAdImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'alt' => ['nullable', 'string', 'max:150'],
            'caption' => ['nullable', 'string', 'max:255'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'alt' => [
                'description' => 'Updated alternative text for the image.',
                'example' => 'Front view of the product from a low angle.',
            ],
            'caption' => [
                'description' => 'Optional caption to describe the image for buyers.',
                'example' => 'Living room with natural light in the afternoon.',
            ],
            'display_order' => [
                'description' => 'Zero-based order that determines how the image is sorted.',
                'example' => 0,
            ],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (! $this->hasAny(['alt', 'caption', 'display_order'])) {
                $validator->errors()->add('payload', 'At least one metadata field must be provided.');
            }
        });
    }
}
