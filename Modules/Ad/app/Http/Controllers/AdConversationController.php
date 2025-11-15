<?php

namespace Modules\Ad\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\CarbonImmutable;
use Exception;
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
use Modules\Ad\Support\HandlesConversationMessages;

class AdConversationController extends Controller
{
    use HandlesConversationMessages;

    /**
     * List conversations for the authenticated user.
     *
     * @group Ads
     * @subgroup Conversations
     * @authenticated
     *
     * @queryParam per_page int Limit the number of conversations per page (1-100). Example: 20
     * @queryParam ad_id int Filter conversations for a specific ad. Example: 42
     * @queryParam advertisable_type string Filter by the ad's advertisable type. Example: "Modules\\Classifieds\\Models\\Listing"
     * @queryParam advertisable_id int Filter by the advertisable identifier. Example: 993
     * @queryParam created_from string Return conversations created on or after this ISO-8601 date. Example: 2025-01-01T00:00:00Z
     * @queryParam created_to string Return conversations created on or before this ISO-8601 date. Example: 2025-01-31T23:59:59Z
     * @queryParam updated_from string Return conversations updated on or after this ISO-8601 date. Example: 2025-02-01T00:00:00Z
     * @queryParam updated_to string Return conversations updated on or before this ISO-8601 date. Example: 2025-02-28T23:59:59Z
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
        $dateFilters = $this->resolveDateFilters($request);

        $conversations = AdConversation::query()
            ->visibleToUser($user)
            ->when($request->filled('ad_id'), fn ($query) => $query->where('ad_id', (int) $request->input('ad_id')))
            ->when($request->filled('advertisable_type'), function ($query) use ($request) {
                $type = $request->string('advertisable_type')->trim()->toString();

                $query->whereHas('ad', fn ($adQuery) => $adQuery->where('advertisable_type', $type));
            })
            ->when($request->filled('advertisable_id'), function ($query) use ($request) {
                $id = (int) $request->input('advertisable_id');

                $query->whereHas('ad', fn ($adQuery) => $adQuery->where('advertisable_id', $id));
            })
            ->when($dateFilters['created_from'], fn ($query, CarbonImmutable $date) => $query->where('ad_conversations.created_at', '>=', $date))
            ->when($dateFilters['created_to'], fn ($query, CarbonImmutable $date) => $query->where('ad_conversations.created_at', '<=', $date))
            ->when($dateFilters['updated_from'], fn ($query, CarbonImmutable $date) => $query->where('ad_conversations.updated_at', '>=', $date))
            ->when($dateFilters['updated_to'], fn ($query, CarbonImmutable $date) => $query->where('ad_conversations.updated_at', '<=', $date))
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

        $recipient = User::query()->find($recipientId);

        if (!$recipient instanceof User) {
            throw new ModelNotFoundException('Recipient not found.');
        }

        $this->guardAgainstBlockedParticipants($user, [$recipient]);

        $participantIds = collect([$user->getKey(), $recipientId])->unique()->values()->all();

        if (count($participantIds) < 2) {
            throw new ModelNotFoundException('Recipient not found.');
        }

        [$conversation, $message] = DB::transaction(function () use ($ad, $user, $participantIds, $request) {
            $conversationQuery = AdConversation::query()->where('ad_id', $ad->getKey());

            foreach ($participantIds as $participantId) {
                $conversationQuery->whereHas('participants', fn ($query) => $query->whereKey($participantId));
            }

            $conversation = $conversationQuery->lockForUpdate()->first();

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

            $message = $this->createConversationMessage($conversation, $request, $user);

            return [$conversation, $message];
        });

        $conversation->setRelation('latestMessage', $message);
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

    /**
     * @return array<string, CarbonImmutable|null>
     */
    private function resolveDateFilters(Request $request): array
    {
        return [
            'created_from' => $this->parseDate($request->input('created_from')),
            'created_to' => $this->parseDate($request->input('created_to')),
            'updated_from' => $this->parseDate($request->input('updated_from')),
            'updated_to' => $this->parseDate($request->input('updated_to')),
        ];
    }

    private function parseDate(mixed $value): ?CarbonImmutable
    {
        if (!is_string($value) || trim($value) === '') {
            return null;
        }

        try {
            return CarbonImmutable::parse($value);
        } catch (Exception) {
            return null;
        }
    }
}
