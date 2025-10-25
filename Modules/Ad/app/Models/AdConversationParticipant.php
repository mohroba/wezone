<?php

declare(strict_types=1);

namespace Modules\Ad\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdConversationParticipant extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'last_read_at',
        'deleted_at',
    ];

    protected $casts = [
        'last_read_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(AdConversation::class, 'conversation_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
