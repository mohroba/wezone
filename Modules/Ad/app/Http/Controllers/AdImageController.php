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

    /**
     * List ad images
     *
     * Retrieve every media item currently attached to the specified ad.
     *
     * @group Ad Images
     * @authenticated
     *
     * @urlParam ad integer required The ID of the ad whose images should be listed.
     * @responseField data[].id integer Media identifier.
     * @responseField data[].url string Original image URL.
     * @responseField data[].thumb_url string Thumbnail URL.
     */
    public function index(Request $request, Ad $ad): JsonResponse
    {
        $this->authorizeAdAccess($request, $ad);

        return AdImageResource::collection($this->imageManager->list($ad))
            ->additional(['meta' => ['message' => 'Images retrieved successfully.']])
            ->response();
    }

    /**
     * Upload ad images
     *
     * Attach one or more images to the specified ad.
     *
     * @group Ad Images
     * @authenticated
     *
     * @urlParam ad integer required The ID of the ad receiving the images.
     * @bodyParam images array required Collection of image payloads.
     * @bodyParam images[].file file required JPEG/PNG/WEBP file (max 5 MB)
     * @bodyParam images[].alt string optional Alternative text for accessibility. Example: Front view of the car
     * @bodyParam images[].caption string optional Short caption shown in galleries. Example: Taken last week
     * @bodyParam images[].display_order integer optional Display order override. Example: 2
     */
    public function store(UploadAdImagesRequest $request, Ad $ad): JsonResponse
    {
        $this->authorizeAdAccess($request, $ad);

        $images = $this->imageManager->upload($ad, $request->validated('images', []));

        return AdImageResource::collection($images)
            ->additional(['meta' => ['message' => 'Images uploaded successfully.']])
            ->response();
    }

    /**
     * Update image metadata
     *
     * Modify the caption, alt text, or display order for an ad image.
     *
     * @group Ad Images
     * @authenticated
     *
     * @urlParam ad integer required The ID of the parent ad.
     * @urlParam media integer required The media identifier returned from uploads.
     * @bodyParam alt string optional Alternative text (max 150 chars). Example: Interior photo
     * @bodyParam caption string optional Caption displayed under the asset. Example: Dashboard close-up
     * @bodyParam display_order integer optional New numeric position. Example: 1
     */
    public function update(UpdateAdImageRequest $request, Ad $ad, Media $media): JsonResponse
    {
        $this->authorizeAdAccess($request, $ad);

        $updated = $this->imageManager->updateMetadata($ad, $media, $request->validated());

        return (new AdImageResource($updated))
            ->additional(['meta' => ['message' => 'Image metadata updated successfully.']])
            ->response();
    }

    /**
     * Delete ad image
     *
     * Remove a single media item from the specified ad.
     *
     * @group Ad Images
     * @authenticated
     *
     * @urlParam ad integer required The ID of the parent ad.
     * @urlParam media integer required The media identifier.
     */
    public function destroy(Request $request, Ad $ad, Media $media): JsonResponse
    {
        $this->authorizeAdAccess($request, $ad);

        $this->imageManager->delete($ad, $media);

        return response()->json([
            'data' => [],
            'meta' => ['message' => 'Image deleted successfully.'],
        ]);
    }

    /**
     * Reorder ad images
     *
     * Update the display order of multiple images in one request.
     *
     * @group Ad Images
     * @authenticated
     *
     * @urlParam ad integer required The ID of the parent ad.
     * @bodyParam order array required New ordering instructions.
     * @bodyParam order[].media_id integer required Media identifier to update. Example: 45
     * @bodyParam order[].display_order integer required Position to assign. Example: 0
     */
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
