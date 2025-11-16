<?php

namespace Modules\Ad\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Ad\Http\Requests\AdAttributeValue\StoreAdAttributeValueRequest;
use Modules\Ad\Http\Requests\AdAttributeValue\UpdateAdAttributeValueRequest;
use Modules\Ad\Http\Resources\AdAttributeValueResource;
use Modules\Ad\Models\AdAttributeValue;

/**
 * @group Ad Attribute Values
 *
 * Manage stored values for dynamic advertisable attributes.
 */
class AdAttributeValueController extends Controller
{
    /**
     * List attribute values
     *
     * @group Ad Attribute Values
     *
     * Retrieve attribute values filtered by definition or advertisable linkage.
     *
     * @queryParam definition_id integer Filter by attribute definition. Example: 12
     * @queryParam ad_id integer Filter by the owning ad identifier. Example: 34
     * @queryParam per_page integer Number of results per page, up to 200. Example: 25
     * @queryParam without_pagination boolean Set to true to return all values without pagination. Example: false
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = AdAttributeValue::query()
            ->with('definition')
            ->when($request->filled('definition_id'), fn ($q) => $q->where('definition_id', $request->input('definition_id')))
            ->when($request->filled('ad_id'), fn ($q) => $q->where('ad_id', $request->input('ad_id')))
            ->orderByDesc('updated_at');

        if ($request->boolean('without_pagination')) {
            return AdAttributeValueResource::collection($query->get());
        }

        $perPage = (int) min($request->integer('per_page', 25), 200);

        return AdAttributeValueResource::collection(
            $query->paginate($perPage)->appends($request->query())
        );
    }

    /**
     * Create an attribute value
     *
     * @group Ad Attribute Values
     *
     * Persist a new attribute value for an advertisable model.
     */
    public function store(StoreAdAttributeValueRequest $request): JsonResponse
    {
        $value = AdAttributeValue::create($this->mapPayload($request->validated(), includeMissing: true))->load('definition');

        return (new AdAttributeValueResource($value))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Show an attribute value
     *
     * @group Ad Attribute Values
     *
     * Retrieve an attribute value with its definition metadata.
     */
    public function show(AdAttributeValue $adAttributeValue): AdAttributeValueResource
    {
        return new AdAttributeValueResource($adAttributeValue->load('definition'));
    }

    /**
     * Update an attribute value
     *
     * @group Ad Attribute Values
     *
     * Apply updates to an existing attribute value.
     */
    public function update(UpdateAdAttributeValueRequest $request, AdAttributeValue $adAttributeValue): AdAttributeValueResource
    {
        $adAttributeValue->fill($this->mapPayload($request->validated(), includeMissing: false));
        $adAttributeValue->save();
        $adAttributeValue->load('definition');

        return new AdAttributeValueResource($adAttributeValue);
    }

    /**
     * Delete an attribute value
     *
     * @group Ad Attribute Values
     *
     * Remove the specified attribute value record.
     */
    public function destroy(AdAttributeValue $adAttributeValue): Response
    {
        $adAttributeValue->delete();

        return response()->noContent();
    }

    private function mapPayload(array $payload, bool $includeMissing): array
    {
        $columns = [
            'definition_id',
            'ad_id',
            'value_string',
            'value_integer',
            'value_decimal',
            'value_boolean',
            'value_date',
            'value_json',
            'normalized_value',
        ];

        $result = [];

        foreach ($columns as $column) {
            if (array_key_exists($column, $payload)) {
                $result[$column] = $payload[$column];
            } elseif ($includeMissing && ! in_array($column, ['definition_id', 'ad_id'], true)) {
                $result[$column] = null;
            }
        }

        return $result;
    }
}
