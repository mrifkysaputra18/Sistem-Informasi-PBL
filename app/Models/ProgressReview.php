<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'weekly_progress_id',
        'reviewer_id',
        'score_progress_speed',
        'score_quality',
        'score_timeliness',
        'score_collaboration',
        'total_score',
        'feedback',
        'suggestions',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'score_progress_speed' => 'decimal:1',
            'score_quality' => 'decimal:1',
            'score_timeliness' => 'decimal:1',
            'score_collaboration' => 'decimal:1',
            'total_score' => 'decimal:1',
        ];
    }

    // Relationships
    public function weeklyProgress()
    {
        return $this->belongsTo(WeeklyProgress::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    // Helper methods
    public function calculateTotalScore()
    {
        $this->total_score = ($this->score_progress_speed ?? 0) + 
                           ($this->score_quality ?? 0) + 
                           ($this->score_timeliness ?? 0) + 
                           ($this->score_collaboration ?? 0);
        
        return $this->total_score;
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function needsRevision()
    {
        return $this->status === 'needs_revision';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}