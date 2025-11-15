<?php

namespace Modules\Ad\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Modules\Ad\Models\Ad;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AdImageManager
{
    /**
     * Retrieve the ordered media items for the provided ad.
     */
    public function list(Ad $ad): Collection
    {
        return $this->sortedMedia($ad);
    }

    /**
     * Persist uploaded images and return the updated ordered collection.
     *
     * @param array<int, array<string, mixed>> $images
     */
    public function upload(Ad $ad, array $images): Collection
    {
        $maxDisplayOrder = $this->maxDisplayOrder($ad);

        foreach ($images as $image) {
            $displayOrder = array_key_exists('display_order', $image) && $image['display_order'] !== null
                ? (int) $image['display_order']
                : ++$maxDisplayOrder;

            $maxDisplayOrder = max($maxDisplayOrder, $displayOrder);

            $ad->addMedia($image['file'])
                ->withCustomProperties($this->buildCustomProperties($image, $displayOrder))
                ->toMediaCollection(Ad::COLLECTION_IMAGES);
        }

        $this->syncOrderColumn($ad);

        return $this->list($ad);
    }

    /**
     * Update image metadata and return the refreshed media item.
     *
     * @param array<string, mixed> $attributes
     */
    public function updateMetadata(Ad $ad, Media $media, array $attributes): Media
    {
        $media = $this->ensureMediaBelongsToAd($ad, $media);

        if (array_key_exists('alt', $attributes)) {
            $media->setCustomProperty('alt', $attributes['alt']);
        }

        if (array_key_exists('caption', $attributes)) {
            $media->setCustomProperty('caption', $attributes['caption']);
        }

        if (array_key_exists('display_order', $attributes) && $attributes['display_order'] !== null) {
            $media->setCustomProperty('display_order', (int) $attributes['display_order']);
        } elseif (array_key_exists('display_order', $attributes)) {
            $media->setCustomProperty('display_order', 0);
        }

        $media->save();

        $this->syncOrderColumn($ad);

        return $media->fresh();
    }

    /**
     * Delete an image from the ad.
     */
    public function delete(Ad $ad, Media $media): void
    {
        $media = $this->ensureMediaBelongsToAd($ad, $media);
        $media->delete();

        $this->syncOrderColumn($ad);
    }

    /**
     * Reorder the provided media identifiers and return the new sorted list.
     *
     * @param array<int, array{media_id:int, display_order:int}> $order
     */
    public function reorder(Ad $ad, array $order): Collection
    {
        $mediaItems = $this->fetchMedia($ad)->keyBy('id');

        foreach ($order as $item) {
            $media = $mediaItems->get((int) $item['media_id']);

            if (! $media) {
                throw (new ModelNotFoundException())->setModel(Media::class, [$item['media_id']]);
            }

            $media->setCustomProperty('display_order', (int) $item['display_order']);
            $media->save();
        }

        $this->syncOrderColumn($ad);

        return $this->list($ad);
    }

    private function sortedMedia(Ad $ad): Collection
    {
        return $this->fetchMedia($ad)
            ->sortBy(function (Media $media) {
                return [
                    $this->resolveDisplayOrder($media),
                    $media->order_column ?? 0,
                    $media->id,
                ];
            })
            ->values();
    }

    private function syncOrderColumn(Ad $ad): void
    {
        $sorted = $this->sortedMedia($ad);

        if ($sorted->isEmpty()) {
            return;
        }

        Media::setNewOrder($sorted->pluck('id')->all());
    }

    private function ensureMediaBelongsToAd(Ad $ad, Media $media): Media
    {
        if (
            (int) $media->model_id !== (int) $ad->getKey()
            || $media->model_type !== $ad->getMorphClass()
            || $media->collection_name !== Ad::COLLECTION_IMAGES
        ) {
            throw (new ModelNotFoundException())->setModel(Media::class, [$media->getKey()]);
        }

        return $media;
    }

    private function resolveDisplayOrder(Media $media): int
    {
        $order = $media->getCustomProperty('display_order');

        return is_numeric($order) ? (int) $order : 0;
    }

    /**
     * @param array<string, mixed> $image
     * @return array<string, mixed>
     */
    private function buildCustomProperties(array $image, int $displayOrder): array
    {
        $properties = [
            'display_order' => $displayOrder,
        ];

        if (array_key_exists('alt', $image)) {
            $properties['alt'] = $image['alt'];
        }

        if (array_key_exists('caption', $image)) {
            $properties['caption'] = $image['caption'];
        }

        return $properties;
    }

    private function maxDisplayOrder(Ad $ad): int
    {
        $max = $this->fetchMedia($ad)
            ->map(fn (Media $media) => $this->resolveDisplayOrder($media))
            ->max();

        return $max ?? -1;
    }

    private function fetchMedia(Ad $ad): Collection
    {
        return $ad->media()
            ->where('collection_name', Ad::COLLECTION_IMAGES)
            ->get();
    }
}
