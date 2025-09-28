<?php

namespace Modules\Settings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('api') !== null;
    }

    public function rules(): array
    {
        $rules = [];

        foreach (config('settings.keys', []) as $key => $meta) {
            $rules[$key] = ['nullable', 'string'];
        }

        return $rules;
    }

    public function settings(): array
    {
        return $this->validated();
    }
}
