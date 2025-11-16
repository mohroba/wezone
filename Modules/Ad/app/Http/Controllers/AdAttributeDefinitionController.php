<?php

namespace Modules\Ad\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Ad\Http\Requests\AdAttributeDefinition\StoreAdAttributeDefinitionRequest;
use Modules\Ad\Http\Requests\AdAttributeDefinition\UpdateAdAttributeDefinitionRequest;
use Modules\Ad\Http\Resources\AdAttributeDefinitionResource;
use Modules\Ad\Models\AdAttributeDefinition;

/**
 * @group Ad Attribute Definitions
 *
 * Manage individual attribute definitions assigned to advertisable entities.
 */
class AdAttributeDefinitionController extends Controller
{
    /**
     * List attribute definitions
     *
     * @group Ad Attribute Definitions
     *
     * Retrieve attribute definitions optionally filtered by group.
     *
     * @queryParam attribute_group_id integer Limit results to a specific attribute group. Example: 4
     * @queryParam per_page integer Number of results per page, up to 200. Example: 25
     * @queryParam without_pagination boolean Set to true to return all definitions without pagination. Example: false
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = AdAttributeDefinition::query()
            ->when($request->filled('attribute_group_id'), fn ($q) => $q->where('attribute_group_id', $request->input('attribute_group_id')))
            ->orderBy('id');

        if ($request->boolean('without_pagination')) {
            return AdAttributeDefinitionResource::collection($query->get());
        }

        $perPage = (int) min($request->integer('per_page', 25), 200);

        return AdAttributeDefinitionResource::collection(
            $query->paginate($perPage)->appends($request->query())
        );
    }

    /**
     * Create an attribute definition
     *
     * @group Ad Attribute Definitions
     *
     * Persist a new attribute definition for dynamic ad data.
     */
    public function store(StoreAdAttributeDefinitionRequest $request): JsonResponse
    {
        $definition = AdAttributeDefinition::create($request->validated());

        return (new AdAttributeDefinitionResource($definition))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Show an attribute definition
     *
     * @group Ad Attribute Definitions
     *
     * Retrieve a single attribute definition with metadata.
     */
    public function show(AdAttributeDefinition $adAttributeDefinition): AdAttributeDefinitionResource
    {
        return new AdAttributeDefinitionResource($adAttributeDefinition);
    }

    /**
     * Update an attribute definition
     *
     * @group Ad Attribute Definitions
     *
     * Apply changes to an existing attribute definition.
     */
    public function update(UpdateAdAttributeDefinitionRequest $request, AdAttributeDefinition $adAttributeDefinition): AdAttributeDefinitionResource
    {
        $adAttributeDefinition->fill($request->validated());
        $adAttributeDefinition->save();

        return new AdAttributeDefinitionResource($adAttributeDefinition);
    }

    /**
     * Delete an attribute definition
     *
     * @group Ad Attribute Definitions
     *
     * Remove the specified attribute definition.
     */
    public function destroy(AdAttributeDefinition $adAttributeDefinition): Response
    {
        $adAttributeDefinition->delete();

        return response()->noContent();
    }
}
