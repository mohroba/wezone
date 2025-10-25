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
    public function index(Ad $ad): AnonymousResourceCollection
    {
        $comments = $ad->comments()
            ->root()
            ->with(['user', 'repliesRecursive'])
            ->orderBy('created_at')
            ->get();

        return AdCommentResource::collection($comments);
    }

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
