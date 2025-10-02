<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassRoom extends Model
{
    protected $fillable = [
        'name',
        'code',
        'subject_id',
        'academic_period_id',
        'semester', // Keep for backward compatibility
        'program_studi',
        'max_groups',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'max_groups' => 'integer',
    ];

    /**
     * Get the subject that owns the class
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the academic period that owns the class
     */
    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    /**
     * Get groups for this class
     */
    public function groups(): HasMany
    {
        return $this->hasMany(Group::class, 'class_room_id');
    }

    /**
     * Get active groups count
     */
    public function getActiveGroupsCountAttribute()
    {
        return $this->groups()->count();
    }

    /**
     * Check if class can have more groups
     */
    public function canAddGroup(): bool
    {
        return $this->groups()->count() < $this->max_groups;
    }

    /**
     * Check if class is full (reached max groups)
     */
    public function isFull(): bool
    {
        return $this->groups()->count() >= $this->max_groups;
    }
}