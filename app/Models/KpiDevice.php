<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KpiDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_uuid',
        'platform',
        'app_version',
        'os_version',
        'device_model',
        'device_manufacturer',
        'locale',
        'timezone',
        'push_token',
        'first_seen_at',
        'last_seen_at',
        'last_heartbeat_at',
        'is_active',
        'extra',
    ];

    protected $casts = [
        'first_seen_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'last_heartbeat_at' => 'datetime',
        'is_active' => 'boolean',
        'extra' => 'array',
    ];

    /**
     * @return HasMany<KpiInstallation>
     */
    public function installations(): HasMany
    {
        return $this->hasMany(KpiInstallation::class);
    }

    /**
     * @return HasMany<KpiUninstallation>
     */
    public function uninstallations(): HasMany
    {
        return $this->hasMany(KpiUninstallation::class);
    }

    /**
     * @return HasMany<KpiSession>
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(KpiSession::class);
    }

    /**
     * @return HasMany<KpiEvent>
     */
    public function events(): HasMany
    {
        return $this->hasMany(KpiEvent::class);
    }
}
