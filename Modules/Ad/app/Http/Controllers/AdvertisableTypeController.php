<?php

namespace Modules\Ad\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Ad\Advertisable\AdvertisableTypeRegistry;
use Modules\Ad\Advertisable\Contracts\AdvertisableTypeDefinition;
use Modules\Ad\Advertisable\DTO\AdvertisableTypeMetadata;
use Modules\Ad\Http\Resources\AdvertisableTypeResource;
use Modules\Ad\Models\AdAttributeGroup;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertisableTypeController extends Controller
{
    /**
     * List supported advertisable types.
     *
     * @group Ads
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
        $groups = AdAttributeGroup::query()
            ->with(['definitions' => fn ($query) => $query->orderBy('id')])
            ->where('advertisable_type', $definition->modelClass())
            ->orderBy('display_order')
            ->orderBy('id')
            ->get();

        return new AdvertisableTypeMetadata($definition, $groups);
    }
}
