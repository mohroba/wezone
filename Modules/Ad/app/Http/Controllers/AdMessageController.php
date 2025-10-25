<?php

namespace Modules\Ad\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Ad\Http\Requests\Conversation\StoreAdMessageRequest;
use Modules\Ad\Http\Resources\AdMessageResource;
use Modules\Ad\Models\AdConversation;

class AdMessageController extends Controller
{
    public function index(Request $request, AdConversation $conversation): AnonymousResourceCollection
    {
        /** @var User $user */
        $user = $request->user();

        if (!$conversation->isVisibleFor($user)) {
            throw new ModelNotFoundException('Conversation not found.');
        }

        $perPage = (int) min($request->integer('per_page', 50), 200);

        $messages = $conversation->messages()
            ->with('sender:id,username')
            ->orderBy('created_at')
            ->paginate($perPage)
            ->appends($request->query());

        return AdMessageResource::collection($messages);
    }

    public function store(StoreAdMessageRequest $request, AdConversation $conversation): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        if (!$conversation->isParticipant($user)) {
            throw new ModelNotFoundException('Conversation not found.');
        }

        $message = DB::transaction(function () use ($conversation, $user, $request) {
            $conversation->ensureParticipant($user);

            $conversation->participants()->pluck('users.id')->each(function (int $participantId) use ($conversation): void {
                $conversation->participants()->updateExistingPivot($participantId, [
                    'deleted_at' => null,
                    'updated_at' => now(),
                ]);
            });

            return $conversation->messages()->create([
                'user_id' => $user->getKey(),
                'body' => $request->string('message')->toString(),
            ])->load('sender:id,username');
        });

        return (new AdMessageResource($message))->response()->setStatusCode(Response::HTTP_CREATED);
    }
}
