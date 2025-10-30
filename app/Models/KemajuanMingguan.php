<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KemajuanMingguan extends Model
{
    use HasFactory;

    protected $table = 'kemajuan_mingguan';

    protected $fillable = [
        'group_id',
        'week_number',
        'title',
        'description',
        'activities',
        'achievements',
        'challenges',
        'next_week_plan',
        'documents',
        'status',
        'submitted_at',
        'deadline',
        'is_locked',
        'is_checked_only',
    ];

    protected $casts = [
        'documents' => 'array',
        'submitted_at' => 'datetime',
        'deadline' => 'datetime',
        'is_locked' => 'boolean',
        'is_checked_only' => 'boolean',
    ];

    /**
     * Get the group
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Kelompok::class, 'group_id');
    }

    /**
     * Check if submitted
     */
    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    /**
     * Check if reviewed
     */
    public function isReviewed(): bool
    {
        return $this->status === 'reviewed';
    }

    /**
     * Check if draft
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }
}





