<?php

namespace Modules\Ad\Http\Requests\Concerns;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Ad\Models\AdAttributeDefinition;

trait ValidatesAttributeValueData
{
    /**
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @param array<int, array<string, mixed>> $attributeValues
     */
    protected function validateAttributeValuesAgainstType($validator, array $attributeValues, int $advertisableTypeId): void
    {
        $definitionIds = collect($attributeValues)
            ->pluck('definition_id')
            ->filter()
            ->unique()
            ->values();

        if ($definitionIds->isEmpty()) {
            $validator->errors()->add('attribute_values', 'At least one attribute value must be provided.');

            return;
        }

        $definitions = AdAttributeDefinition::query()
            ->with('attributeGroup')
            ->whereIn('id', $definitionIds)
            ->get()
            ->keyBy('id');

        foreach ($attributeValues as $index => $value) {
            $path = sprintf('attribute_values.%s', $index);
            $definitionId = Arr::get($value, 'definition_id');
            $definition = $definitionId ? $definitions->get($definitionId) : null;

            if (! $definition) {
                $validator->errors()->add("$path.definition_id", 'The selected attribute definition is invalid.');
                continue;
            }

            $groupTypeId = (int) optional($definition->attributeGroup)->advertisable_type_id;

            if ($groupTypeId !== $advertisableTypeId) {
                $validator->errors()->add("$path.definition_id", 'The attribute definition does not belong to the selected advertisable type.');
                continue;
            }

            if (! $this->attributeValueHasAnyValue($value)) {
                $validator->errors()->add($path, 'At least one value field must be provided.');
                continue;
            }

            $expectedField = $this->valueFieldForType($definition->data_type);

            if ($expectedField && ! $this->attributeValueFieldFilled($value, $expectedField)) {
                $validator->errors()->add("$path.$expectedField", 'The provided value does not match the definition type.');
                continue;
            }

            if ($expectedField && $definition->validation_rules) {
                $valueValidator = Validator::make(
                    [$expectedField => Arr::get($value, $expectedField)],
                    [$expectedField => $definition->validation_rules]
                );

                if ($valueValidator->fails()) {
                    foreach ($valueValidator->errors()->all() as $message) {
                        $validator->errors()->add("$path.$expectedField", $message);
                    }
                }
            }

            if ($expectedField) {
                $this->validateAttributeOptions($validator, $definition, $value, $expectedField, $path);
            }
        }
    }

    protected function attributeValueHasAnyValue(array $value): bool
    {
        foreach ($this->attributeValueFields() as $field) {
            if ($this->attributeValueFieldFilled($value, $field)) {
                return true;
            }
        }

        return false;
    }

    protected function attributeValueFieldFilled(array $value, string $field): bool
    {
        if (! array_key_exists($field, $value)) {
            return false;
        }

        $fieldValue = $value[$field];

        if ($field === 'value_boolean') {
            return $fieldValue !== null;
        }

        if ($field === 'value_json') {
            return $fieldValue !== null;
        }

        if (in_array($field, ['value_integer', 'value_decimal'], true)) {
            return $fieldValue !== null && $fieldValue !== '';
        }

        if (in_array($field, ['value_string', 'value_date'], true)) {
            return $fieldValue !== null && $fieldValue !== '';
        }

        return false;
    }

    /**
     * @return array<int, string>
     */
    protected function attributeValueFields(): array
    {
        return [
            'value_string',
            'value_integer',
            'value_decimal',
            'value_boolean',
            'value_date',
            'value_json',
        ];
    }

    protected function valueFieldForType(string $type): ?string
    {
        return match ($type) {
            'string', 'enum' => 'value_string',
            'integer' => 'value_integer',
            'decimal' => 'value_decimal',
            'boolean' => 'value_boolean',
            'date' => 'value_date',
            'json' => 'value_json',
            default => null,
        };
    }

    protected function validateAttributeOptions($validator, AdAttributeDefinition $definition, array $value, string $field, string $path): void
    {
        $options = $definition->options;

        if (! is_array($options)) {
            return;
        }

        if (isset($options['enum']) && is_array($options['enum']) && $options['enum'] !== []) {
            $fieldValue = Arr::get($value, $field);

            if ($fieldValue !== null && ! in_array($fieldValue, $options['enum'], true)) {
                $validator->errors()->add("$path.$field", 'The selected value is invalid.');
                return;
            }
        }

        $numericValue = Arr::get($value, $field);

        if (! is_numeric($numericValue)) {
            return;
        }

        foreach (['minimum' => '>=', 'exclusiveMinimum' => '>', 'maximum' => '<=', 'exclusiveMaximum' => '<'] as $keyword => $operator) {
            if (! isset($options[$keyword]) || ! is_numeric($options[$keyword])) {
                continue;
            }

            $passes = match ($operator) {
                '>=' => $numericValue >= $options[$keyword],
                '>' => $numericValue > $options[$keyword],
                '<=' => $numericValue <= $options[$keyword],
                '<' => $numericValue < $options[$keyword],
            };

            if (! $passes) {
                $validator->errors()->add("$path.$field", 'The provided value does not satisfy the configured constraints.');
                break;
            }
        }
    }
}
