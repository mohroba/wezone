<?php

declare(strict_types=1);

namespace Modules\Ad\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Ad\Models\AdMessage;

/**
 * @mixin AdMessage
 */
class AdMessageResource extends JsonResource
{
    public function toArray($request): array
    {
        $sender = $this->whenLoaded('sender');

        return [
            'id' => $this->id,
            'body' => $this->body,
            'is_read' => (bool) $this->is_read,
            'created_at' => $this->created_at,
            'sender' => $sender ? [
                'id' => $sender->id,
                'username' => $sender->username,
                'full_name' => $sender->profile?->full_name,
            ] : null,
        ];
    }
}
