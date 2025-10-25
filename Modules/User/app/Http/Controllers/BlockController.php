<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    /**
     * @group Users
     * @authenticated
     *
     * List blocked users
     */
    public function index(Request $request)
    {
        $blocked = $request->user('api')
            ->blockedUsers()
            ->with('profile')
            ->paginate((int) min($request->integer('per_page', 20), 50));

        return $blocked->through(function (User $user) {
            return [
                'id' => $user->id,
                'username' => $user->username,
                'full_name' => $user->profile?->full_name,
                'blocked_at' => $user->pivot?->blocked_at,
            ];
        });
    }

    /**
     * @group Users
     * @authenticated
     *
     * Block a user
     */
    public function store(Request $request, User $user): JsonResponse
    {
        $authUser = $request->user('api');

        if ($authUser->is($user)) {
            abort(422, 'You cannot block yourself.');
        }

        $authUser->blockedUsers()->syncWithoutDetaching([
            $user->getKey() => ['blocked_at' => now()],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User blocked successfully.',
        ], 201);
    }

    /**
     * @group Users
     * @authenticated
     *
     * Unblock a user
     */
    public function destroy(Request $request, User $user): JsonResponse
    {
        $request->user('api')->blockedUsers()->detach($user->getKey());

        return response()->json([
            'success' => true,
            'message' => 'User unblocked successfully.',
        ]);
    }
}
