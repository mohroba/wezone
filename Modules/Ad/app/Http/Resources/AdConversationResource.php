<?php

declare(strict_types=1);

namespace Modules\Ad\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdConversation;

/**
 * @mixin AdConversation
 */
class AdConversationResource extends JsonResource
{
    public function toArray($request): array
    {
        $ad = $this->whenLoaded('ad');
        $seller = $this->whenLoaded('seller');
        $buyer = $this->whenLoaded('buyer');

        return [
            'id' => $this->id,
            'ad' => $ad ? [
                'id' => $ad->id,
                'title' => $ad->title,
                'slug' => $ad->slug,
                'thumbnail' => $ad->getFirstMediaUrl(Ad::COLLECTION_IMAGES, Ad::CONVERSION_THUMB),
            ] : null,
            'seller' => $seller ? [
                'id' => $seller->id,
                'username' => $seller->username,
            ] : null,
            'buyer' => $buyer ? [
                'id' => $buyer->id,
                'username' => $buyer->username,
            ] : null,
            'last_message_at' => $this->last_message_at,
            'unread_messages_count' => (int) ($this->unread_messages_count ?? 0),
            'last_message' => $this->whenLoaded('lastMessage', fn () => new AdMessageResource($this->lastMessage)),
            'messages' => AdMessageResource::collection($this->whenLoaded('messages')),
        ];
    }
}
