<?php

namespace Modules\Ad\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \Modules\Ad\Models\AdAttributeValue */
class AdAttributeValueResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'definition_id' => $this->definition_id,
            'advertisable_type' => $this->advertisable_type,
            'advertisable_id' => $this->advertisable_id,
            'value_string' => $this->value_string,
            'value_integer' => $this->value_integer,
            'value_decimal' => $this->value_decimal,
            'value_boolean' => $this->value_boolean,
            'value_json' => $this->value_json,
            'normalized_value' => $this->normalized_value,
            'definition' => AdAttributeDefinitionResource::make($this->whenLoaded('definition')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
