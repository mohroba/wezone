<?php

namespace Modules\Ad\Http\Requests\AdAttributeValue;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Modules\Ad\Models\AdAttributeDefinition;

class StoreAdAttributeValueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'definition_id' => ['required', 'integer', 'exists:ad_attribute_definitions,id'],
            'ad_id' => ['required', 'integer', 'exists:ads,id'],
            'value_string' => ['nullable', 'string'],
            'value_integer' => ['nullable', 'integer'],
            'value_decimal' => ['nullable', 'numeric'],
            'value_boolean' => ['nullable', 'boolean'],
            'value_date' => ['nullable', 'date'],
            'value_json' => ['nullable', 'array'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'value_boolean' => $this->toBoolean($this->input('value_boolean')),
        ]);
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $definition = $this->definition();

            if ($definition) {
                $this->validateValueMatchesDefinition($validator, $definition);
            }

        });
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'definition_id' => [
                'description' => 'Identifier of the attribute definition being populated.',
                'example' => 4,
            ],
            'ad_id' => [
                'description' => 'Identifier of the ad record this value belongs to.',
                'example' => 15,
            ],
            'value_string' => [
                'description' => 'String value when the definition expects textual data.',
                'example' => 'Automatic',
            ],
            'value_integer' => [
                'description' => 'Integer value when the definition expects whole numbers.',
                'example' => 5,
            ],
            'value_decimal' => [
                'description' => 'Decimal value when the definition expects numeric data.',
                'example' => 1.6,
            ],
            'value_boolean' => [
                'description' => 'Boolean value when the definition expects true or false.',
                'example' => true,
            ],
            'value_date' => [
                'description' => 'Date value when the definition expects a calendar date.',
                'example' => '2024-01-15',
            ],
            'value_json' => [
                'description' => 'Structured data payload for JSON definitions.',
                'example' => ['features' => ['sunroof', 'heated seats']],
            ],
        ];
    }

    private function validateValueMatchesDefinition($validator, AdAttributeDefinition $definition): void
    {
        $valueFields = $this->valueFields();
        $hasValue = collect($valueFields)->contains(fn ($field) => $this->filled($field));

        if (! $hasValue) {
            $validator->errors()->add('value', 'At least one value field must be provided.');

            return;
        }

        $expectedField = $this->valueFieldForType($definition->data_type);

        if ($expectedField && ! $this->filled($expectedField)) {
            $validator->errors()->add($expectedField, 'The provided value does not match the definition type.');

            return;
        }

        if ($expectedField && $definition->validation_rules) {
            $valueValidator = ValidatorFacade::make(
                [$expectedField => $this->input($expectedField)],
                [$expectedField => $definition->validation_rules]
            );

            if ($valueValidator->fails()) {
                foreach ($valueValidator->errors()->all() as $message) {
                    $validator->errors()->add($expectedField, $message);
                }
            }
        }

        if ($expectedField) {
            $this->validateAgainstOptionsSchema($validator, $definition, $expectedField);
        }
    }

    /**
     * @return array<int, string>
     */
    private function valueFields(): array
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

    private function valueFieldForType(string $type): ?string
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

    private function validateAgainstOptionsSchema($validator, AdAttributeDefinition $definition, string $field): void
    {
        $options = $definition->options;

        if (! is_array($options)) {
            return;
        }

        if (isset($options['enum']) && is_array($options['enum']) && $options['enum'] !== []) {
            $value = $this->input($field);

            if ($value !== null && ! in_array($value, $options['enum'], true)) {
                $validator->errors()->add($field, 'The selected value is invalid.');
            }
        }

        $numericValue = $this->input($field);

        if (is_numeric($numericValue)) {
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
                    $validator->errors()->add($field, 'The provided value does not satisfy the configured constraints.');

                    break;
                }
            }
        }
    }

    private function definition(): ?AdAttributeDefinition
    {
        $definitionId = $this->input('definition_id');

        if (! $definitionId) {
            return null;
        }

        return AdAttributeDefinition::find($definitionId);
    }

    private function toBoolean(mixed $value): ?bool
    {
        if ($value === null) {
            return null;
        }

        return filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
    }
}
