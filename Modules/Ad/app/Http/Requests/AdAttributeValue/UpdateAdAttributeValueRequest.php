<?php

namespace Modules\Ad\Http\Requests\AdAttributeValue;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Ad\Models\AdAttributeDefinition;
use Modules\Ad\Models\AdAttributeValue;

class UpdateAdAttributeValueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'definition_id' => ['sometimes', 'required', 'integer', 'exists:ad_attribute_definitions,id'],
            'ad_id' => ['sometimes', 'required', 'integer', 'exists:ads,id'],
            'value_string' => ['nullable', 'string'],
            'value_integer' => ['nullable', 'integer'],
            'value_decimal' => ['nullable', 'numeric'],
            'value_boolean' => ['nullable', 'boolean'],
            'value_date' => ['nullable', 'date'],
            'value_json' => ['nullable', 'array'],
            'normalized_value' => ['nullable', 'string', 'max:255'],
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
        /** @var AdAttributeValue $value */
        $value = $this->route('ad_attribute_value');

        $validator->after(function ($validator) use ($value): void {
            $definition = $this->definition() ?? $value->definition;

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
            'normalized_value' => [
                'description' => 'Precomputed normalized representation used for search.',
                'example' => '1.6',
            ],
        ];
    }

    private function validateValueMatchesDefinition($validator, AdAttributeDefinition $definition): void
    {
        $valueFields = [
            'value_string',
            'value_integer',
            'value_decimal',
            'value_boolean',
            'value_date',
            'value_json',
        ];

        $hasValue = false;

        foreach ($valueFields as $field) {
            if ($this->filled($field)) {
                $hasValue = true;
                break;
            }
        }

        if (! $hasValue) {
            $validator->errors()->add('value', 'At least one value field must be provided.');

            return;
        }

            $expectedField = match ($definition->data_type) {
                'string' => 'value_string',
                'integer' => 'value_integer',
                'decimal' => 'value_decimal',
                'boolean' => 'value_boolean',
                'date' => 'value_date',
                'enum' => 'value_string',
                'json' => 'value_json',
                default => null,
        };

        if ($expectedField && ! $this->filled($expectedField)) {
            $validator->errors()->add($expectedField, 'The provided value does not match the definition type.');
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
