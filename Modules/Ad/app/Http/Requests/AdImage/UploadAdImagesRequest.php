<?php

namespace Modules\Ad\Http\Requests\AdImage;

use Illuminate\Foundation\Http\FormRequest;

class UploadAdImagesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'images' => ['required', 'array', 'min:1'],
            'images.*.file' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'images.*.alt' => ['nullable', 'string', 'max:150'],
            'images.*.caption' => ['nullable', 'string', 'max:255'],
            'images.*.display_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'images' => [
                'description' => 'List of image assets and optional metadata to upload.',
                'example' => [
                    [
                        'file' => 'front_view.webp',
                        'alt' => 'Front view of the item',
                        'caption' => 'Front exterior shot',
                        'display_order' => 0,
                    ],
                ],
            ],
            'images.*.file' => [
                'description' => 'File payload for the image (JPEG, PNG, or WebP).',
                'type' => 'file',
            ],
            'images.*.alt' => [
                'description' => 'Accessible description of the image.',
                'example' => 'Front facade showing the main entrance.',
            ],
            'images.*.caption' => [
                'description' => 'Optional caption to annotate the image.',
                'example' => 'Main entrance with new landscaping.',
            ],
            'images.*.display_order' => [
                'description' => 'Zero-based priority used to order images.',
                'example' => 1,
            ],
        ];
    }
}
