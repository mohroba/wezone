<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KpiSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_uuid',
        'kpi_device_id',
        'user_id',
        'started_at',
        'ended_at',
        'duration_seconds',
        'app_version',
        'platform',
        'os_version',
        'network_type',
        'city',
        'country',
        'metadata',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'duration_seconds' => 'integer',
        'metadata' => 'array',
    ];

    public function getRouteKeyName(): string
    {
        return 'session_uuid';
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(KpiDevice::class, 'kpi_device_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<KpiEvent>
     */
    public function events(): HasMany
    {
        return $this->hasMany(KpiEvent::class, 'kpi_session_id');
    }
}
