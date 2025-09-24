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
