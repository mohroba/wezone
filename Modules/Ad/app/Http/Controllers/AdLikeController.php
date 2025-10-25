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
