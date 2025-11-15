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
        // in AddAdImagesRequest::bodyParameters()
        return [
            'images' => [
                'description' => 'Array of image uploads...',
                'type' => 'array',
                'example' => [[
                    // DO NOT provide an example path here
                    'custom_properties' => ['alt' => 'Front view'],
                ]],
            ],
            'images[].file' => [
                'description' => 'Image file that will be stored in the ad gallery.',
                'type' => 'file',
                // no "example" key; Scribe will create a fake image file
            ],
            'images[].custom_properties' => [
                'description' => 'Optional metadata...',
                'type' => 'object',
                'example' => ['caption' => 'Dashboard controls'],
            ],
        ];

    }
}
