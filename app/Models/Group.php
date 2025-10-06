<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    protected $fillable = [
        'class_room_id',
        'name',
        'project_id',
        'leader_id',
        'google_drive_folder_id',
        'total_score',
        'ranking',
        'max_members'
    ];

    protected $casts = [
        'total_score' => 'decimal:2',
        'max_members' => 'integer',
    ];

    /**
     * Get the class room
     */
    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class, 'class_room_id');
    }

    /**
     * Get academic period through classroom
     */
    public function academicPeriod()
    {
        return $this->hasOneThrough(
            AcademicPeriod::class,
            ClassRoom::class,
            'id',                  // FK di class_rooms
            'id',                  // FK di academic_periods  
            'class_room_id',       // Local key di groups
            'academic_period_id'   // Local key di class_rooms
        );
    }

    /**
     * Get the project
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the leader
     */
    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    /**
     * Get group members
     */
    public function members(): HasMany
    {
        return $this->hasMany(GroupMember::class);
    }

    /**
     * Get group scores
     */
    public function scores(): HasMany
    {
        return $this->hasMany(GroupScore::class);
    }

    /**
     * Get weekly progress
     */
    public function weeklyProgress(): HasMany
    {
        return $this->hasMany(WeeklyProgress::class);
    }

    /**
     * Get weekly targets
     */
    public function weeklyTargets(): HasMany
    {
        return $this->hasMany(WeeklyTarget::class);
    }

    /**
     * Check if group is full
     */
    public function isFull(): bool
    {
        return $this->members()->count() >= $this->max_members;
    }

    /**
     * Get members count
     */
    public function getMembersCountAttribute()
    {
        return $this->members()->count();
    }

    /**
     * Get weekly target completion rate (for speed criteria)
     * Returns percentage (0-100+)
     * Can exceed 100% if completed more than planned
     */
    public function getTargetCompletionRate(): float
    {
        $totalTargets = $this->weeklyTargets()->count();
        
        if ($totalTargets === 0) {
            return 0;
        }

        $completedTargets = $this->weeklyTargets()->where('is_completed', true)->count();
        
        return ($completedTargets / $totalTargets) * 100;
    }

    /**
     * Get target completion score (normalized to 0-100)
     * Used for ranking calculation
     */
    public function getTargetCompletionScore(): float
    {
        $rate = $this->getTargetCompletionRate();
        
        // If rate > 100%, cap at 100 for scoring
        // Or allow bonus? Let's allow up to 120% for over-achievement
        return min($rate, 120);
    }
}
