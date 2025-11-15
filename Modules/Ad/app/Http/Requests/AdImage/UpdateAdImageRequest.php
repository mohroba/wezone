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

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (! $this->hasAny(['alt', 'caption', 'display_order'])) {
                $validator->errors()->add('payload', 'At least one metadata field must be provided.');
            }
        });
    }
}
