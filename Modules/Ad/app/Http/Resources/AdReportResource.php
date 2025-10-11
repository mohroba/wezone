<?php

namespace Modules\Ad\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \Modules\Ad\Models\AdReport */
class AdReportResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'ad_id' => $this->ad_id,
            'reason_code' => $this->reason_code,
            'description' => $this->description,
            'status' => $this->status,
            'reported_by' => $this->reported_by,
            'handled_by' => $this->handled_by,
            'handled_at' => $this->handled_at?->toISOString(),
            'resolution_notes' => $this->resolution_notes,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'ad' => $this->whenLoaded('ad', function () {
                return [
                    'id' => $this->ad->id,
                    'title' => $this->ad->title,
                    'slug' => $this->ad->slug,
                ];
            }),
            'reporter' => $this->whenLoaded('reporter', function () {
                return [
                    'id' => $this->reporter->id,
                    'username' => $this->reporter->username,
                    'email' => $this->reporter->email,
                ];
            }),
            'handler' => $this->whenLoaded('handler', function () {
                return [
                    'id' => $this->handler->id,
                    'username' => $this->handler->username,
                    'email' => $this->handler->email,
                ];
            }),
        ];
    }
}
