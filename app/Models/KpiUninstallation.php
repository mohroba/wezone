<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KpiUninstallation extends Model
{
    use HasFactory;

    protected $fillable = [
        'kpi_device_id',
        'user_id',
        'uninstalled_at',
        'app_version',
        'reason',
        'report_source',
        'metadata',
    ];

    protected $casts = [
        'uninstalled_at' => 'datetime',
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
