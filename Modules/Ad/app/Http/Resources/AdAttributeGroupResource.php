<?php

namespace Modules\Ad\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \Modules\Ad\Models\AdAttributeGroup */
class AdAttributeGroupResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'advertisable_type_id' => $this->advertisable_type_id,
            'advertisable_type' => $this->whenLoaded('advertisableType', fn () => $this->advertisableType?->model_class),
            'display_order' => $this->display_order,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
