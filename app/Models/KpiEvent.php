<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KpiEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_uuid',
        'kpi_device_id',
        'kpi_session_id',
        'user_id',
        'event_key',
        'event_name',
        'event_category',
        'event_value',
        'occurred_at',
        'metadata',
    ];

    protected $casts = [
        'event_value' => 'float',
        'occurred_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(KpiDevice::class, 'kpi_device_id');
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(KpiSession::class, 'kpi_session_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
