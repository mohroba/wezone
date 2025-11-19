<?php

namespace Modules\Ad\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Ad\Advertisable\DTO\AdvertisableTypeMetadata;
use Modules\Ad\Http\Resources\AdAttributeGroupResource;

/** @mixin AdvertisableTypeMetadata */
class AdvertisableTypeResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var AdvertisableTypeMetadata $metadata */
        $metadata = $this->resource;
        $definition = $metadata->definition;

        return [
            'id' => $metadata->typeModel->id,
            'key' => $definition->key(),
            'label' => $definition->label(),
            'description' => $definition->description(),
            'model_class' => $definition->modelClass(),
            'icon_url' => $metadata->typeModel->getFirstMediaUrl($metadata->typeModel::COLLECTION_ICON),
            'attribute_groups' => $metadata->attributeGroups
                ->map(function ($group) use ($request) {
                    $group->loadMissing('definitions');

                    return (new AdAttributeGroupResource($group))->toArray($request);
                })
                ->values()
                ->all(),
        ];
    }
}
