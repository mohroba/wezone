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
