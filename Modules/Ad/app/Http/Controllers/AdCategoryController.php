<?php

namespace Modules\Ad\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Modules\Ad\Http\Requests\AdCategory\StoreAdCategoryRequest;
use Modules\Ad\Http\Requests\AdCategory\UpdateAdCategoryRequest;
use Modules\Ad\Http\Resources\AdCategoryResource;
use Modules\Ad\Models\AdCategory;
use Modules\Ad\Services\CategoryHierarchyManager;

/**
 * @group Ad Categories
 *
 * Manage the hierarchical taxonomy used to organise ads.
 */
class AdCategoryController extends Controller
{
    public function __construct(private readonly CategoryHierarchyManager $hierarchyManager)
    {
    }

    /**
     * List ad categories
     *
     * @group Ad Categories
     *
     * Fetch categories with optional filtering by parent, activation state, or search term.
     *
     * @queryParam parent_id integer Filter categories by parent identifier. Example: 1
     * @queryParam only_active boolean Return only active categories. Example: true
     * @queryParam search string Search by category name or slug. Example: vehicles
     * @queryParam advertisable_type_id integer Limit results to the given advertisable type. Example: 2
     * @queryParam per_page integer Number of results per page, up to 200. Example: 50
     * @queryParam without_pagination boolean Set to true to receive all categories without pagination. Example: false
     * @responseField icon_url string|null Public URL to the uploaded icon image, if present.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->validate([
            'parent_id' => ['nullable', 'integer', 'exists:ad_categories,id'],
            'only_active' => ['nullable', 'boolean'],
            'search' => ['nullable', 'string'],
            'advertisable_type_id' => ['nullable', 'integer', 'exists:advertisable_types,id'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:200'],
            'without_pagination' => ['nullable', 'boolean'],
        ]);

        $onlyActive = $request->boolean('only_active');
        $withoutPagination = $request->boolean('without_pagination');

        $query = AdCategory::query()
            ->when(isset($filters['parent_id']), fn ($q) => $q->where('parent_id', $filters['parent_id']))
            ->when($onlyActive, fn ($q) => $q->where('is_active', true))
            ->when(isset($filters['search']), function ($q) use ($filters) {
                $search = $filters['search'];

                $q->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->when(isset($filters['advertisable_type_id']), fn ($q) => $q->where('advertisable_type_id', $filters['advertisable_type_id']))
            ->orderBy('sort_order')
            ->orderBy('name');

        if ($withoutPagination) {
            return AdCategoryResource::collection($query->get());
        }

        $perPage = (int) ($filters['per_page'] ?? 50);

        return AdCategoryResource::collection(
            $query->paginate($perPage)->appends($request->query())
        );
    }

    /**
     * Create a category
     *
     * @group Ad Categories
     *
     * Store a new category and rebuild the hierarchy metadata.
     *
     * @contentType multipart/form-data
     * @bodyParam icon file Icon image representing the category. No-example
     * @responseField icon_url string|null Public URL to the uploaded icon image, if present.
     */
    public function store(StoreAdCategoryRequest $request): JsonResponse
    {
        $payload = Arr::except($request->validated(), ['icon']);

        /** @var AdCategory $category */
        $category = DB::transaction(function () use ($payload, $request) {
            $category = AdCategory::create($payload);
            $this->syncIcon($category, $request);
            $this->hierarchyManager->handleCreated($category);

            return $category->fresh();
        });

        return (new AdCategoryResource($category))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Show a category
     *
     * @group Ad Categories
     *
     * Retrieve details for the given category record.
     *
     * @responseField icon_url string|null Public URL to the uploaded icon image, if present.
     */
    public function show(AdCategory $adCategory): AdCategoryResource
    {
        return new AdCategoryResource($adCategory);
    }

    /**
     * Update a category
     *
     * @group Ad Categories
     *
     * Apply updates and recompute the category hierarchy when relationships change.
     *
     * @contentType multipart/form-data
     * @bodyParam icon file Icon image representing the category. No-example
     * @responseField icon_url string|null Public URL to the uploaded icon image, if present.
     */
    public function update(UpdateAdCategoryRequest $request, AdCategory $adCategory): AdCategoryResource
    {
        $payload = Arr::except($request->validated(), ['icon']);

        /** @var AdCategory $category */
        $category = DB::transaction(function () use ($adCategory, $payload, $request) {
            $adCategory->fill($payload);
            $adCategory->save();
            $this->syncIcon($adCategory, $request);

            $this->hierarchyManager->rebuildSubtree($adCategory);

            return $adCategory->fresh();
        });

        return new AdCategoryResource($category);
    }

    /**
     * Delete a category
     *
     * @group Ad Categories
     *
     * Soft delete the specified category.
     */
    public function destroy(AdCategory $adCategory): Response
    {
        $adCategory->delete();

        return response()->noContent();
    }

    private function syncIcon(AdCategory $category, Request $request): void
    {
        if (! $request->hasFile('icon')) {
            return;
        }

        $uploadedIcon = $request->file('icon');
        $mediaName = pathinfo((string) $uploadedIcon->getClientOriginalName(), PATHINFO_FILENAME);

        if ($mediaName === '') {
            $mediaName = $category->slug ?? 'category-icon';
        }

        $category->clearMediaCollection(AdCategory::COLLECTION_ICON);

        $category
            ->addMediaFromRequest('icon')
            ->usingName($mediaName)
            ->toMediaCollection(AdCategory::COLLECTION_ICON);
    }
}
