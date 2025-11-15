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
    /**
     * List conversations for the authenticated user.
     *
     * @group Ads
     * @subgroup Conversations
     * @authenticated
     *
     * @queryParam per_page int Limit the number of conversations per page (1-100). Example: 20
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 56,
     *       "ad": {
     *         "id": 84,
     *         "title": "Vintage bicycle"
     *       },
     *       "latest_message": {
     *         "id": 120,
     *         "body": "Thanks for the update!",
     *         "sender": {
     *           "id": 7,
     *           "username": "seller42"
     *         }
     *       }
     *     }
     *   ],
     *   "links": {
     *     "first": "https://example.com/api/ads/conversations?page=1",
     *     "last": "https://example.com/api/ads/conversations?page=1",
     *     "prev": null,
     *     "next": null
     *   },
     *   "meta": {
     *     "current_page": 1,
     *     "from": 1,
     *     "last_page": 1,
     *     "path": "https://example.com/api/ads/conversations",
     *     "per_page": 20,
     *     "to": 1,
     *     "total": 1
     *   }
     * }
     */
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

    /**
     * Start a conversation about an ad.
     *
     * Creates a conversation (or reopens an existing one) and sends the first message.
     *
     * @group Ads
     * @subgroup Conversations
     * @authenticated
     *
     * @urlParam ad integer required The identifier of the ad to discuss.
     *
     * @response 201 {
     *   "data": {
     *     "id": 57,
     *     "ad": {
     *       "id": 84,
     *       "title": "Vintage bicycle"
     *     },
     *     "latest_message": {
     *       "body": "Is this still available?",
     *       "sender": {
     *         "id": 15,
     *         "username": "buyer42"
     *       }
     *     }
     *   }
     * }
     */
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

    /**
     * Hide a conversation for the authenticated user.
     *
     * @group Ads
     * @subgroup Conversations
     * @authenticated
     *
     * @urlParam conversation integer required The identifier of the conversation to hide.
     *
     * @response 200 {
     *   "meta": {
     *     "message": "Conversation hidden successfully."
     *   }
     * }
     */
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
