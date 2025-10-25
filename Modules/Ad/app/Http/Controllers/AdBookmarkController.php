<?php

declare(strict_types=1);

namespace Modules\Ad\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Modules\Ad\Http\Resources\AdResource;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdFavorite;

class AdBookmarkController extends Controller
{
    /**
     * @group Ads
     * @authenticated
     *
     * Bookmark an ad
     */
    public function store(Request $request, Ad $ad): JsonResponse
    {
        $user = $request->user('api');

        if ($ad->user->isBlocking($user) || $user->isBlocking($ad->user)) {
            abort(403, 'You are not permitted to interact with this user.');
        }

        DB::transaction(function () use ($ad, $user): void {
            AdFavorite::query()->firstOrCreate([
                'ad_id' => $ad->getKey(),
                'user_id' => $user->getKey(),
            ]);

            $ad->update(['favorite_count' => $ad->favorites()->count()]);
        });

        return (new AdResource($ad->fresh(['categories', 'advertisable', 'media'])))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * @group Ads
     * @authenticated
     *
     * Remove ad bookmark
     */
    public function destroy(Request $request, Ad $ad): JsonResponse
    {
        $user = $request->user('api');

        DB::transaction(function () use ($ad, $user): void {
            AdFavorite::query()
                ->where('ad_id', $ad->getKey())
                ->where('user_id', $user->getKey())
                ->delete();

            $ad->update(['favorite_count' => $ad->favorites()->count()]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Bookmark removed successfully.',
        ]);
    }

    /**
     * @group Ads
     * @authenticated
     *
     * List bookmarked ads
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user('api');

        $favorites = AdFavorite::query()
            ->where('user_id', $user->getKey())
            ->with(['ad.categories', 'ad.advertisable', 'ad.media'])
            ->latest()
            ->paginate((int) min($request->integer('per_page', 15), 50));

        return AdResource::collection(
            $favorites->through(static fn (AdFavorite $favorite) => $favorite->ad)
        );
    }
}
