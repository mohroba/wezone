<?php

namespace Modules\Auth\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Auth\Http\Requests\ProfileUpdateRequest;
use Modules\Auth\Http\Resources\ProfileResource;

class ProfileController extends Controller
{
    /**
     * @group Auth
     * @authenticated
     *
     * Get authenticated profile
     *
     * Retrieve the current user's profile, including roles, permissions, and media links.
     *
     * @response {
     *   "success": true,
     *   "message": "Profile retrieved successfully.",
     *   "data": {
     *     "profile": {
     *       "id": 12,
     *       "first_name": "Sara",
     *       "last_name": "Rahimi",
     *       "full_name": "Sara Rahimi",
     *       "birth_date": "1994-03-18",
     *       "national_id": "1234567890",
     *       "residence_city_id": 10,
     *       "residence_province_id": 2,
     *       "user": {
     *         "id": 45,
     *         "mobile": "989123456789",
     *         "username": "sara94",
     *         "email": "sara@example.com",
     *         "roles": [
     *           "customer"
     *         ],
     *         "permissions": []
     *       },
     *       "media": {
     *         "national_id_document": "https://cdn.example.com/media/national-id.pdf",
     *         "profile_images": [
     *           {
     *             "id": "f17c6ae4-5c1a-4c44-a058-9324c4b6f8b9",
     *             "name": "avatar",
     *             "url": "https://cdn.example.com/media/avatar.jpg"
     *           }
     *         ]
     *       },
     *       "created_at": "2025-09-24T12:00:00.000000Z",
     *       "updated_at": "2025-09-25T07:00:00.000000Z"
     *     }
     *   },
     *   "meta": {}
     * }
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        $profile = $user->profile()->firstOrCreate([]);

        $profile->loadMissing('user.roles', 'user.permissions');

        return ApiResponse::success(
            'Profile retrieved successfully.',
            [
                'profile' => new ProfileResource($profile),
            ]
        );
    }

    /**
     * @group Auth
     * @authenticated
     *
     * Update profile details
     *
     * Store optional personal information for the authenticated user. Only provided fields will be saved.
     *
     * @bodyParam first_name string optional User's given name. Example: "Sara"
     * @bodyParam last_name string optional User's family name. Example: "Rahimi"
     * @bodyParam birth_date date optional Date of birth in Y-m-d format. Example: "1994-03-18"
     * @bodyParam national_id string optional National identification number. Example: "1234567890"
     * @bodyParam residence_city_id integer optional Identifier of the city where the user resides. Example: 10
     * @bodyParam residence_province_id integer optional Identifier of the province where the user resides. Example: 2
     * @response {
     *   "success": true,
     *   "message": "Profile updated successfully.",
     *   "data": {
     *     "profile": {
     *       "id": 12,
     *       "first_name": "Sara",
     *       "last_name": "Rahimi",
     *       "full_name": "Sara Rahimi",
     *       "birth_date": "1994-03-18",
     *       "national_id": "1234567890",
     *       "residence_city_id": 10,
     *       "residence_province_id": 2,
     *       "user": {
     *         "id": 45,
     *         "mobile": "989123456789",
     *         "username": "sara94",
     *         "email": "sara@example.com",
     *         "roles": [
     *           "customer"
     *         ],
     *         "permissions": []
     *       },
     *       "media": {
     *         "national_id_document": "https://cdn.example.com/media/national-id.pdf",
     *         "profile_images": [
     *           {
     *             "id": "f17c6ae4-5c1a-4c44-a058-9324c4b6f8b9",
     *             "name": "avatar",
     *             "url": "https://cdn.example.com/media/avatar.jpg"
     *           }
     *         ]
     *       },
     *       "created_at": "2025-09-24T12:00:00.000000Z",
     *       "updated_at": "2025-09-25T07:05:00.000000Z"
     *     }
     *   },
     *   "meta": {}
     * }
     * @response status=422 scenario="Validation error" {
     *   "success": false,
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "birth_date": [
     *       "The birth date is not a valid date."
     *     ]
     *   },
     *   "data": null
     * }
     */
    public function update(ProfileUpdateRequest $request): JsonResponse
    {
        $user = $request->user();
        $profile = $user->profile()->firstOrCreate([]);

        $profile->fill($request->validated());
        $profile->save();

        $profile->loadMissing('user.roles', 'user.permissions');

        return ApiResponse::success(
            'Profile updated successfully.',
            [
                'profile' => new ProfileResource($profile),
            ]
        );
    }
}
