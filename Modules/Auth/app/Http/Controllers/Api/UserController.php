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
