<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KpiInstallation extends Model
{
    use HasFactory;

    protected $fillable = [
        'kpi_device_id',
        'user_id',
        'installed_at',
        'app_version',
        'install_source',
        'campaign',
        'is_reinstall',
        'metadata',
    ];

    protected $casts = [
        'installed_at' => 'datetime',
        'is_reinstall' => 'boolean',
        'metadata' => 'array',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(KpiDevice::class, 'kpi_device_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
