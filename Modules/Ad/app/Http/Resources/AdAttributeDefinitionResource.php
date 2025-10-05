<?php

namespace Modules\Ad\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \Modules\Ad\Models\AdAttributeDefinition */
class AdAttributeDefinitionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'group_id' => $this->group_id,
            'key' => $this->key,
            'label' => $this->label,
            'help_text' => $this->help_text,
            'data_type' => $this->data_type,
            'unit' => $this->unit,
            'options' => $this->options,
            'is_required' => $this->is_required,
            'is_filterable' => $this->is_filterable,
            'is_searchable' => $this->is_searchable,
            'validation_rules' => $this->validation_rules,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
