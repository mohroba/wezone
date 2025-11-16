<?php

namespace Modules\Ad\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Modules\Ad\Http\Requests\AdAttributeValue\StoreAdAttributeValueRequest;
use Modules\Ad\Http\Requests\AdAttributeValue\UpdateAdAttributeValueRequest;
use Modules\Ad\Http\Resources\AdAttributeValueResource;
use Modules\Ad\Models\AdAttributeDefinition;
use Modules\Ad\Models\AdAttributeValue;
use JsonException;

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
        $definition = AdAttributeDefinition::query()->findOrFail($request->input('definition_id'));
        $value = AdAttributeValue::create(
            $this->buildValuePayload($definition, $request->validated(), includeMissing: true)
        )->load('definition');

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
        $definition = $this->resolveDefinitionForUpdate($request, $adAttributeValue);

        $adAttributeValue->fill(
            $this->buildValuePayload($definition, $request->validated(), includeMissing: false, currentValue: $adAttributeValue)
        );
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

    private function buildValuePayload(
        AdAttributeDefinition $definition,
        array $payload,
        bool $includeMissing,
        ?AdAttributeValue $currentValue = null
    ): array {
        $columns = [
            'value_string',
            'value_integer',
            'value_decimal',
            'value_boolean',
            'value_date',
            'value_json',
        ];

        $result = [
            'definition_id' => $definition->getKey(),
        ];

        if (array_key_exists('ad_id', $payload)) {
            $result['ad_id'] = $payload['ad_id'];
        } elseif ($currentValue) {
            $result['ad_id'] = $currentValue->ad_id;
        }

        foreach ($columns as $column) {
            if (array_key_exists($column, $payload)) {
                $result[$column] = $column === 'value_date' && $payload[$column]
                    ? Carbon::parse($payload[$column])->toDateString()
                    : $payload[$column];
            } elseif ($includeMissing) {
                $result[$column] = null;
            }
        }

        $valueColumn = $this->valueColumnForType($definition->data_type);
        $shouldUpdateNormalized = $valueColumn && ($includeMissing || array_key_exists($valueColumn, $payload));

        if ($shouldUpdateNormalized && $valueColumn) {
            $result['normalized_value'] = $this->normalizedValue($definition, $payload, $currentValue);
        }

        return $result;
    }

    private function resolveDefinitionForUpdate(
        UpdateAdAttributeValueRequest $request,
        AdAttributeValue $adAttributeValue
    ): AdAttributeDefinition {
        if ($request->filled('definition_id')) {
            return AdAttributeDefinition::query()->findOrFail($request->input('definition_id'));
        }

        return $adAttributeValue->definition;
    }

    private function valueColumnForType(string $type): ?string
    {
        return match ($type) {
            'string', 'enum' => 'value_string',
            'integer' => 'value_integer',
            'decimal' => 'value_decimal',
            'boolean' => 'value_boolean',
            'date' => 'value_date',
            'json' => 'value_json',
            default => null,
        };
    }

    private function normalizedValue(
        AdAttributeDefinition $definition,
        array $payload,
        ?AdAttributeValue $currentValue = null
    ): ?string {
        $valueColumn = $this->valueColumnForType($definition->data_type);

        if (! $valueColumn) {
            return null;
        }

        $rawValue = array_key_exists($valueColumn, $payload)
            ? $payload[$valueColumn]
            : ($currentValue?->{$valueColumn});

        if ($rawValue === null) {
            return null;
        }

        return match ($definition->data_type) {
            'string', 'enum' => $this->normalizeStringValue($rawValue),
            'integer' => (string) (int) $rawValue,
            'decimal' => $this->normalizeDecimalValue($rawValue),
            'boolean' => $rawValue ? '1' : '0',
            'date' => $this->normalizeDateValue($rawValue),
            'json' => $this->normalizeJsonValue($rawValue),
            default => null,
        };
    }

    private function normalizeStringValue(mixed $value): string
    {
        return Str::of((string) $value)->lower()->trim()->value();
    }

    private function normalizeDecimalValue(mixed $value): string
    {
        $number = is_string($value) ? (float) $value : (float) ($value ?? 0);

        return rtrim(rtrim(number_format($number, 4, '.', ''), '0'), '.') ?: '0';
    }

    private function normalizeDateValue(mixed $value): string
    {
        return Carbon::parse($value)->toDateString();
    }

    private function normalizeJsonValue(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            return $value;
        }

        try {
            return json_encode($value, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return null;
        }
    }
}
