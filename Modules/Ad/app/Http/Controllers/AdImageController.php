<?php

namespace Modules\Ad\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Ad\Http\Requests\AdImage\ReorderAdImagesRequest;
use Modules\Ad\Http\Requests\AdImage\UpdateAdImageRequest;
use Modules\Ad\Http\Requests\AdImage\UploadAdImagesRequest;
use Modules\Ad\Http\Resources\AdImageResource;
use Modules\Ad\Models\Ad;
use Modules\Ad\Services\AdImageManager;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AdImageController extends Controller
{
    public function __construct(private readonly AdImageManager $imageManager)
    {
    }

    public function index(Request $request, Ad $ad): JsonResponse
    {
        $this->authorizeAdAccess($request, $ad);

        return AdImageResource::collection($this->imageManager->list($ad))
            ->additional(['meta' => ['message' => 'Images retrieved successfully.']])
            ->response();
    }

    public function store(UploadAdImagesRequest $request, Ad $ad): JsonResponse
    {
        $this->authorizeAdAccess($request, $ad);

        $images = $this->imageManager->upload($ad, $request->validated('images', []));

        return AdImageResource::collection($images)
            ->additional(['meta' => ['message' => 'Images uploaded successfully.']])
            ->response();
    }

    public function update(UpdateAdImageRequest $request, Ad $ad, Media $media): JsonResponse
    {
        $this->authorizeAdAccess($request, $ad);

        $updated = $this->imageManager->updateMetadata($ad, $media, $request->validated());

        return (new AdImageResource($updated))
            ->additional(['meta' => ['message' => 'Image metadata updated successfully.']])
            ->response();
    }

    public function destroy(Request $request, Ad $ad, Media $media): JsonResponse
    {
        $this->authorizeAdAccess($request, $ad);

        $this->imageManager->delete($ad, $media);

        return response()->json([
            'data' => [],
            'meta' => ['message' => 'Image deleted successfully.'],
        ]);
    }

    public function reorder(ReorderAdImagesRequest $request, Ad $ad): JsonResponse
    {
        $this->authorizeAdAccess($request, $ad);

        $images = $this->imageManager->reorder($ad, $request->validated('order', []));

        return AdImageResource::collection($images)
            ->additional(['meta' => ['message' => 'Image order updated successfully.']])
            ->response();
    }

    private function authorizeAdAccess(Request $request, Ad $ad): void
    {
        $user = $request->user();

        $isAuthorized = $user !== null
            && (
                $user->getKey() === $ad->user_id
                || $user->hasRole('admin')
                || $user->can('ad.report.manage')
            );

        abort_unless($isAuthorized, Response::HTTP_FORBIDDEN, 'You are not authorized to manage images for this ad.');
    }
}
