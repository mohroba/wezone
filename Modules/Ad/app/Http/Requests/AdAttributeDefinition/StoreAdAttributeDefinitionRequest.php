<?php

namespace Modules\Ad\Http\Requests\AdAttributeDefinition;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Ad\Models\AdAttributeDefinition;

class StoreAdAttributeDefinitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $dataTypes = ['string', 'integer', 'decimal', 'boolean', 'enum', 'json'];

        return [
            'group_id' => ['nullable', 'integer', 'exists:ad_attribute_groups,id'],
            'key' => [
                'required',
                'string',
                'max:255',
                Rule::unique((new AdAttributeDefinition())->getTable(), 'key')->where(function ($query) {
                    return $query->where('group_id', $this->input('group_id'));
                }),
            ],
            'label' => ['required', 'string', 'max:255'],
            'help_text' => ['nullable', 'string'],
            'data_type' => ['required', 'string', Rule::in($dataTypes)],
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
