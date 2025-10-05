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

class AdAttributeDefinitionController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = AdAttributeDefinition::query()
            ->when($request->filled('group_id'), fn ($q) => $q->where('group_id', $request->input('group_id')))
            ->orderBy('id');

        if ($request->boolean('without_pagination')) {
            return AdAttributeDefinitionResource::collection($query->get());
        }

        $perPage = (int) min($request->integer('per_page', 25), 200);

        return AdAttributeDefinitionResource::collection(
            $query->paginate($perPage)->appends($request->query())
        );
    }

    public function store(StoreAdAttributeDefinitionRequest $request): JsonResponse
    {
        $definition = AdAttributeDefinition::create($request->validated());

        return (new AdAttributeDefinitionResource($definition))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(AdAttributeDefinition $adAttributeDefinition): AdAttributeDefinitionResource
    {
        return new AdAttributeDefinitionResource($adAttributeDefinition);
    }

    public function update(UpdateAdAttributeDefinitionRequest $request, AdAttributeDefinition $adAttributeDefinition): AdAttributeDefinitionResource
    {
        $adAttributeDefinition->fill($request->validated());
        $adAttributeDefinition->save();

        return new AdAttributeDefinitionResource($adAttributeDefinition);
    }

    public function destroy(AdAttributeDefinition $adAttributeDefinition): Response
    {
        $adAttributeDefinition->delete();

        return response()->noContent();
    }
}
