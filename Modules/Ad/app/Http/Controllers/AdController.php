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
use Modules\Ad\Models\AdvertisableType;

/**
 * @group Ads
 *
 * Endpoints for managing advertisement listings.
 */
class AdController extends Controller
{
    private const VIEW_TRACK_TTL_MINUTES = 60;

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
            ->with(['categories', 'advertisable'])
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
        $advertisablePayload = $request->advertisablePayload();
        $categories = $payload->pull('categories', []);
        $payload->pull('advertisable');

        $typeId = $this->resolveAdvertisableTypeId($advertisablePayload['type']);

        /** @var Ad $ad */
        $ad = DB::transaction(function () use ($payload, $categories, $advertisablePayload, $typeId) {
            $advertisableModel = $this->createAdvertisableModel(
                $advertisablePayload['type'],
                $advertisablePayload['attributes'],
                $payload->get('slug')
            );

            $payload->put('advertisable_type', $advertisablePayload['type']);
            $payload->put('advertisable_id', $advertisableModel->getKey());
            $payload->put('advertisable_type_id', $typeId);

            $ad = Ad::create($payload->toArray());

            $this->syncCategories($ad, $categories);

            return $ad->load(['categories', 'advertisable', 'media']);
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
        $advertisablePayload = $request->advertisablePayload();
        $categories = $payload->pull('categories', null);
        $statusNote = $payload->pull('status_note');
        $statusMetadata = $payload->pull('status_metadata');
        $payload->pull('advertisable');

        $previousStatus = $ad->status;
        $previousSlug = $ad->slug;

        /** @var Ad $ad */
        $ad = DB::transaction(function () use ($ad, $payload, $categories, $previousStatus, $previousSlug, $statusNote, $statusMetadata, $request, $advertisablePayload) {
            $targetSlug = $payload->get('slug', $ad->slug);

            if ($advertisablePayload !== null) {
                $advertisableModel = $this->persistUpdatedAdvertisable($ad, $advertisablePayload, $targetSlug);
                $payload->put('advertisable_type', $advertisablePayload['type']);
                $payload->put('advertisable_id', $advertisableModel->getKey());
                $payload->put('advertisable_type_id', $this->resolveAdvertisableTypeId($advertisablePayload['type']));
            } elseif ($payload->has('slug') && $ad->advertisable) {
                $this->ensureAdvertisableSlug($ad->advertisable, $targetSlug);
            }

            $ad->fill($payload->toArray());
            $ad->save();

            if ($categories !== null) {
                $this->syncCategories($ad, $categories);
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

            return $ad->load(['categories', 'advertisable', 'media']);
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
        $payload = $this->applySlugToAttributes($attributes, $slug, $model);

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
        $payload = $this->applySlugToAttributes($attributes, $slug, $model);

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

    private function syncCategories(Ad $ad, array $categories): void
    {
        $payload = collect($categories)->mapWithKeys(function ($category) {
            $categoryId = (int) data_get($category, 'id');

            return [$categoryId => [
                'is_primary' => (bool) data_get($category, 'is_primary', false),
                'assigned_by' => data_get($category, 'assigned_by'),
            ]];
        })->all();

        if (empty($payload)) {
            $ad->categories()->detach();

            return;
        }

        $ad->categories()->sync($payload);
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

    private function resolveAdvertisableTypeId(string $modelClass): int
    {
        $typeId = AdvertisableType::query()->where('model_class', $modelClass)->value('id');

        if ($typeId === null) {
            throw ValidationException::withMessages([
                'advertisable.type' => 'The selected advertisable type is not registered.',
            ]);
        }

        return (int) $typeId;
    }
}
