<?php

namespace Modules\Ad\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'ad_id',
        'reported_by',
        'reason_code',
        'description',
        'status',
        'handled_by',
        'handled_at',
        'resolution_notes',
        'metadata',
    ];

    protected $casts = [
        'handled_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function ad(): BelongsTo
    {
        return $this->belongsTo(Ad::class);
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    protected static function newFactory(): Factory
    {
        return \Modules\Ad\Database\Factories\AdReportFactory::new();
    }
}
