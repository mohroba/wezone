<?php

namespace Modules\Ad\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'ad_id',
        'from_status',
        'to_status',
        'changed_by',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function ad(): BelongsTo
    {
        return $this->belongsTo(Ad::class);
    }

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
