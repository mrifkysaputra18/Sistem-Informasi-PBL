<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WeeklyTarget extends Model
{
    protected $fillable = [
        'group_id',
        'created_by',           // NEW: Dosen yang membuat target
        'week_number',
        'title',
        'description',
        'deadline',             // NEW: Deadline submit
        'submission_notes',     // NEW: Catatan dari mahasiswa
        'submission_status',    // NEW: Status submission
        'is_completed',
        'evidence_file',
        'evidence_files',
        'is_checked_only',
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
        'deadline' => 'datetime',  // NEW
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
     * Get the creator (dosen who created this target)
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
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

    /**
     * Check if target is pending (belum disubmit mahasiswa)
     */
    public function isPending(): bool
    {
        return $this->submission_status === 'pending';
    }

    /**
     * Check if target has been submitted
     */
    public function isSubmitted(): bool
    {
        return in_array($this->submission_status, ['submitted', 'late', 'approved', 'revision']);
    }

    /**
     * Check if submission is late
     */
    public function isLate(): bool
    {
        if (!$this->deadline) return false;
        
        return $this->completed_at && $this->completed_at->gt($this->deadline);
    }

    /**
     * Check if deadline has passed
     */
    public function isOverdue(): bool
    {
        if (!$this->deadline) return false;
        
        return now()->gt($this->deadline) && !$this->isSubmitted();
    }

    /**
     * Get status badge color
     */
    public function getStatusColor(): string
    {
        return match($this->submission_status) {
            'pending' => 'gray',
            'submitted' => 'blue',
            'late' => 'orange',
            'approved' => 'green',
            'revision' => 'yellow',
            default => 'gray',
        };
    }

    /**
     * Get status label in Indonesian
     */
    public function getStatusLabel(): string
    {
        return match($this->submission_status) {
            'pending' => 'Belum Dikerjakan',
            'submitted' => 'Sudah Submit',
            'late' => 'Terlambat',
            'approved' => 'Disetujui',
            'revision' => 'Perlu Revisi',
            default => 'Unknown',
        };
    }
}
