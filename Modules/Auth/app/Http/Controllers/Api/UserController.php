<?php

namespace Modules\Auth\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Auth\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    /**
     * @group Auth
     * @authenticated
     *
     * Get authenticated user
     *
     * Return the signed-in user's account record with roles and permissions.
     *
     * @response {
     *   "success": true,
     *   "message": "User retrieved successfully.",
     *   "data": {
     *     "user": {
     *       "id": 45,
     *       "mobile": "989123456789",
     *       "username": "sara94",
     *       "email": "sara@example.com",
     *       "roles": [
     *         "customer"
     *       ],
     *       "permissions": [],
     *       "created_at": "2025-09-24T12:00:00.000000Z",
     *       "updated_at": "2025-09-25T07:00:00.000000Z"
     *     }
     *   },
     *   "meta": {}
     * }
     */
    public function show(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $user->loadMissing('roles', 'permissions');

        return ApiResponse::success(
            'User retrieved successfully.',
            [
                'user' => $this->transformUser($user),
            ]
        );
    }

    /**
     * @group Auth
     * @authenticated
     *
     * Update authenticated user
     *
     * Change account-level fields for the current user. Leave fields out to keep their existing values.
     *
     * @bodyParam username string optional A unique username between 1 and 191 characters. Example: "sara94"
     * @bodyParam email string optional A unique, valid email address. Example: "sara@example.com"
     * @response {
     *   "success": true,
     *   "message": "User updated successfully.",
     *   "data": {
     *     "user": {
     *       "id": 45,
     *       "mobile": "989123456789",
     *       "username": "sara94",
     *       "email": "sara@example.com",
     *       "roles": [
     *         "customer"
     *       ],
     *       "permissions": [],
     *       "created_at": "2025-09-24T12:00:00.000000Z",
     *       "updated_at": "2025-09-25T07:10:00.000000Z"
     *     }
     *   },
     *   "meta": {}
     * }
     * @response status=422 scenario="Validation error" {
     *   "success": false,
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "username": [
     *       "The username has already been taken."
     *     ]
     *   },
     *   "data": null
     * }
     */
    public function update(UserUpdateRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $data = $request->validated();

        $user->fill($data);
        $user->save();

        $user->loadMissing('roles', 'permissions');

        return ApiResponse::success(
            'User updated successfully.',
            [
                'user' => $this->transformUser($user),
            ]
        );
    }

    private function transformUser(User $user): array
    {
        return [
            'id' => $user->id,
            'mobile' => $user->mobile,
            'username' => $user->username,
            'email' => $user->email,
            'roles' => $user->getRoleNames()->values(),
            'permissions' => $user->getPermissionNames()->values(),
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }
}
