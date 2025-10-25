<?php

declare(strict_types=1);

namespace Modules\Ad\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Ad\Http\Resources\AdResource;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdLike;

class AdLikeController extends Controller
{
    /**
     * @group Ads
     * @authenticated
     *
     * Like an ad
     */
    public function store(Request $request, Ad $ad): JsonResponse
    {
        $user = $request->user('api');

        if ($ad->user->isBlocking($user) || $user->isBlocking($ad->user)) {
            abort(403, 'You are not permitted to interact with this user.');
        }

        DB::transaction(function () use ($ad, $user): void {
            AdLike::query()->firstOrCreate([
                'ad_id' => $ad->getKey(),
                'user_id' => $user->getKey(),
            ]);

            $ad->update(['like_count' => $ad->likes()->count()]);
        });

        return (new AdResource($ad->fresh(['categories', 'advertisable', 'media'])))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * @group Ads
     * @authenticated
     *
     * Remove like from an ad
     */
    public function destroy(Request $request, Ad $ad): JsonResponse
    {
        $user = $request->user('api');

        DB::transaction(function () use ($ad, $user): void {
            AdLike::query()
                ->where('ad_id', $ad->getKey())
                ->where('user_id', $user->getKey())
                ->delete();

            $ad->update(['like_count' => $ad->likes()->count()]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Ad unliked successfully.',
        ]);
    }
}
