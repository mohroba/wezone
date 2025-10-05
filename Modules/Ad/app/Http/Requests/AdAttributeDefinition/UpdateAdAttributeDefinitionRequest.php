<?php

namespace Modules\Ad\Http\Requests\AdAttributeDefinition;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Ad\Models\AdAttributeDefinition;

class UpdateAdAttributeDefinitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var AdAttributeDefinition $definition */
        $definition = $this->route('ad_attribute_definition');
        $dataTypes = ['string', 'integer', 'decimal', 'boolean', 'enum', 'json'];

        return [
            'group_id' => ['nullable', 'integer', 'exists:ad_attribute_groups,id'],
            'key' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique($definition->getTable(), 'key')
                    ->where(function ($query) use ($definition) {
                        $groupId = $this->input('group_id', $definition->group_id);

                        return $query->where('group_id', $groupId);
                    })
                    ->ignore($definition->id),
            ],
            'label' => ['sometimes', 'required', 'string', 'max:255'],
            'help_text' => ['nullable', 'string'],
            'data_type' => ['sometimes', 'required', 'string', Rule::in($dataTypes)],
            'unit' => ['nullable', 'string', 'max:255'],
            'options' => ['nullable', 'array'],
            'is_required' => ['boolean'],
            'is_filterable' => ['boolean'],
            'is_searchable' => ['boolean'],
            'validation_rules' => ['nullable', 'string'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'is_required' => $this->toBoolean($this->input('is_required')),
            'is_filterable' => $this->toBoolean($this->input('is_filterable')),
            'is_searchable' => $this->toBoolean($this->input('is_searchable')),
        ]);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'group_id' => [
                'description' => 'Identifier of the attribute group this definition belongs to.',
                'example' => 2,
            ],
            'key' => [
                'description' => 'Unique machine-friendly key for the attribute.',
                'example' => 'engine_volume',
            ],
            'label' => [
                'description' => 'Human readable label for the attribute.',
                'example' => 'Engine volume',
            ],
            'help_text' => [
                'description' => 'Helper text to guide form inputs.',
                'example' => 'Specify the displacement in liters.',
            ],
            'data_type' => [
                'description' => 'Datatype expected for the attribute value.',
                'example' => 'decimal',
            ],
            'unit' => [
                'description' => 'Unit displayed next to the value.',
                'example' => 'L',
            ],
            'options' => [
                'description' => 'Available options or constraints for the attribute.',
                'example' => ['min' => 1.0, 'max' => 5.0],
            ],
            'is_required' => [
                'description' => 'Whether the attribute must be provided when creating ads.',
                'example' => true,
            ],
            'is_filterable' => [
                'description' => 'Whether the attribute can be used as a filter in listings.',
                'example' => true,
            ],
            'is_searchable' => [
                'description' => 'Whether the attribute contributes to search indexes.',
                'example' => false,
            ],
            'validation_rules' => [
                'description' => 'Laravel validation rules applied to the attribute value.',
                'example' => 'numeric|min:0.5|max:5',
            ],
        ];
    }

    private function toBoolean(mixed $value): ?bool
    {
        if ($value === null) {
            return null;
        }

        return filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
    }
}
