<?php

namespace Modules\Ad\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Modules\Ad\Http\Requests\Ad\StoreAdRequest;
use Modules\Ad\Http\Requests\Ad\UpdateAdRequest;
use Modules\Ad\Http\Resources\AdResource;
use Modules\Ad\Models\Ad;

class AdController extends Controller
{
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

    public function store(StoreAdRequest $request): JsonResponse
    {
        $payload = collect($request->validated());
        $categories = $payload->pull('categories', []);

        /** @var Ad $ad */
        $ad = DB::transaction(function () use ($payload, $categories) {
            $ad = Ad::create($payload->toArray());
            $this->syncCategories($ad, $categories);

            return $ad->load(['categories', 'advertisable']);
        });

        return (new AdResource($ad))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Ad $ad): AdResource
    {
        $ad->load(['categories', 'advertisable']);

        return new AdResource($ad);
    }

    public function update(UpdateAdRequest $request, Ad $ad): AdResource
    {
        $payload = collect($request->validated());
        $categories = $payload->pull('categories', null);
        $statusNote = $payload->pull('status_note');
        $statusMetadata = $payload->pull('status_metadata');

        $previousStatus = $ad->status;
        $previousSlug = $ad->slug;

        /** @var Ad $ad */
        $ad = DB::transaction(function () use ($ad, $payload, $categories, $previousStatus, $previousSlug, $statusNote, $statusMetadata, $request) {
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

            return $ad->load(['categories', 'advertisable']);
        });

        return new AdResource($ad);
    }

    public function destroy(Ad $ad): Response
    {
        $ad->delete();

        return response()->noContent();
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
}
