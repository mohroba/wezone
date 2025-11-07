<?php

namespace Modules\Ad\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Query\Expression;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Ad\Http\Resources\AdResource;
use Modules\Ad\Models\Ad;

/**
 * @group Ads
 *
 * @subgroup Explore
 *
 * Discover ads prioritized by promotion and user interests.
 */
class AdExploreController extends Controller
{
    /**
     * Explore ads
     *
     * Retrieve a prioritized feed of ads combining promoted listings and
     * recommendations based on the authenticated user's interactions.
     *
     * @queryParam per_page integer Number of results per page, up to 50. Example: 20
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();
        $perPage = (int) min($request->integer('per_page', 20), 50);
        $now = now();

        $query = Ad::query()
            ->select('ads.*')
            ->where('status', 'published')
            ->with(['media'])
            ->with(['user' => function ($relation): void {
                $relation
                    ->with(['profile.media'])
                    ->withCount([
                        'ads as published_ads_count' => fn ($builder) => $builder->where('status', 'published'),
                        'followers',
                    ]);
            }])
            ->selectRaw(
                'CASE WHEN (ads.featured_until IS NOT NULL AND ads.featured_until > ?) OR ads.priority_score > 0 THEN 1 ELSE 0 END AS is_promoted',
                [$now]
            );

        $engagedCategoryIds = $user ? $this->resolveEngagedCategoryIds($user->getKey()) : collect();

        if ($engagedCategoryIds->isNotEmpty()) {
            $placeholders = implode(',', array_fill(0, $engagedCategoryIds->count(), '?'));
            $query->selectRaw(
                "CASE WHEN EXISTS (SELECT 1 FROM ad_category_ad WHERE ad_category_ad.ad_id = ads.id AND ad_category_ad.category_id IN ($placeholders)) THEN 1 ELSE 0 END AS is_related",
                $engagedCategoryIds->all()
            );
        } else {
            $query->selectRaw('0 AS is_related');
        }

        $ads = $query
            ->orderByDesc(new Expression('is_promoted'))
            ->orderByDesc(new Expression('is_related'))
            ->orderByDesc('featured_until')
            ->orderByDesc('priority_score')
            ->orderByDesc('published_at')
            ->paginate($perPage)
            ->appends($request->query());

        return AdResource::collection($ads);
    }

    /**
     * @return Collection<int, int>
     */
    private function resolveEngagedCategoryIds(int $userId): Collection
    {
        $liked = DB::table('ad_category_ad')
            ->join('ad_likes', 'ad_category_ad.ad_id', '=', 'ad_likes.ad_id')
            ->where('ad_likes.user_id', $userId)
            ->pluck('ad_category_ad.category_id');

        $bookmarked = DB::table('ad_category_ad')
            ->join('ad_favorites', 'ad_category_ad.ad_id', '=', 'ad_favorites.ad_id')
            ->where('ad_favorites.user_id', $userId)
            ->pluck('ad_category_ad.category_id');

        return $liked
            ->merge($bookmarked)
            ->filter()
            ->unique()
            ->values();
    }
}
