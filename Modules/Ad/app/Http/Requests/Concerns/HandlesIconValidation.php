<?php

namespace Modules\Ad\Http\Requests\Concerns;

trait HandlesIconValidation
{
    /**
     * Build the validation rules shared by icon uploads.
     *
     * @param array<string, mixed> $options
     * @return array<int, string>
     */
    private static function iconRules(array $options = []): array
    {
        $rules = [];

        if (! empty($options['sometimes'])) {
            $rules[] = 'sometimes';
        }

        if (! empty($options['required'])) {
            $rules[] = 'required';
        } elseif ($options['nullable'] ?? true) {
            $rules[] = 'nullable';
        }

        $rules[] = 'file';
        $rules[] = 'mimes:jpeg,jpg,png,bmp,gif,svg,webp,ico';
        $rules[] = 'max:2048';

        return $rules;
    }
}
