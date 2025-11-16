<?php

namespace Modules\Ad\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \Modules\Ad\Models\AdCategory */
class AdCategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'advertisable_type_id' => $this->advertisable_type_id,
            'slug' => $this->slug,
            'name' => $this->name,
            'name_localized' => $this->name_localized,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
