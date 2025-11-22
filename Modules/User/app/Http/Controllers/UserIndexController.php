<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\User\Http\Requests\UserIndexRequest;
use Modules\User\Http\Resources\UserResource;

class UserIndexController extends Controller
{
    /**
     * Search users.
     *
     * @group Users
     */
    public function index(UserIndexRequest $request): AnonymousResourceCollection
    {
        $validated = $request->validated();
        $perPage = $validated['per_page'] ?? 15;

        $query = User::query()
            ->with('profile')
            ->withCount(['followers', 'followings']);

        if (!empty($validated['follower_id'])) {
            $query->whereHas('followers', function (Builder $relation) use ($validated): void {
                $relation->where('follower_id', $validated['follower_id']);
            });
        }

        if (!empty($validated['email'])) {
            $query->where('email', 'like', '%' . $validated['email'] . '%');
        }

        if (!empty($validated['mobile'])) {
            $query->where('mobile', 'like', '%' . $validated['mobile'] . '%');
        }

        if (!empty($validated['username'])) {
            $query->where('username', 'like', '%' . $validated['username'] . '%');
        }

        $users = $query->paginate($perPage)->withQueryString();

        return UserResource::collection($users);
    }
}
