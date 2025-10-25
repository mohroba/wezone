<?php

declare(strict_types=1);

namespace Modules\Ad\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Modules\Ad\Http\Requests\Conversation\SendMessageRequest;
use Modules\Ad\Http\Requests\Conversation\StartConversationRequest;
use Modules\Ad\Http\Resources\AdConversationResource;
use Modules\Ad\Http\Resources\AdMessageResource;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdConversation;
use Modules\Ad\Models\AdConversationParticipant;
use Modules\Ad\Models\AdMessage;

class AdConversationController extends Controller
{
    /**
     * @group Ads
     * @authenticated
     *
     * List ad conversations
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user('api');

        $conversations = AdConversation::query()
            ->whereHas('participants', function ($query) use ($user): void {
                $query->where('user_id', $user->getKey())
                    ->whereNull('deleted_at');
            })
            ->with([
                'ad.media',
                'seller.profile',
                'buyer.profile',
                'lastMessage.sender.profile',
            ])
            ->withCount([
                'messages as unread_messages_count' => function ($query) use ($user): void {
                    $query->where('sender_id', '!=', $user->getKey())
                        ->where('is_read', false);
                },
            ])
            ->latest('last_message_at')
            ->paginate((int) min($request->integer('per_page', 20), 50));

        return AdConversationResource::collection($conversations);
    }

    /**
     * @group Ads
     * @authenticated
     *
     * Start a conversation with the ad owner
     */
    public function store(StartConversationRequest $request, Ad $ad): JsonResponse
    {
        $user = $request->user('api');

        if ($ad->user_id === $user->getKey()) {
            abort(422, 'You cannot start a conversation with yourself.');
        }

        if ($ad->user->isBlocking($user) || $user->isBlocking($ad->user)) {
            abort(403, 'You are not permitted to interact with this user.');
        }

        $conversation = DB::transaction(function () use ($ad, $user, $request) {
            /** @var AdConversation $conversation */
            $conversation = AdConversation::withTrashed()->firstOrCreate([
                'ad_id' => $ad->getKey(),
                'seller_id' => $ad->user_id,
                'buyer_id' => $user->getKey(),
            ]);

            if ($conversation->trashed()) {
                $conversation->restore();
            }

            $this->syncParticipant($conversation, $ad->user_id, true);
            $participant = $this->syncParticipant($conversation, $user->getKey(), true);

            $message = $this->persistMessage($conversation, $user->getKey(), $request->validated('message'), true);

            $participant->forceFill(['last_read_at' => $message->created_at])->save();

            return $conversation;
        });

        $conversation->load([
            'ad.media',
            'seller.profile',
            'buyer.profile',
            'lastMessage.sender.profile',
        ])->loadCount([
            'messages as unread_messages_count' => function ($query) use ($user): void {
                $query->where('sender_id', '!=', $user->getKey())
                    ->where('is_read', false);
            },
        ]);

        return (new AdConversationResource($conversation))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * @group Ads
     * @authenticated
     *
     * Show conversation details
     */
    public function show(Request $request, AdConversation $conversation)
    {
        $user = $request->user('api');

        $participant = $conversation->participants()->where('user_id', $user->getKey())->first();

        if (! $participant) {
            abort(404);
        }

        $conversation->load([
            'ad.media',
            'seller.profile',
            'buyer.profile',
            'messages.sender.profile',
        ])->loadCount([
            'messages as unread_messages_count' => function ($query) use ($user): void {
                $query->where('sender_id', '!=', $user->getKey())
                    ->where('is_read', false);
            },
        ]);

        $this->markConversationRead($conversation, $user->getKey());

        $participant->forceFill(['deleted_at' => null, 'last_read_at' => Carbon::now()])->save();

        $conversation->setAttribute('unread_messages_count', 0);

        return new AdConversationResource($conversation);
    }

    /**
     * @group Ads
     * @authenticated
     *
     * Send a new message in a conversation
     */
    public function sendMessage(SendMessageRequest $request, AdConversation $conversation): JsonResponse
    {
        $user = $request->user('api');

        if (! $conversation->participants()->where('user_id', $user->getKey())->exists()) {
            abort(404);
        }

        $otherParticipant = $conversation->seller_id === $user->getKey()
            ? $conversation->buyer
            : $conversation->seller;

        if ($otherParticipant->isBlocking($user) || $user->isBlocking($otherParticipant)) {
            abort(403, 'You are not permitted to interact with this user.');
        }

        $message = DB::transaction(function () use ($conversation, $user, $request) {
            $senderParticipant = $this->syncParticipant($conversation, $user->getKey(), true);
            $recipientParticipant = $this->syncParticipant($conversation, $conversation->seller_id === $user->getKey() ? $conversation->buyer_id : $conversation->seller_id, true);

            $message = $this->persistMessage($conversation, $user->getKey(), $request->validated('message'), true);

            $senderParticipant->forceFill(['last_read_at' => $message->created_at])->save();
            $recipientParticipant->forceFill(['deleted_at' => null])->save();

            return $message;
        });

        $message->load('sender.profile');

        return (new AdMessageResource($message))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * @group Ads
     * @authenticated
     *
     * Remove conversation for the current user
     */
    public function destroy(Request $request, AdConversation $conversation): JsonResponse
    {
        $user = $request->user('api');

        $participant = $conversation->participants()->where('user_id', $user->getKey())->first();

        if (! $participant) {
            abort(404);
        }

        $participant->forceFill([
            'deleted_at' => Carbon::now(),
            'last_read_at' => Carbon::now(),
        ])->save();

        if ($conversation->participants()->whereNull('deleted_at')->doesntExist()) {
            $conversation->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Conversation removed for the current user.',
        ]);
    }

    private function syncParticipant(AdConversation $conversation, int $userId, bool $resetDeletion): AdConversationParticipant
    {
        /** @var AdConversationParticipant $participant */
        $participant = $conversation->participants()->withTrashed()->firstOrNew([
            'user_id' => $userId,
        ]);

        if ($participant->exists && ! $resetDeletion) {
            return $participant;
        }

        $participant->conversation_id = $conversation->getKey();

        if ($resetDeletion) {
            $participant->deleted_at = null;
        }

        $participant->save();

        return $participant;
    }

    private function persistMessage(AdConversation $conversation, int $senderId, string $body, bool $markRead = true): AdMessage
    {
        /** @var AdMessage $message */
        $message = $conversation->messages()->create([
            'sender_id' => $senderId,
            'body' => $body,
            'is_read' => $markRead,
        ]);

        $conversation->forceFill([
            'last_message_id' => $message->getKey(),
            'last_message_at' => $message->created_at,
        ])->save();

        return $message;
    }

    private function markConversationRead(AdConversation $conversation, int $userId): void
    {
        $conversation->messages()
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }
}
