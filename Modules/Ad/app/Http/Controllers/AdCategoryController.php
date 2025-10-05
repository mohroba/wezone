<?php

namespace Modules\Ad\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
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
     * @queryParam per_page integer Number of results per page, up to 200. Example: 50
     * @queryParam without_pagination boolean Set to true to receive all categories without pagination. Example: false
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = AdCategory::query()
            ->when($request->filled('parent_id'), fn ($q) => $q->where('parent_id', $request->input('parent_id')))
            ->when($request->boolean('only_active'), fn ($q) => $q->where('is_active', true))
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->input('search');

                $q->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->orderBy('sort_order')
            ->orderBy('name');

        if ($request->boolean('without_pagination')) {
            return AdCategoryResource::collection($query->get());
        }

        $perPage = (int) min($request->integer('per_page', 50), 200);

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
     */
    public function store(StoreAdCategoryRequest $request): JsonResponse
    {
        $payload = $request->validated();

        /** @var AdCategory $category */
        $category = DB::transaction(function () use ($payload) {
            $category = AdCategory::create($payload);
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
     */
    public function update(UpdateAdCategoryRequest $request, AdCategory $adCategory): AdCategoryResource
    {
        $payload = $request->validated();

        /** @var AdCategory $category */
        $category = DB::transaction(function () use ($adCategory, $payload) {
            $adCategory->fill($payload);
            $adCategory->save();

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
}
