<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subject extends Model
{
    protected $fillable = [
        'academic_period_id',
        'code',
        'name',
        'description',
        'is_pbl_related',
        'is_active'
    ];

    protected $casts = [
        'is_pbl_related' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the academic period that owns the subject
     */
    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    /**
     * Get class rooms for this subject
     */
    public function classRooms(): HasMany
    {
        return $this->hasMany(ClassRoom::class);
    }

    /**
     * Get criteria for this subject
     */
    public function criteria(): HasMany
    {
        return $this->hasMany(Criterion::class);
    }
}
