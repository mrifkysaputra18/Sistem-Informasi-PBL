<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TargetTodoItem extends Model
{
    protected $table = 'target_todo_items';

    protected $fillable = [
        'target_mingguan_id',
        'title',
        'description',
        'order',
        'is_completed_by_student',
        'completed_at',
        'is_verified_by_reviewer',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'is_completed_by_student' => 'boolean',
        'is_verified_by_reviewer' => 'boolean',
        'completed_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the target mingguan this todo belongs to
     */
    public function targetMingguan(): BelongsTo
    {
        return $this->belongsTo(TargetMingguan::class, 'target_mingguan_id');
    }

    /**
     * Get the reviewer who verified this todo
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'verified_by');
    }

    /**
     * Mark as completed by student
     */
    public function markCompleted(): void
    {
        $this->update([
            'is_completed_by_student' => true,
            'completed_at' => now(),
        ]);
    }

    /**
     * Mark as verified by reviewer
     */
    public function markVerified(int $reviewerId): void
    {
        $this->update([
            'is_verified_by_reviewer' => true,
            'verified_by' => $reviewerId,
            'verified_at' => now(),
        ]);
    }

    /**
     * Unmark verification
     */
    public function unmarkVerified(): void
    {
        $this->update([
            'is_verified_by_reviewer' => false,
            'verified_by' => null,
            'verified_at' => null,
        ]);
    }
}
