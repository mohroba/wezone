<?php

declare(strict_types=1);

namespace Modules\Ad\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Ad\Http\Requests\Comment\StoreAdCommentRequest;
use Modules\Ad\Http\Resources\AdCommentResource;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdComment;

class AdCommentController extends Controller
{
    /**
     * @group Ads
     *
     * List comments for an ad
     */
    public function index(Request $request, Ad $ad): AnonymousResourceCollection
    {
        $comments = $ad->comments()
            ->with(['user.profile', 'replies.user.profile'])
            ->whereNull('parent_id')
            ->latest()
            ->paginate((int) min($request->integer('per_page', 15), 50));

        return AdCommentResource::collection($comments);
    }

    /**
     * @group Ads
     * @authenticated
     *
     * Add a comment to an ad
     */
    public function store(StoreAdCommentRequest $request, Ad $ad): JsonResponse
    {
        $user = $request->user('api');

        if ($ad->user->isBlocking($user) || $user->isBlocking($ad->user)) {
            abort(403, 'You are not permitted to interact with this user.');
        }

        $comment = $ad->comments()->create([
            'user_id' => $user->getKey(),
            'body' => $request->validated('body'),
        ]);

        $comment->load(['user.profile']);

        return (new AdCommentResource($comment))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * @group Ads
     * @authenticated
     *
     * Reply to a comment
     */
    public function reply(StoreAdCommentRequest $request, Ad $ad, AdComment $comment): JsonResponse
    {
        $user = $request->user('api');

        if ($comment->ad_id !== $ad->getKey()) {
            abort(404);
        }

        if ($ad->user->isBlocking($user) || $user->isBlocking($ad->user)) {
            abort(403, 'You are not permitted to interact with this user.');
        }

        $reply = $ad->comments()->create([
            'user_id' => $user->getKey(),
            'body' => $request->validated('body'),
            'parent_id' => $comment->getKey(),
        ]);

        $reply->load(['user.profile', 'parent']);

        return (new AdCommentResource($reply))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * @group Ads
     * @authenticated
     *
     * Delete a comment
     */
    public function destroy(Request $request, AdComment $comment): JsonResponse
    {
        $user = $request->user('api');

        if ($comment->user_id !== $user->getKey() && $comment->ad->user_id !== $user->getKey()) {
            abort(403, 'You are not allowed to remove this comment.');
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment removed successfully.',
        ]);
    }
}
