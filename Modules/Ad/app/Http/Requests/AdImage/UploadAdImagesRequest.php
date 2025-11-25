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
            'file' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp,gif',
                'mimetypes:image/jpeg,image/png,image/webp,image/gif',
                'max:5120',
            ],
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
            'file' => [
                'description' => 'Image file to upload (JPEG, PNG, WebP, or GIF; max 5 MB).',
                'type' => 'file',
            ],
            'alt' => [
                'description' => 'Accessible description of the image.',
                'example' => 'Front facade showing the main entrance.',
            ],
            'caption' => [
                'description' => 'Optional caption to annotate the image.',
                'example' => 'Main entrance with new landscaping.',
            ],
            'display_order' => [
                'description' => 'Zero-based priority used to order images.',
                'example' => 1,
            ],
        ];
    }
}
