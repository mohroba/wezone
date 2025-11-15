<?php

namespace Modules\Ad\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Modules\Ad\Http\Resources\AdFavoriteResource;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdFavorite;

class AdFavoriteController extends Controller
{
    /**
     * List bookmarked ads for the authenticated user.
     *
     * @group Ads
     * @subgroup Engagement
     * @authenticated
     *
     * @queryParam per_page int The number of bookmarks per page when paginating (1-100). Example: 20
     * @queryParam without_pagination boolean Return all bookmarks without pagination. Example: true
     *
     * @response 200 scenario="Paginated" {
     *   "data": [
     *     {
     *       "id": 15,
     *       "ad_id": 84,
     *       "ad": {
     *         "id": 84,
     *         "title": "Vintage bicycle"
     *       }
     *     }
     *   ],
     *   "links": {
     *     "first": "https://example.com/api/ads/bookmarks?page=1",
     *     "last": "https://example.com/api/ads/bookmarks?page=1",
     *     "prev": null,
     *     "next": null
     *   },
     *   "meta": {
     *     "current_page": 1,
     *     "from": 1,
     *     "last_page": 1,
     *     "path": "https://example.com/api/ads/bookmarks",
     *     "per_page": 20,
     *     "to": 1,
     *     "total": 1
     *   }
     * }
     *
     * @response 200 scenario="Without pagination" {
     *   "data": [
     *     {
     *       "id": 15,
     *       "ad_id": 84,
     *       "ad": {
     *         "id": 84,
     *         "title": "Vintage bicycle"
     *       }
     *     }
     *   ]
     * }
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();

        $perPage = (int) max(1, min($request->integer('per_page', 15), 100));

        $favoritesQuery = AdFavorite::query()
            ->where('user_id', $user->getAuthIdentifier())
            ->with(['ad' => function ($query): void {
                $query->with(['categories', 'advertisable']);
            }])
            ->latest();

        if ($request->boolean('without_pagination')) {
            return AdFavoriteResource::collection($favoritesQuery->get());
        }

        /** @var LengthAwarePaginator $paginator */
        $paginator = $favoritesQuery->paginate($perPage)->appends($request->query());

        return AdFavoriteResource::collection($paginator);
    }

    /**
     * Toggle the bookmark state for an ad.
     *
     * @group Ads
     * @subgroup Engagement
     * @authenticated
     *
     * @urlParam ad integer required The identifier of the ad to bookmark or un-bookmark.
     *
     * @response 200 scenario="Bookmarked" {
     *   "data": {
     *     "favorited": true,
     *     "favorite_count": 12
     *   }
     * }
     *
     * @response 200 scenario="Removed from bookmarks" {
     *   "data": {
     *     "favorited": false,
     *     "favorite_count": 11
     *   }
     * }
     */
    public function toggle(Request $request, Ad $ad): JsonResponse
    {
        $user = $request->user();

        [$favorited, $favoriteCount] = DB::transaction(function () use ($user, $ad) {
            /** @var Ad $lockedAd */
            $lockedAd = Ad::query()
                ->whereKey($ad->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $favoriteQuery = AdFavorite::query()
                ->where('ad_id', $lockedAd->getKey())
                ->where('user_id', $user->getAuthIdentifier())
                ->lockForUpdate();

            $existingFavorite = $favoriteQuery->first();

            if ($existingFavorite !== null) {
                $existingFavorite->delete();
                $favorited = false;
            } else {
                AdFavorite::create([
                    'ad_id' => $lockedAd->getKey(),
                    'user_id' => $user->getAuthIdentifier(),
                ]);
                $favorited = true;
            }

            $favoriteCount = AdFavorite::query()
                ->where('ad_id', $lockedAd->getKey())
                ->count();

            $lockedAd->forceFill(['favorite_count' => $favoriteCount])->save();

            return [$favorited, $favoriteCount];
        });

        return response()->json([
            'data' => [
                'favorited' => $favorited,
                'favorite_count' => $favoriteCount,
            ],
        ]);
    }
}
