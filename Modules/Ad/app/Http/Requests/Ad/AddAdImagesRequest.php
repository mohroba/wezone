<?php

namespace Modules\Ad\Http\Requests\Ad;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;
use Modules\Ad\Http\Requests\Concerns\NormalizesImageUploads;

class AddAdImagesRequest extends FormRequest
{
    use NormalizesImageUploads;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['required', 'array'],
            'images.*.file' => ['required', File::image()->max(5 * 1024)],
            'images.*.custom_properties' => ['nullable', 'array'],
            'images.*.custom_properties.alt' => ['nullable', 'string', 'max:255'],
            'images.*.custom_properties.caption' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->normalizeImagesPayload();
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'images' => [
                'description' => 'Array of image uploads to append to the gallery. The request must be sent as multipart/form-data using field names such as images[0][file].',
                'type' => 'array',
                'example' => [
                    [
                        'file' => 'binary image upload',
                        'custom_properties' => ['alt' => 'Front view'],
                    ],
                ],
            ],
            'images[].file' => [
                'description' => 'Image file that will be stored in the ad gallery.',
                'type' => 'file',
                'example' => 'photo.jpg',
            ],
            'images[].custom_properties' => [
                'description' => 'Optional metadata saved with the image (for example alt text or caption).',
                'type' => 'object',
                'example' => ['caption' => 'Dashboard controls'],
            ],
        ];
    }
}
