<?php

namespace Modules\Ad\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Ad\Models\AdvertisableType;

/** @mixin AdvertisableType */
class AdvertisableTypeModelResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var AdvertisableType $type */
        $type = $this->resource;

        return [
            'id' => $type->id,
            'key' => $type->key,
            'label' => $type->label,
            'description' => $type->description,
            'model_class' => $type->model_class,
            'icon_url' => $type->getFirstMediaUrl(AdvertisableType::COLLECTION_ICON),
            'created_at' => $type->created_at?->toISOString(),
            'updated_at' => $type->updated_at?->toISOString(),
        ];
    }
}
