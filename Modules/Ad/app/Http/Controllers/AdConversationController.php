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
use Modules\Ad\Http\Requests\Conversation\StoreAdConversationRequest;
use Modules\Ad\Http\Resources\AdConversationResource;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdConversation;

class AdConversationController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        /** @var User $user */
        $user = $request->user();

        $perPage = (int) min($request->integer('per_page', 20), 100);

        $conversations = AdConversation::query()
            ->visibleToUser($user)
            ->with([
                'ad:id,title,user_id',
                'ad.user:id,username',
                'participants:id,username',
                'latestMessage.sender:id,username',
            ])
            ->orderByDesc('updated_at')
            ->paginate($perPage)
            ->appends($request->query());

        return AdConversationResource::collection($conversations);
    }

    public function store(StoreAdConversationRequest $request, Ad $ad): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $recipientId = $request->recipientId($ad);

        if ($recipientId === $user->getKey()) {
            throw new ModelNotFoundException('Recipient not found.');
        }

        $participantIds = collect([$user->getKey(), $recipientId])->unique()->values()->all();

        if (count($participantIds) < 2) {
            throw new ModelNotFoundException('Recipient not found.');
        }

        /** @var AdConversation $conversation */
        $conversation = DB::transaction(function () use ($ad, $user, $participantIds, $request): AdConversation {
            $conversationQuery = AdConversation::query()->where('ad_id', $ad->getKey());

            foreach ($participantIds as $participantId) {
                $conversationQuery->whereHas('participants', fn ($query) => $query->whereKey($participantId));
            }

            $conversation = $conversationQuery->first();

            if (!$conversation instanceof AdConversation) {
                $conversation = AdConversation::create([
                    'ad_id' => $ad->getKey(),
                    'initiated_by' => $user->getKey(),
                ]);
            }

            $participants = $conversation->participants();

            foreach ($participantIds as $participantId) {
                $participants->syncWithoutDetaching([
                    $participantId => ['deleted_at' => null],
                ]);

                $participants->updateExistingPivot($participantId, [
                    'deleted_at' => null,
                    'updated_at' => now(),
                ]);
            }

            $conversation->messages()->create([
                'user_id' => $user->getKey(),
                'body' => $request->string('message')->toString(),
            ]);

            return $conversation;
        });

        $conversation->loadMissing([
            'ad:id,title,user_id',
            'ad.user:id,username',
            'participants:id,username',
            'latestMessage.sender:id,username',
        ]);

        return (new AdConversationResource($conversation))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function destroy(Request $request, AdConversation $conversation): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        if (!$conversation->isParticipant($user)) {
            throw new ModelNotFoundException('Conversation not found.');
        }

        $conversation->hideFor($user);

        return response()->json([
            'meta' => [
                'message' => 'Conversation hidden successfully.',
            ],
        ]);
    }
}
