<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WeeklyTarget extends Model
{
    protected $fillable = [
        'group_id',
        'week_number',
        'title',
        'description',
        'is_completed',
        'evidence_file',
        'evidence_files', // New: JSON array for multiple files
        'is_checked_only', // New: Checkbox option
        'completed_at',
        'completed_by',
        'is_reviewed',
        'reviewed_at',
        'reviewer_id',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'is_checked_only' => 'boolean',
        'is_reviewed' => 'boolean',
        'evidence_files' => 'array',
        'completed_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the group
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get the user who completed this target
     */
    public function completedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    /**
     * Get the reviewer (dosen)
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * Get the review for this target
     */
    public function review(): HasOne
    {
        return $this->hasOne(WeeklyTargetReview::class);
    }

    /**
     * Check if target can be edited/deleted
     * Target cannot be modified after being reviewed by dosen
     */
    public function canBeModified(): bool
    {
        return !$this->is_reviewed;
    }

    /**
     * Check if target has been reviewed
     */
    public function isReviewed(): bool
    {
        return $this->is_reviewed ?? false;
    }
}
