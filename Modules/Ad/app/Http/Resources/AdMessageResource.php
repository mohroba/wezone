<?php

namespace Modules\Ad\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

/** @mixin \Modules\Ad\Models\AdMessage */
class AdMessageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'conversation_id' => $this->ad_conversation_id,
            'user_id' => $this->user_id,
            'body' => $this->type === 'text' ? $this->body : null,
            'type' => $this->type,
            'payload' => $this->payload,
            'attachment_url' => $this->when($this->hasAttachmentPayload(), function () {
                $disk = Arr::get($this->payload, 'disk');
                $path = Arr::get($this->payload, 'path');

                if (!$disk || !$path) {
                    return null;
                }

                try {
                    return Storage::disk($disk)->url($path);
                } catch (InvalidArgumentException) {
                    return null;
                }
            }),
            'sender' => $this->whenLoaded('sender', function () {
                return [
                    'id' => $this->sender->id,
                    'username' => $this->sender->username,
                ];
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }

    private function hasAttachmentPayload(): bool
    {
        if (!is_array($this->payload)) {
            return false;
        }

        return in_array($this->type, ['image', 'audio', 'video'], true)
            && Arr::get($this->payload, 'path');
    }
}
