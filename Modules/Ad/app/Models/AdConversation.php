<?php

namespace Modules\Ad\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AdConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'ad_id',
        'initiated_by',
    ];

    /**
     * @return BelongsTo<Ad, AdConversation>
     */
    public function ad(): BelongsTo
    {
        return $this->belongsTo(Ad::class);
    }

    /**
     * @return BelongsTo<User, AdConversation>
     */
    public function initiator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }

    /**
     * @return HasMany<AdMessage>
     */
    public function messages(): HasMany
    {
        return $this->hasMany(AdMessage::class, 'ad_conversation_id');
    }

    /**
     * @return HasOne<AdMessage>
     */
    public function latestMessage(): HasOne
    {
        return $this->hasOne(AdMessage::class, 'ad_conversation_id')->latestOfMany();
    }

    /**
     * @return BelongsToMany<User>
     */
    public function participants(): BelongsToMany
    {
        return $this
            ->belongsToMany(User::class, 'ad_conversation_user', 'ad_conversation_id', 'user_id')
            ->using(AdConversationParticipant::class)
            ->withPivot(['deleted_at'])
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany<User>
     */
    public function activeParticipants(): BelongsToMany
    {
        return $this->participants()->whereNull('ad_conversation_user.deleted_at');
    }

    public function scopeVisibleToUser(Builder $query, User $user): Builder
    {
        return $query->whereHas('participants', function (Builder $participantQuery) use ($user): void {
            $participantQuery
                ->where('users.id', $user->getKey())
                ->whereNull('ad_conversation_user.deleted_at');
        });
    }

    public function isParticipant(User $user): bool
    {
        if (!$user->exists || !$this->exists) {
            return false;
        }

        return $this->participants()->whereKey($user->getKey())->exists();
    }

    public function isVisibleFor(User $user): bool
    {
        if (!$user->exists || !$this->exists) {
            return false;
        }

        return $this->participants()
            ->whereKey($user->getKey())
            ->whereNull('ad_conversation_user.deleted_at')
            ->exists();
    }

    public function ensureParticipant(User $user): void
    {
        if (!$user->exists || !$this->exists) {
            return;
        }

        $this->participants()->syncWithoutDetaching([
            $user->getKey() => ['deleted_at' => null],
        ]);

        $this->participants()->updateExistingPivot($user->getKey(), [
            'deleted_at' => null,
            'updated_at' => now(),
        ]);
    }

    public function hideFor(User $user): void
    {
        if (!$user->exists || !$this->exists) {
            return;
        }

        $this->participants()->updateExistingPivot($user->getKey(), [
            'deleted_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
