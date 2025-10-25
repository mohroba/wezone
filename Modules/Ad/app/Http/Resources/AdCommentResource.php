<?php

namespace Modules\Ad\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

/**
 * @mixin \Modules\Ad\Models\AdComment
 */
class AdCommentResource extends JsonResource
{
    public function toArray($request): array
    {
        $replies = $this->whenLoaded('repliesRecursive', function () {
            return AdCommentResource::collection($this->repliesRecursive);
        });

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
}
