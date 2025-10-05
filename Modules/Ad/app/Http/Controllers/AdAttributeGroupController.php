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

class AdAttributeGroupController extends Controller
{
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

    public function store(StoreAdAttributeGroupRequest $request): JsonResponse
    {
        $group = AdAttributeGroup::create($request->validated());

        return (new AdAttributeGroupResource($group))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(AdAttributeGroup $adAttributeGroup): AdAttributeGroupResource
    {
        return new AdAttributeGroupResource($adAttributeGroup);
    }

    public function update(UpdateAdAttributeGroupRequest $request, AdAttributeGroup $adAttributeGroup): AdAttributeGroupResource
    {
        $adAttributeGroup->fill($request->validated());
        $adAttributeGroup->save();

        return new AdAttributeGroupResource($adAttributeGroup);
    }

    public function destroy(AdAttributeGroup $adAttributeGroup): Response
    {
        $adAttributeGroup->delete();

        return response()->noContent();
    }
}
