<?php

namespace Modules\Ad\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Ad\Http\Resources\AdAttributeDefinitionResource;

/** @mixin \Modules\Ad\Models\AdAttributeGroup */
class AdAttributeGroupResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'advertisable_type_id' => $this->advertisable_type_id,
            'display_order' => $this->display_order,
            'definitions' => AdAttributeDefinitionResource::collection(
                $this->whenLoaded('definitions')
            ),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
