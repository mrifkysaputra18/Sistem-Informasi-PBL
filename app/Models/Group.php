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
}
