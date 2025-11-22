<?php

namespace Modules\Ad\Http\Requests\AdAttributeDefinition;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Modules\Ad\Http\Requests\AdAttributeDefinition\Concerns\ValidatesDefinitionOptions;
use Modules\Ad\Models\AdAttributeDefinition;

class UpdateAdAttributeDefinitionRequest extends FormRequest
{
    use ValidatesDefinitionOptions;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Always safe table name (even during Scribe generation)
        $table = (new AdAttributeDefinition())->getTable();

        // Handle possible route parameter states: model, ID, or null
        $routeParam = $this->route('ad_attribute_definition');
        $definitionId = $routeParam instanceof AdAttributeDefinition
            ? $routeParam->getKey()
            : (is_numeric($routeParam) ? (int) $routeParam : null);

        // Define unique rule safely
        $uniqueRule = Rule::unique($table, 'key')
            ->where(function ($query) use ($routeParam) {
                // If there’s a bound model, use its attribute_group_id as default
                $groupId = $this->input('attribute_group_id', $routeParam?->attribute_group_id ?? null);
                if ($groupId !== null) {
                    $query->where('attribute_group_id', $groupId);
                }
                return $query;
            });

        if ($definitionId !== null) {
            $uniqueRule->ignore($definitionId);
        }

        $dataTypes = ['string', 'integer', 'decimal', 'boolean', 'enum', 'json', 'date'];

        return [
            'attribute_group_id'        => ['sometimes', 'required', 'integer', 'exists:ad_attribute_groups,id'],
            'key'             => ['sometimes', 'required', 'string', 'max:255', $uniqueRule],
            'label'           => ['sometimes', 'required', 'string', 'max:255'],
            'help_text'       => ['nullable', 'string'],
            'data_type'       => ['sometimes', 'required', 'string', Rule::in($dataTypes)],
            'unit'            => ['nullable', 'string', 'max:255'],
            'options'         => ['nullable', 'array'],
            'is_required'     => ['boolean'],
            'is_filterable'   => ['boolean'],
            'is_searchable'   => ['boolean'],
            'validation_rules'=> ['nullable', 'string'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(fn (Validator $afterValidator) => $this->validateOptionsSchema($afterValidator));
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'is_required'   => $this->toBoolean($this->input('is_required')),
            'is_filterable' => $this->toBoolean($this->input('is_filterable')),
            'is_searchable' => $this->toBoolean($this->input('is_searchable')),
        ]);
    }

    /**
     * Scribe v5.x – explicit, typed documentation for generated API docs.
     *
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'attribute_group_id' => [
                'description' => 'Identifier of the attribute group this definition belongs to.',
                'type'        => 'integer',
                'example'     => 2,
            ],
            'key' => [
                'description' => 'Unique machine-friendly key for the attribute.',
                'type'        => 'string',
                'example'     => 'engine_volume',
                'required'    => false,
            ],
            'label' => [
                'description' => 'Human readable label for the attribute.',
                'type'        => 'string',
                'example'     => 'Engine volume',
                'required'    => false,
            ],
            'help_text' => [
                'description' => 'Helper text to guide form inputs.',
                'type'        => 'string',
                'example'     => 'Specify the displacement in liters.',
            ],
            'data_type' => [
                'description' => 'Datatype expected for the attribute value.',
                'type'        => 'string',
                'example'     => 'decimal',
            ],
            'unit' => [
                'description' => 'Unit displayed next to the value.',
                'type'        => 'string',
                'example'     => 'L',
            ],
            'options' => [
                'description' => 'Available options or constraints for the attribute; pass either a constraint object or an array of enum strings.',
                'type'        => 'array<string>',
                'example'     => ['Red', 'Blue', 'White'],
            ],
            'is_required' => [
                'description' => 'Whether the attribute must be provided when creating ads.',
                'type'        => 'boolean',
                'example'     => true,
            ],
            'is_filterable' => [
                'description' => 'Whether the attribute can be used as a filter in listings.',
                'type'        => 'boolean',
                'example'     => true,
            ],
            'is_searchable' => [
                'description' => 'Whether the attribute contributes to search indexes.',
                'type'        => 'boolean',
                'example'     => false,
            ],
            'validation_rules' => [
                'description' => 'Laravel validation rules applied to the attribute value.',
                'type'        => 'string',
                'example'     => 'numeric|min:0.5|max:5',
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
