<?php

declare(strict_types=1);

namespace Modules\Ad\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Ad\Models\AdComment;

/**
 * @mixin AdComment
 */
class AdCommentResource extends JsonResource
{
    public function toArray($request): array
    {
        $user = $this->whenLoaded('user');

        return [
            'id' => $this->id,
            'body' => $this->body,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'parent_id' => $this->parent_id,
            'user' => $user ? [
                'id' => $user->id,
                'username' => $user->username,
                'full_name' => $user->profile?->full_name,
            ] : null,
            'replies' => AdCommentResource::collection($this->whenLoaded('replies')),
        ];
    }
}
