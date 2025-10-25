<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Modules\User\Http\Requests\BlockedUsersRequest;
use Modules\User\Http\Requests\BlockUserRequest;
use Modules\User\Http\Requests\UnblockUserRequest;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Models\UserBlock;
use Modules\User\Models\UserFollow;

class BlockController extends Controller
{
    public function store(BlockUserRequest $request, User $user): JsonResponse
    {
        $blocker = $request->user();

        $block = DB::transaction(function () use ($blocker, $user): UserBlock {
            $record = UserBlock::firstOrCreate([
                'blocker_id' => $blocker->id,
                'blocked_id' => $user->id,
            ]);

            UserFollow::query()
                ->where(function (Builder $query) use ($blocker, $user): void {
                    $query->where('follower_id', $blocker->id)
                        ->where('followed_id', $user->id);
                })
                ->orWhere(function (Builder $query) use ($blocker, $user): void {
                    $query->where('follower_id', $user->id)
                        ->where('followed_id', $blocker->id);
                })
                ->delete();

            return $record;
        });

        $status = $block->wasRecentlyCreated ? 201 : 200;
        $message = $block->wasRecentlyCreated
            ? __('User blocked successfully.')
            : __('User already blocked.');

        return (new UserResource($user->load('profile')))
            ->additional([
                'meta' => [
                    'message' => $message,
                ],
            ])->response()->setStatusCode($status);
    }

    public function unblock(UnblockUserRequest $request, User $user): JsonResponse
    {
        $blocker = $request->user();

        $deleted = UserBlock::query()
            ->where('blocker_id', $blocker->id)
            ->where('blocked_id', $user->id)
            ->delete();

        return response()->json([
            'meta' => [
                'message' => $deleted
                    ? __('User unblocked successfully.')
                    : __('User was not blocked.'),
            ],
        ]);
    }

    public function index(BlockedUsersRequest $request): AnonymousResourceCollection
    {
        $validated = $request->validated();
        $perPage = $validated['per_page'] ?? 15;

        $blockedUsers = $request->user()
            ->blockedUsers()
            ->with('profile')
            ->withCount(['followers', 'followings'])
            ->orderByDesc('user_blocks.created_at')
            ->paginate($perPage)
            ->withQueryString();

        return UserResource::collection($blockedUsers);
    }
}
