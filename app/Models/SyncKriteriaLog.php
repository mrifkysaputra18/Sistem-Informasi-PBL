<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SyncKriteriaLog extends Model
{
    protected $table = 'sync_kriteria_logs';

    protected $fillable = [
        'class_room_id',
        'synced_by',
        'criteria_synced',
        'previous_values',
        'new_values',
        'synced_at',
        'is_reverted',
        'reverted_at',
        'reverted_by',
    ];

    protected $casts = [
        'criteria_synced' => 'array',
        'previous_values' => 'array',
        'new_values' => 'array',
        'synced_at' => 'datetime',
        'is_reverted' => 'boolean',
        'reverted_at' => 'datetime',
    ];

    /**
     * Get the classroom
     */
    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(RuangKelas::class, 'class_room_id');
    }

    /**
     * Get the user who synced
     */
    public function syncedByUser(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'synced_by');
    }

    /**
     * Get the user who reverted
     */
    public function revertedByUser(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'reverted_by');
    }

    /**
     * Check if this sync can be reverted
     */
    public function canBeReverted(): bool
    {
        return !$this->is_reverted;
    }

    /**
     * Mark as reverted
     */
    public function markReverted(int $userId): void
    {
        $this->update([
            'is_reverted' => true,
            'reverted_at' => now(),
            'reverted_by' => $userId,
        ]);
    }
}
