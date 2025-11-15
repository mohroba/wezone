<?php

namespace Modules\Ad\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdLike;

class AdLikeController extends Controller
{
    /**
     * Toggle the like state for an ad.
     *
     * @group Ads
     * @subgroup Engagement
     * @authenticated
     *
     * @urlParam ad integer required The identifier of the ad to like or unlike.
     *
     * @response 200 scenario="Liked" {
     *   "data": {
     *     "liked": true,
     *     "like_count": 34
     *   }
     * }
     *
     * @response 200 scenario="Unliked" {
     *   "data": {
     *     "liked": false,
     *     "like_count": 33
     *   }
     * }
     */
    public function toggle(Request $request, Ad $ad): JsonResponse
    {
        $user = $request->user();

        [$liked, $likeCount] = DB::transaction(function () use ($user, $ad) {
            /** @var Ad $lockedAd */
            $lockedAd = Ad::query()
                ->whereKey($ad->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $likeQuery = AdLike::query()
                ->where('ad_id', $lockedAd->getKey())
                ->where('user_id', $user->getAuthIdentifier())
                ->lockForUpdate();

            $existingLike = $likeQuery->first();

            if ($existingLike !== null) {
                $existingLike->delete();
                $liked = false;
            } else {
                AdLike::create([
                    'ad_id' => $lockedAd->getKey(),
                    'user_id' => $user->getAuthIdentifier(),
                ]);
                $liked = true;
            }

            $likeCount = AdLike::query()
                ->where('ad_id', $lockedAd->getKey())
                ->count();

            $lockedAd->forceFill(['like_count' => $likeCount])->save();

            return [$liked, $likeCount];
        });

        return response()->json([
            'data' => [
                'liked' => $liked,
                'like_count' => $likeCount,
            ],
        ]);
    }
}
