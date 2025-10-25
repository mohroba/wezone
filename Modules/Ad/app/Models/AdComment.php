<?php

namespace Modules\Ad\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ad_id',
        'user_id',
        'parent_id',
        'body',
    ];

    public function ad(): BelongsTo
    {
        return $this->belongsTo(Ad::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('created_at');
    }

    public function repliesRecursive(): HasMany
    {
        return $this
            ->replies()
            ->with(['user', 'repliesRecursive']);
    }

    public function scopeRoot(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    protected static function newFactory(): Factory
    {
        return \Modules\Ad\Database\Factories\AdCommentFactory::new();
    }
}
