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

    private function toBoolean(mixed $value): ?bool
    {
        if ($value === null) {
            return null;
        }

        return filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
    }
}
