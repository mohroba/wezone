<?php

namespace Modules\Ad\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Ad\Advertisable\AdvertisableTypeRegistry;
use Modules\Ad\Advertisable\Contracts\AdvertisableTypeDefinition;
use Modules\Ad\Advertisable\DTO\AdvertisableTypeMetadata;
use Modules\Ad\Http\Requests\AdvertisableType\StoreAdvertisableTypeRequest;
use Modules\Ad\Http\Requests\AdvertisableType\UpdateAdvertisableTypeRequest;
use Modules\Ad\Http\Resources\AdvertisableTypeModelResource;
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
     * Retrieve the registered advertisable classes for payload construction.
     *
     * @group Ads
     * @responseField key string Unique key registered for the advertisable type.
     * @responseField label string Human readable name.
     * @responseField model_class string Backing model class for advertisable instances.
     */
    public function classes(AdvertisableTypeRegistry $registry): JsonResponse
    {
        $classes = $registry->all()->map(function (AdvertisableTypeDefinition $definition) {
            return [
                'key' => $definition->key(),
                'label' => $definition->label(),
                'model_class' => $definition->modelClass(),
            ];
        });

        return response()->json(['data' => $classes->values()->all()]);
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

    /**
     * Create a new advertisable type.
     */
    public function store(StoreAdvertisableTypeRequest $request): JsonResponse
    {
        $payload = Arr::except($request->validated(), ['icon']);

        /** @var AdvertisableType $type */
        $type = DB::transaction(function () use ($payload, $request) {
            $type = AdvertisableType::create($payload);
            $this->syncIcon($type, $request);

            return $type->fresh();
        });

        return (new AdvertisableTypeModelResource($type))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Update an existing advertisable type.
     */
    public function update(UpdateAdvertisableTypeRequest $request, AdvertisableType $advertisableType): AdvertisableTypeModelResource
    {
        $payload = Arr::except($request->validated(), ['icon']);

        /** @var AdvertisableType $type */
        $type = DB::transaction(function () use ($advertisableType, $payload, $request) {
            $advertisableType->fill($payload);

            if ($advertisableType->isDirty()) {
                $advertisableType->save();
            }

            $this->syncIcon($advertisableType, $request);

            return $advertisableType->fresh();
        });

        return new AdvertisableTypeModelResource($type);
    }

    /**
     * Delete an advertisable type.
     */
    public function destroy(AdvertisableType $advertisableType): Response
    {
        $advertisableType->delete();

        return response()->noContent();
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

    private function syncIcon(AdvertisableType $type, Request $request): void
    {
        if (! $request->hasFile('icon')) {
            return;
        }

        $uploadedIcon = $request->file('icon');
        $mediaName = pathinfo((string) $uploadedIcon->getClientOriginalName(), PATHINFO_FILENAME);

        if ($mediaName === '') {
            $mediaName = $type->key ?? 'advertisable-type-icon';
        }

        $type->clearMediaCollection(AdvertisableType::COLLECTION_ICON);

        $type
            ->addMediaFromRequest('icon')
            ->usingName($mediaName)
            ->toMediaCollection(AdvertisableType::COLLECTION_ICON);
    }
}
