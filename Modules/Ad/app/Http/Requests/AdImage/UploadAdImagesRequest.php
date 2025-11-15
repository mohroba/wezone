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
}
