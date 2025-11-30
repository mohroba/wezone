<?php

namespace Modules\Ad\Http\Requests\AdAttributeDefinition\Concerns;

use Illuminate\Validation\Validator;
use Modules\Ad\Models\AdAttributeDefinition;

trait ValidatesDefinitionOptions
{
    protected function validateOptionsSchema(Validator $validator): void
    {
        $options = $this->input('options');

        if ($options === null) {
            return;
        }

        if (! is_array($options)) {
            $validator->errors()->add('options', 'The options field must be a JSON array or object.');

            return;
        }

        $isList = array_is_list($options);
        if ($isList) {
            foreach ($options as $index => $enumValue) {
                if (! is_string($enumValue)) {
                    $validator->errors()->add("options.$index", 'Each option must be a string.');
                    return;
                }
            }
        }

        if (isset($options['enum']) && (! is_array($options['enum']) || $options['enum'] === [])) {
            $validator->errors()->add('options.enum', 'Enum definitions must provide a non-empty array of values.');
        } elseif (isset($options['enum'])) {
            foreach ($options['enum'] as $index => $enumValue) {
                if (! is_scalar($enumValue) && $enumValue !== null) {
                    $validator->errors()->add("options.enum.$index", 'Each enum value must be a scalar.');
                    break;
                }
            }
        }

        foreach (['minimum', 'maximum', 'exclusiveMinimum', 'exclusiveMaximum', 'multipleOf'] as $keyword) {
            if (isset($options[$keyword]) && ! is_numeric($options[$keyword])) {
                $validator->errors()->add("options.$keyword", 'The ' . $keyword . ' constraint must be numeric.');
            }
        }

        foreach (['minLength', 'maxLength'] as $keyword) {
            if (
                isset($options[$keyword]) &&
                (! is_int($options[$keyword]) || $options[$keyword] < 0)
            ) {
                $validator->errors()->add("options.$keyword", 'The ' . $keyword . ' constraint must be a positive integer.');
            }
        }

        if ($this->resolveDefinitionDataType() === 'enum') {
            if ($isList) {
                if ($options === []) {
                    $validator->errors()->add('options.enum', 'Enum definitions must include an enum array.');
                }
            } elseif ($this->enumValuesFromDefinitionOptions($options) === null) {
                $validator->errors()->add('options.enum', 'Enum definitions must include an enum array.');
            }
        }
    }

    protected function enumValuesFromDefinitionOptions(array $options): ?array
    {
        if (array_is_list($options)) {
            return $options;
        }

        if (isset($options['enum']) && is_array($options['enum'])) {
            return $options['enum'];
        }

        return null;
    }

    protected function resolveDefinitionDataType(): ?string
    {
        if ($this->filled('data_type')) {
            return $this->input('data_type');
        }

        $routeParam = $this->route('ad_attribute_definition');

        return $routeParam instanceof AdAttributeDefinition ? $routeParam->data_type : null;
    }
}
