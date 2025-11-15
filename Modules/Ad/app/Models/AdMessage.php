<?php

namespace Modules\Ad\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'ad_conversation_id',
        'user_id',
        'body',
        'type',
        'payload',
    ];

    protected $touches = ['conversation'];

    protected $casts = [
        'payload' => 'array',
    ];

    protected $attributes = [
        'type' => 'text',
    ];

    /**
     * @return BelongsTo<AdConversation, AdMessage>
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(AdConversation::class, 'ad_conversation_id');
    }

    /**
     * @return BelongsTo<User, AdMessage>
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
