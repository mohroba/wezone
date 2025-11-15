<?php

namespace Modules\Ad\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Ad\Models\Ad;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @mixin Media
 */
class AdImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Media $media */
        $media = $this->resource;

        return [
            'id' => $media->id,
            'url' => $media->getUrl(),
            'thumb_url' => $media->hasGeneratedConversion(Ad::CONVERSION_THUMB)
                ? $media->getUrl(Ad::CONVERSION_THUMB)
                : $media->getUrl(),
            'alt' => $media->getCustomProperty('alt'),
            'caption' => $media->getCustomProperty('caption'),
            'display_order' => $this->displayOrder($media),
            'created_at' => $media->created_at?->toISOString(),
            'updated_at' => $media->updated_at?->toISOString(),
        ];
    }

    private function displayOrder(Media $media): int
    {
        $order = $media->getCustomProperty('display_order');

        return is_numeric($order) ? (int) $order : 0;
    }
}
