<?php

namespace Modules\Ad\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Modules\Ad\Http\Requests\AdComment\StoreAdCommentRequest;
use Modules\Ad\Http\Resources\AdCommentResource;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdComment;

class AdCommentController
{
    /**
     * List comments for an ad.
     *
     * @group Ads
     * @subgroup Comments
     *
     * @urlParam ad integer required The identifier of the ad whose comments should be listed.
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 198,
     *       "body": "Is this still available?",
     *       "user": {
     *         "id": 12,
     *         "username": "buyer42"
     *       },
     *       "replies": []
     *     }
     *   ]
     * }
     */
    public function index(Ad $ad): AnonymousResourceCollection
    {
        $comments = $ad->comments()
            ->root()
            ->with(['user', 'repliesRecursive'])
            ->orderBy('created_at')
            ->get();

        return AdCommentResource::collection($comments);
    }

    /**
     * Create a comment on an ad.
     *
     * @group Ads
     * @subgroup Comments
     * @authenticated
     *
     * @urlParam ad integer required The identifier of the ad to comment on.
     * @bodyParam body string required The message to publish as a comment. Example: "Can you share more pictures?"
     * @bodyParam parent_id integer The parent comment identifier when replying to another comment. Example: 197
     *
     * @response 201 {
     *   "data": {
     *     "id": 199,
     *     "body": "Can you share more pictures?",
     *     "user": {
     *       "id": 15,
     *       "username": "interested-buyer"
     *     },
     *     "replies": []
     *   }
     * }
     */
    public function store(StoreAdCommentRequest $request, Ad $ad): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user('api');

        $validated = $request->validated();

        $payload = [
            'body' => $validated['body'],
            'user_id' => $user->getKey(),
        ];

        if (array_key_exists('parent_id', $validated) && $validated['parent_id'] !== null) {
            $payload['parent_id'] = (int) $validated['parent_id'];
        }

        /** @var AdComment $comment */
        $comment = $ad->comments()->create($payload);

        $comment->load(['user', 'repliesRecursive']);

        return (new AdCommentResource($comment))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
