<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyTargetReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'weekly_target_id',
        'reviewer_id',
        'score',
        'feedback',
        'suggestions',
        'status',
    ];

    protected $casts = [
        'score' => 'decimal:2',
    ];

    /**
     * Get the weekly target being reviewed
     */
    public function weeklyTarget(): BelongsTo
    {
        return $this->belongsTo(WeeklyTarget::class);
    }

    /**
     * Get the reviewer (dosen)
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * Check if approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if needs revision
     */
    public function needsRevision(): bool
    {
        return $this->status === 'needs_revision';
    }

    /**
     * Check if rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
