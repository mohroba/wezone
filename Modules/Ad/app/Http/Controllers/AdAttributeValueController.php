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

class AdAttributeValueController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = AdAttributeValue::query()
            ->with('definition')
            ->when($request->filled('definition_id'), fn ($q) => $q->where('definition_id', $request->input('definition_id')))
            ->when($request->filled('advertisable_type'), fn ($q) => $q->where('advertisable_type', $request->input('advertisable_type')))
            ->when($request->filled('advertisable_id'), fn ($q) => $q->where('advertisable_id', $request->input('advertisable_id')))
            ->orderByDesc('updated_at');

        if ($request->boolean('without_pagination')) {
            return AdAttributeValueResource::collection($query->get());
        }

        $perPage = (int) min($request->integer('per_page', 25), 200);

        return AdAttributeValueResource::collection(
            $query->paginate($perPage)->appends($request->query())
        );
    }

    public function store(StoreAdAttributeValueRequest $request): JsonResponse
    {
        $value = AdAttributeValue::create($this->mapPayload($request->validated(), includeMissing: true))->load('definition');

        return (new AdAttributeValueResource($value))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(AdAttributeValue $adAttributeValue): AdAttributeValueResource
    {
        return new AdAttributeValueResource($adAttributeValue->load('definition'));
    }

    public function update(UpdateAdAttributeValueRequest $request, AdAttributeValue $adAttributeValue): AdAttributeValueResource
    {
        $adAttributeValue->fill($this->mapPayload($request->validated(), includeMissing: false));
        $adAttributeValue->save();
        $adAttributeValue->load('definition');

        return new AdAttributeValueResource($adAttributeValue);
    }

    public function destroy(AdAttributeValue $adAttributeValue): Response
    {
        $adAttributeValue->delete();

        return response()->noContent();
    }

    private function mapPayload(array $payload, bool $includeMissing): array
    {
        $columns = [
            'definition_id',
            'advertisable_type',
            'advertisable_id',
            'value_string',
            'value_integer',
            'value_decimal',
            'value_boolean',
            'value_json',
            'normalized_value',
        ];

        $result = [];

        foreach ($columns as $column) {
            if (array_key_exists($column, $payload)) {
                $result[$column] = $payload[$column];
            } elseif ($includeMissing && ! in_array($column, ['definition_id', 'advertisable_type', 'advertisable_id'], true)) {
                $result[$column] = null;
            }
        }

        return $result;
    }
}
