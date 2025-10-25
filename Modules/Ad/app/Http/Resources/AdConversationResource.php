<?php

namespace Modules\Ad\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/** @mixin \Modules\Ad\Models\AdConversation */
class AdConversationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'ad_id' => $this->ad_id,
            'initiated_by' => $this->initiated_by,
            'ad' => $this->whenLoaded('ad', function () {
                $ad = $this->ad;

                return [
                    'id' => $ad->id,
                    'title' => $ad->title,
                    'owner_id' => $ad->user_id,
                    'owner' => $ad->relationLoaded('user') ? [
                        'id' => $ad->user->id,
                        'username' => $ad->user->username,
                    ] : null,
                ];
            }),
            'participants' => $this->whenLoaded('participants', function () {
                return $this->participants->map(function ($participant) {
                    return [
                        'id' => $participant->id,
                        'username' => $participant->username,
                        'deleted_at' => $participant->pivot?->deleted_at
                            ? Carbon::parse($participant->pivot->deleted_at)->toISOString()
                            : null,
                    ];
                })->values();
            }),
            'latest_message' => $this->whenLoaded('latestMessage', function () use ($request) {
                return (new AdMessageResource($this->latestMessage))->toArray($request);
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
