<?php

namespace Modules\Ad\Http\Resources;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

/**
 * @mixin \Modules\Ad\Models\AdComment
 */
class AdCommentThreadResource extends JsonResource
{
    public function __construct($resource, private int $maxDepth = 3, private int $currentDepth = 1)
    {
        parent::__construct($resource);
    }

    public static function collectionWithDepth($resource, int $maxDepth): AnonymousResourceCollection
    {
        $threaded = collect($resource)->map(
            static fn ($comment) => new self($comment, $maxDepth, 1)
        );

        return new AnonymousResourceCollection($threaded, static::class);
    }

    public function toArray($request): array
    {
        $replies = $this->buildReplies($request);

        return [
            'id' => $this->id,
            'ad_id' => $this->ad_id,
            'parent_id' => $this->parent_id,
            'body' => $this->body,
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                ];
            }),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'replies' => $replies instanceof MissingValue ? [] : $replies,
        ];
    }

    private function buildReplies($request)
    {
        $isLastAllowedLevel = $this->currentDepth >= $this->maxDepth;

        if ($isLastAllowedLevel) {
            return [];
        }

        $replies = $this->whenLoaded('replies', function () use ($request) {
            return $this->replies->map(function ($reply) use ($request) {
                return (new self($reply, $this->maxDepth, $this->currentDepth + 1))->toArray($request);
            });
        }, []);

        return $replies;
    }
}
