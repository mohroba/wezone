<?php

namespace Modules\Ad\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class AdJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'company_name',
        'position_title',
        'industry',
        'employment_type',
        'experience_level',
        'education_level',
        'salary_min',
        'salary_max',
        'currency',
        'salary_type',
        'work_schedule',
        'remote_level',
        'benefits_json',
    ];

    protected $casts = [
        'benefits_json' => 'array',
    ];

    public function ad(): MorphOne
    {
        return $this->morphOne(Ad::class, 'advertisable');
    }
}
