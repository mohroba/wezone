<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Carbon;
use Modules\User\Http\Requests\FollowUserRequest;
use Modules\User\Http\Requests\UnfollowUserRequest;
use Modules\User\Http\Requests\UserFollowersRequest;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Models\UserFollow;

class FollowController extends Controller
{
    public function store(FollowUserRequest $request, User $user): JsonResponse
    {
        $follower = $request->user();

        $follow = UserFollow::firstOrCreate([
            'follower_id' => $follower->id,
            'followed_id' => $user->id,
        ]);

        $status = $follow->wasRecentlyCreated ? 201 : 200;

        return (new UserResource($user->load('profile')))
            ->additional([
                'meta' => [
                    'message' => $follow->wasRecentlyCreated ? __('Followed successfully.') : __('Already following user.'),
                ],
            ])->response()->setStatusCode($status);
    }

    public function destroy(UnfollowUserRequest $request, User $user): JsonResponse
    {
        $follower = $request->user();

        $deleted = UserFollow::query()
            ->where('follower_id', $follower->id)
            ->where('followed_id', $user->id)
            ->delete();

        return response()->json([
            'meta' => [
                'message' => $deleted ? __('Unfollowed successfully.') : __('You were not following this user.'),
            ],
        ]);
    }

    public function index(UserFollowersRequest $request, User $user): AnonymousResourceCollection
    {
        $validated = $request->validated();
        $perPage = $validated['per_page'] ?? 15;

        $query = $user->followers()
            ->with('profile')
            ->withCount(['followers', 'followings'])
            ->orderByDesc('user_follows.created_at');

        if (isset($validated['followed_from'])) {
            $query->wherePivot('created_at', '>=', Carbon::parse($validated['followed_from']));
        }

        if (isset($validated['followed_to'])) {
            $query->wherePivot('created_at', '<=', Carbon::parse($validated['followed_to'])->endOfDay());
        }

        $followers = $query->paginate($perPage)->withQueryString();

        return UserResource::collection($followers);
    }
}
