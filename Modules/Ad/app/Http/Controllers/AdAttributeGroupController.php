<?php

namespace Modules\Ad\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Ad\Http\Requests\AdAttributeGroup\StoreAdAttributeGroupRequest;
use Modules\Ad\Http\Requests\AdAttributeGroup\UpdateAdAttributeGroupRequest;
use Modules\Ad\Http\Resources\AdAttributeGroupResource;
use Modules\Ad\Models\AdAttributeGroup;

/**
 * @group Ad Attribute Groups
 *
 * Manage groupings of dynamic attributes for advertisable entities.
 */
class AdAttributeGroupController extends Controller
{
    /**
     * List attribute groups
     *
     * @group Ad Attribute Groups
     *
     * Retrieve attribute groups filtered by advertisable type or category.
     *
     * @queryParam advertisable_type string Filter by advertisable class name. Example: Modules\\Ad\\Models\\AdCar
     * @queryParam category_id integer Filter groups scoped to the given category. Example: 8
     * @queryParam per_page integer Number of results per page, up to 200. Example: 25
     * @queryParam without_pagination boolean Set to true to return all groups without pagination. Example: false
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = AdAttributeGroup::query()
            ->when($request->filled('advertisable_type'), fn ($q) => $q->where('advertisable_type', $request->input('advertisable_type')))
            ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->input('category_id')))
            ->orderBy('display_order')
            ->orderBy('id');

        if ($request->boolean('without_pagination')) {
            return AdAttributeGroupResource::collection($query->get());
        }

        $perPage = (int) min($request->integer('per_page', 25), 200);

        return AdAttributeGroupResource::collection(
            $query->paginate($perPage)->appends($request->query())
        );
    }

    /**
     * Create an attribute group
     *
     * @group Ad Attribute Groups
     *
     * Persist a new attribute group for organising attribute definitions.
     */
    public function store(StoreAdAttributeGroupRequest $request): JsonResponse
    {
        $group = AdAttributeGroup::create($request->validated());

        return (new AdAttributeGroupResource($group))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Show an attribute group
     *
     * @group Ad Attribute Groups
     *
     * Retrieve details for a single attribute group.
     */
    public function show(AdAttributeGroup $adAttributeGroup): AdAttributeGroupResource
    {
        return new AdAttributeGroupResource($adAttributeGroup);
    }

    /**
     * Update an attribute group
     *
     * @group Ad Attribute Groups
     *
     * Apply modifications to the specified attribute group.
     */
    public function update(UpdateAdAttributeGroupRequest $request, AdAttributeGroup $adAttributeGroup): AdAttributeGroupResource
    {
        $adAttributeGroup->fill($request->validated());
        $adAttributeGroup->save();

        return new AdAttributeGroupResource($adAttributeGroup);
    }

    /**
     * Delete an attribute group
     *
     * @group Ad Attribute Groups
     *
     * Remove the specified attribute group.
     */
    public function destroy(AdAttributeGroup $adAttributeGroup): Response
    {
        $adAttributeGroup->delete();

        return response()->noContent();
    }
}
