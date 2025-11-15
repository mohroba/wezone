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
    /**
     * Follow a user.
     * @group Users
     * @urlParam user integer required The ID of the user to follow. Example: 123
     */
    public function store(FollowUserRequest $request, $user_id): JsonResponse
    {
        $follower = $request->user();
        $user = User::find($user_id);

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $follow = UserFollow::firstOrCreate([
            'follower_id' => $follower->id,
            'followed_id' => $user->id,
        ]);

        $status = $follow->wasRecentlyCreated ? 201 : 200;

        return (new UserResource($user->load('profile')))
            ->additional([
                'meta' => [
                    'message' => $follow->wasRecentlyCreated
                        ? __('Followed successfully.')
                        : __('Already following user.'),
                ],
            ])
            ->response()
            ->setStatusCode($status);
    }


    /**
     * Unfollow a user.
     * @group Users
     * @urlParam user integer required The ID of the user to unfollow. Example: 123
     */
    public function destroy(UnfollowUserRequest $request, $user_id): JsonResponse
    {
        $follower = $request->user();
        $user = User::find($user_id);

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $deleted = UserFollow::query()
            ->where('follower_id', $follower->id)
            ->where('followed_id', $user->id)
            ->delete();

        return response()->json([
            'meta' => [
                'message' => $deleted
                    ? __('Unfollowed successfully.')
                    : __('You were not following this user.'),
            ],
        ]);
    }


    /**
     * List a user's followers.
     *
     * @group Users
     */
    public function index(UserFollowersRequest $request, $user_id): AnonymousResourceCollection
    {
        $user = User::find($user_id);

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

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
