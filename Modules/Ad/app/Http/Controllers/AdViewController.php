<?php

declare(strict_types=1);

namespace Modules\Ad\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Ad\Http\Resources\AdResource;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdView;

class AdViewController extends Controller
{
    /**
     * @group Ads
     *
     * Record an ad view
     *
     * Register a view for the given advertisement. Views from the same authenticated
     * user within the last hour are deduplicated. Guest views are deduplicated
     * using IP address and user agent within the same hour.
     *
     * @bodyParam source string optional Optional origin of the view such as "listing" or "share". Example: listing
     */
    public function store(Request $request, Ad $ad): JsonResponse
    {
        $viewer = $request->user('api');
        $ip = (string) $request->ip();
        $userAgent = (string) $request->userAgent();
        $now = Carbon::now();
        $cutoff = $now->clone()->subHour();

        $alreadyRecorded = AdView::query()
            ->when($viewer !== null, function ($query) use ($viewer, $ad, $cutoff): void {
                $query
                    ->where('ad_id', $ad->getKey())
                    ->where('viewer_id', $viewer->getKey())
                    ->where('viewed_at', '>=', $cutoff);
            }, function ($query) use ($ad, $cutoff, $ip, $userAgent): void {
                $query
                    ->where('ad_id', $ad->getKey())
                    ->where('ip_address', $ip)
                    ->where('user_agent', $userAgent)
                    ->where('viewed_at', '>=', $cutoff);
            })
            ->exists();

        if (! $alreadyRecorded) {
            DB::transaction(function () use ($ad, $viewer, $ip, $userAgent, $now): void {
                AdView::query()->create([
                    'ad_id' => $ad->getKey(),
                    'viewer_id' => $viewer?->getKey(),
                    'ip_address' => $ip,
                    'user_agent' => $userAgent,
                    'viewed_at' => $now,
                ]);

                $ad->increment('view_count');
            });
        }

        return (new AdResource($ad->fresh(['categories', 'advertisable', 'media'])))
            ->response()
            ->setStatusCode(201);
    }
}
