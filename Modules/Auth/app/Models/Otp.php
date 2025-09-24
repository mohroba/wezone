<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'mobile',
        'code',
        'expires_at',
        'used_at',
        'attempts',
        'meta',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'meta' => 'array',
    ];

    public function markAsUsed(): void
    {
        $this->forceFill([
            'used_at' => now(),
        ])->save();
    }

    public function incrementAttempts(): void
    {
        $this->increment('attempts');
    }

    public function hasExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
