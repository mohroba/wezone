<?php

namespace Modules\Ad\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Ad\Advertisable\DTO\AdvertisableTypeMetadata;
use Modules\Ad\Http\Resources\AdAttributeDefinitionResource;

/** @mixin AdvertisableTypeMetadata */
class AdvertisableTypeResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var AdvertisableTypeMetadata $metadata */
        $metadata = $this->resource;
        $definition = $metadata->definition;

        return [
            'key' => $definition->key(),
            'label' => $definition->label(),
            'description' => $definition->description(),
            'model_class' => $definition->modelClass(),
            'base_properties' => array_map(static fn ($property) => $property->toArray(), $definition->baseProperties()),
            'attribute_groups' => $metadata->attributeGroups
                ->map(function ($group) use ($request) {
                    return [
                        'id' => $group->id,
                        'name' => $group->name,
                        'advertisable_type_id' => $group->advertisable_type_id,
                        'advertisable_type' => $group->advertisableType?->model_class,
                        'display_order' => $group->display_order,
                        'created_at' => $group->created_at?->toISOString(),
                        'updated_at' => $group->updated_at?->toISOString(),
                        'definitions' => $group->definitions
                            ->map(fn ($definition) => (new AdAttributeDefinitionResource($definition))->toArray($request))
                            ->all(),
                    ];
                })
                ->values()
                ->all(),
        ];
    }
}
