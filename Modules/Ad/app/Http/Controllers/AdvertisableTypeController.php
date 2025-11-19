<?php

namespace Modules\Ad\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Ad\Advertisable\AdvertisableTypeRegistry;
use Modules\Ad\Advertisable\Contracts\AdvertisableTypeDefinition;
use Modules\Ad\Advertisable\DTO\AdvertisableTypeMetadata;
use Modules\Ad\Http\Resources\AdvertisableTypeResource;
use Modules\Ad\Models\AdAttributeGroup;
use Modules\Ad\Models\AdvertisableType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertisableTypeController extends Controller
{
    /**
     * List supported advertisable types.
     *
     * @group Ads
     * @responseField id integer Database identifier of the advertisable type.
     * @responseField key string Unique key registered for the advertisable type.
     * @responseField label string Human readable name.
     * @responseField description string|null Description shown to clients.
     * @responseField model_class string Backing model class for advertisable instances.
     * @responseField icon_url string|null Public URL of the advertisable type icon.
     * @responseField attribute_groups array Attribute groups and definitions describing the advertisable payload.
     */
    public function index(AdvertisableTypeRegistry $registry): AnonymousResourceCollection
    {
        $types = $registry->all()->map(fn (AdvertisableTypeDefinition $definition) => $this->buildMetadata($definition));

        return AdvertisableTypeResource::collection($types);
    }

    /**
     * Show metadata for an advertisable type.
     *
     * @group Ads
     * @responseField id integer Database identifier of the advertisable type.
     * @responseField key string Unique key registered for the advertisable type.
     * @responseField label string Human readable name.
     * @responseField description string|null Description shown to clients.
     * @responseField model_class string Backing model class for advertisable instances.
     * @responseField icon_url string|null Public URL of the advertisable type icon.
     * @responseField attribute_groups array Attribute groups and definitions describing the advertisable payload.
     */
    public function show(string $key, AdvertisableTypeRegistry $registry): AdvertisableTypeResource
    {
        $definition = $registry->getByKey($key);

        if ($definition === null) {
            throw new NotFoundHttpException('Advertisable type not found.');
        }

        return new AdvertisableTypeResource($this->buildMetadata($definition));
    }

    private function buildMetadata(AdvertisableTypeDefinition $definition): AdvertisableTypeMetadata
    {
        $typeModel = AdvertisableType::query()
            ->firstOrCreate(
                [
                    'key' => $definition->key(),
                    'model_class' => $definition->modelClass(),
                ],
                [
                    'label' => $definition->label(),
                    'description' => $definition->description(),
                ],
            );

        $typeModel->fill([
            'label' => $definition->label(),
            'description' => $definition->description(),
        ]);

        if ($typeModel->isDirty()) {
            $typeModel->save();
        }

        $groups = AdAttributeGroup::query()
            ->with([
                'definitions' => fn ($query) => $query->orderBy('id'),
                'advertisableType',
            ])
            ->where('advertisable_type_id', $typeModel->id)
            ->orderBy('display_order')
            ->orderBy('id')
            ->get();

        return new AdvertisableTypeMetadata($definition, $groups, $typeModel);
    }
}
