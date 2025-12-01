<?php

namespace Modules\Ad\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\Ad\Http\Requests\Ad\StoreAdRequest;
use Modules\Ad\Http\Requests\Ad\UpdateAdRequest;
use Modules\Ad\Http\Resources\AdResource;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdAttributeDefinition;
use Modules\Ad\Models\AdCategory;
use Modules\Ad\Models\AdJob;
use Modules\Ad\Services\AdAttributeValuePayloadBuilder;

/**
 * @group Ads
 *
 * Endpoints for managing advertisement listings.
 */
class AdController extends Controller
{
    private const VIEW_TRACK_TTL_MINUTES = 60;

    public function __construct(private readonly AdAttributeValuePayloadBuilder $attributeValuePayloadBuilder)
    {
    }

    /**
     * List ads
     *
     * @group Ads
     *
     * Retrieve a filtered or paginated list of ads.
     *
     * @queryParam status string Filter by lifecycle status. Example: published
     * @queryParam user_id integer Filter by owner user ID. Example: 12
     * @queryParam category_id integer Limit to ads attached to the provided category. Example: 5
     * @queryParam search string Search within title and description text. Example: sedan
     * @queryParam only_published boolean Return only published records when true. Example: true
     * @queryParam per_page integer Number of results per page, up to 100. Example: 25
     * @queryParam without_pagination boolean Set to true to return all records without pagination. Example: false
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Ad::query()
            ->with([
                'categories',
                'advertisable',
                'advertisableType',
                'attributeValues.definition',
                'planPurchases' => fn ($relation) => $relation
                    ->with('plan')
                    ->latest(),
            ])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('user_id'), fn ($q) => $q->where('user_id', $request->input('user_id')))
            ->when($request->filled('category_id'), function ($q) use ($request) {
                $q->whereHas('categories', function ($categoryQuery) use ($request) {
                    $categoryQuery->where('ad_categories.id', $request->input('category_id'));
                });
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->input('search');

                $q->where(function ($inner) use ($search) {
                    $inner->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->boolean('only_published'), function ($q) {
                $q->where('status', 'published');
            })
            ->orderByDesc('created_at');

        $perPage = (int) min($request->integer('per_page', 15), 100);

        if ($request->boolean('without_pagination')) {
            return AdResource::collection($query->get());
        }

        return AdResource::collection(
            $query->paginate($perPage)->appends($request->query())
        );
    }

    /**
     * Create an ad
     *
     * @group Ads
     *
     * Store a new ad and attach optional category assignments.
     */
    public function store(StoreAdRequest $request): JsonResponse
    {
        $payload = collect($request->validated());
        $advertisableAttributes = $request->advertisablePayload();
        $categories = $payload->pull('categories', []);
        $attributeValues = $payload->pull('attribute_values', []);
        $payload->pull('advertisable');

        $typeModel = $request->advertisableTypeModel();
        $typeClass = $typeModel->model_class;

        /** @var Ad $ad */
        $ad = DB::transaction(function () use ($payload, $categories, $attributeValues, $advertisableAttributes, $typeModel, $typeClass) {
            $advertisableModel = $this->createAdvertisableModel(
                $typeClass,
                $advertisableAttributes ?? [],
                $payload->get('slug')
            );

            $payload->put('advertisable_type', $typeClass);
            $payload->put('advertisable_id', $advertisableModel->getKey());
            $payload->put('advertisable_type_id', $typeModel->getKey());

            $ad = Ad::create($payload->toArray());

            $this->syncCategories($ad, $categories);
            $this->syncAttributeValues($ad, $attributeValues);

            return $ad->load(['categories', 'advertisable', 'advertisableType', 'attributeValues.definition', 'media']);
        });

        return (new AdResource($ad))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Show ad details
     *
     * @group Ads
     *
     * Retrieve the complete information for a single ad.
     */
    public function show(Ad $ad): AdResource
    {
        $ad->load([
            'categories',
            'advertisable',
            'advertisableType',
            'attributeValues.definition',
            'user' => function ($query): void {
                $query
                    ->with(['profile.media'])
                    ->withCount([
                        'ads as published_ads_count' => fn ($builder) => $builder->where('status', 'published'),
                        'followers',
                    ]);
            },
            'payments' => fn ($query) => $query->latest(),
            'planPurchases' => fn ($query) => $query
                ->with([
                    'plan',
                    'payments' => fn ($paymentQuery) => $paymentQuery->latest(),
                ])
                ->latest(),
        ]);

        return new AdResource($ad);
    }

    /**
     * Update an ad
     *
     * @group Ads
     *
     * Apply updates to an existing ad, optionally recording status and slug history.
     */
    public function update(UpdateAdRequest $request, Ad $ad): AdResource
    {
        $payload = collect($request->validated());
        $advertisableAttributes = $request->advertisablePayload();
        $categories = $payload->pull('categories', null);
        $attributeValues = $payload->pull('attribute_values', null);
        $statusNote = $payload->pull('status_note');
        $statusMetadata = $payload->pull('status_metadata');
        $payload->pull('advertisable');

        $previousStatus = $ad->status;
        $previousSlug = $ad->slug;

        $typeModel = $request->advertisableTypeModel();
        $typeClass = $typeModel->model_class;

        /** @var Ad $ad */
        $ad = DB::transaction(function () use ($ad, $payload, $categories, $attributeValues, $previousStatus, $previousSlug, $statusNote, $statusMetadata, $request, $advertisableAttributes, $typeModel, $typeClass) {
            $targetSlug = $payload->get('slug', $ad->slug);

            if ($advertisableAttributes !== null) {
                $advertisableModel = $this->persistUpdatedAdvertisable($ad, [
                    'type' => $typeClass,
                    'attributes' => $advertisableAttributes,
                ], $targetSlug);
                $payload->put('advertisable_id', $advertisableModel->getKey());
            } elseif ($payload->has('slug') && $ad->advertisable) {
                $this->ensureAdvertisableSlug($ad->advertisable, $targetSlug);
            }

            $payload->put('advertisable_type', $typeClass);
            $payload->put('advertisable_type_id', $typeModel->getKey());

            $ad->fill($payload->toArray());
            $ad->save();

            if ($categories !== null) {
                $this->syncCategories($ad, $categories);
            }

            if ($attributeValues !== null) {
                $this->syncAttributeValues($ad, $attributeValues);
            }

            if ($previousSlug !== $ad->slug) {
                $ad->slugHistories()->create([
                    'slug' => $previousSlug,
                    'redirect_to_slug' => $ad->slug,
                ]);
            }

            if ($previousStatus !== $ad->status) {
                $ad->statusHistories()->create([
                    'from_status' => $previousStatus,
                    'to_status' => $ad->status,
                    'changed_by' => optional($request->user())->id,
                    'notes' => $statusNote,
                    'metadata' => $statusMetadata,
                ]);
            }

            return $ad->load(['categories', 'advertisable', 'advertisableType', 'attributeValues.definition', 'media']);
        });

        return new AdResource($ad);
    }

    /**
     * Delete an ad
     *
     * @group Ads
     *
     * Soft delete the specified ad.
     */
    public function destroy(Ad $ad): Response
    {
        $ad->delete();

        return response()->noContent();
    }

    /**
     * Register an ad view
     *
     * @group Ads
     *
     * Safely record a view for the specified ad. Repeated requests from the same viewer
     * within a short time window are ignored to avoid double counting.
     *
     * @responseField data.id int The ad identifier.
     * @responseField data.view_count int The updated total number of views.
     * @responseField data.incremented bool Indicates whether the counter increased.
     */
    public function markSeen(Request $request, Ad $ad): JsonResponse
    {
        $cacheKey = $this->viewTrackerCacheKey($request, $ad);
        $viewRecorded = Cache::add($cacheKey, true, now()->addMinutes(self::VIEW_TRACK_TTL_MINUTES));

        if ($viewRecorded) {
            $ad->increment('view_count');
            $ad->refresh();
        }

        return response()->json([
            'data' => [
                'id' => $ad->getKey(),
                'view_count' => $ad->view_count,
                'incremented' => $viewRecorded,
            ],
        ]);
    }

    private function createAdvertisableModel(string $type, array $attributes, ?string $slug): Model
    {
        /** @var Model $model */
        $model = new $type();
        $payload = $this->applySlugToAttributes(
            $this->applyDefaultAdvertisableAttributes($type, $attributes),
            $slug,
            $model
        );

        $model->fill($payload);
        $model->save();

        return $model;
    }

    private function persistUpdatedAdvertisable(Ad $ad, array $advertisablePayload, ?string $slug): Model
    {
        $type = $advertisablePayload['type'];
        $attributes = $advertisablePayload['attributes'];

        if ($ad->advertisable && $ad->advertisable_type === $type) {
            return $this->updateAdvertisableModel($ad->advertisable, $attributes, $slug);
        }

        if ($ad->advertisable) {
            $ad->advertisable->delete();
        }

        return $this->createAdvertisableModel($type, $attributes, $slug);
    }

    private function updateAdvertisableModel(Model $model, array $attributes, ?string $slug): Model
    {
        $payload = $this->applySlugToAttributes(
            $this->applyDefaultAdvertisableAttributes(get_class($model), $attributes),
            $slug,
            $model
        );

        $model->fill($payload);
        $model->save();

        return $model;
    }

    private function ensureAdvertisableSlug(Model $model, string $slug): void
    {
        if ((string) $model->getAttribute('slug') === $slug) {
            return;
        }

        $model->fill(['slug' => $slug]);
        $model->save();
    }

    /**
     * @param array<string, mixed> $attributes
     */
    private function applySlugToAttributes(array $attributes, ?string $slug, Model $model): array
    {
        if (! array_key_exists('slug', $attributes) || empty($attributes['slug'])) {
            $attributes['slug'] = $slug ?? (string) $model->getAttribute('slug') ?: Str::uuid()->toString();
        }

        return $attributes;
    }

    /**
     * Provide minimal defaults so draft advertisables can be created without full payload.
     *
     * @param array<string, mixed> $attributes
     * @return array<string, mixed>
     */
    private function applyDefaultAdvertisableAttributes(string $type, array $attributes): array
    {
        // For job adverts, required fields must be non-null in the DB.
        if ($type === AdJob::class) {
            $attributes['company_name'] = $attributes['company_name'] ?? 'Draft company';
            $attributes['position_title'] = $attributes['position_title'] ?? 'Draft position';
        }

        return $attributes;
    }

    private function syncCategories(Ad $ad, array $categories): void
    {
        $categoryCollection = collect($categories);

        $categoryIds = $categoryCollection
            ->pluck('id')
            ->filter(fn ($id) => $id !== null)
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($categoryIds->isEmpty()) {
            $ad->categories()->detach();

            return;
        }

        $categoryMap = AdCategory::query()
            ->whereIn('id', $categoryIds)
            ->get(['id', 'advertisable_type_id'])
            ->keyBy('id');

        foreach ($categoryIds as $categoryId) {
            $category = $categoryMap->get($categoryId);

            if (! $category || (int) $category->advertisable_type_id !== (int) $ad->advertisable_type_id) {
                throw ValidationException::withMessages([
                    'categories' => ['Selected categories must belong to the same advertisable type as the ad.'],
                ]);
            }
        }

        $payload = $categoryCollection->mapWithKeys(function ($category) {
            $categoryId = (int) data_get($category, 'id');

            return [$categoryId => [
                'is_primary' => (bool) data_get($category, 'is_primary', false),
                'assigned_by' => data_get($category, 'assigned_by'),
            ]];
        })->all();

        $ad->categories()->sync($payload);
    }

    private function syncAttributeValues(Ad $ad, array $attributeValues): void
    {
        $ad->attributeValues()->delete();

        if ($attributeValues === []) {
            return;
        }

        $definitionIds = collect($attributeValues)
            ->pluck('definition_id')
            ->filter()
            ->unique()
            ->values();

        if ($definitionIds->isEmpty()) {
            return;
        }

        $definitions = AdAttributeDefinition::query()
            ->whereIn('id', $definitionIds)
            ->get()
            ->keyBy('id');

        $payloads = collect($attributeValues)
            ->map(function ($value) use ($definitions, $ad) {
                $definition = $definitions->get(data_get($value, 'definition_id'));

                if (! $definition) {
                    return null;
                }

                return $this->attributeValuePayloadBuilder->build(
                    $definition,
                    array_merge($value, ['ad_id' => $ad->getKey()]),
                    includeMissing: true
                );
            })
            ->filter()
            ->values()
            ->all();

        if ($payloads === []) {
            return;
        }

        $ad->attributeValues()->createMany($payloads);
    }


    private function viewTrackerCacheKey(Request $request, Ad $ad): string
    {
        $viewerKey = $this->resolveViewerKey($request);

        return sprintf('ads:%s:viewers:%s', $ad->getKey(), $viewerKey);
    }

    private function resolveViewerKey(Request $request): string
    {
        $user = $request->user();

        if ($user !== null) {
            return 'user:' . $user->getAuthIdentifier();
        }

        $ipAddress = $request->ip() ?? 'unknown-ip';
        $userAgent = $request->userAgent() ?? 'unknown-agent';

        return 'guest:' . sha1($ipAddress . '|' . $userAgent);
    }

}
