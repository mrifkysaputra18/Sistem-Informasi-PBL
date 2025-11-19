<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TargetMingguan extends Model
{
    protected $table = 'target_mingguan';

    protected $fillable = [
        'group_id',
        'created_by',           // NEW: Dosen yang membuat target
        'week_number',
        'title',
        'description',
        'deadline',             // NEW: Deadline submit
        'grace_period_minutes', // NEW: Grace period
        'auto_close',           // NEW: Auto close
        'auto_closed_at',       // NEW: Auto closed timestamp
        'is_open',              // NEW: Apakah target masih bisa disubmit
        'reopened_by',          // NEW: Dosen yang membuka kembali target
        'reopened_at',          // NEW: Waktu dibuka kembali
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
        'is_open' => 'boolean',    // NEW
        'auto_close' => 'boolean', // NEW
        'evidence_files' => 'array',
        'completed_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'deadline' => 'datetime',  // NEW
        'reopened_at' => 'datetime', // NEW
        'auto_closed_at' => 'datetime', // NEW
    ];

    /**
     * Boot method for auto-closing expired targets
     */
    protected static function booted(): void
    {
        // Auto-check and close expired targets when loaded from database
        static::retrieved(function ($target) {
            if ($target->shouldAutoClose()) {
                // Update database immediately without triggering events
                $target->updateQuietly([
                    'is_open' => false,
                    'auto_closed_at' => now(),
                ]);
                
                // Update model instance
                $target->is_open = false;
                $target->auto_closed_at = now();
            }
        });
    }

    /**
     * Get the group
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Kelompok::class, 'group_id');
    }

    /**
     * Get the user who completed this target
     */
    public function completedByUser(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'completed_by');
    }

    /**
     * Get the creator (dosen who created this target)
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'created_by');
    }

    /**
     * Get the reviewer (dosen)
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'reviewer_id');
    }

    /**
     * Get the user who reopened this target
     */
    public function reopener(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'reopened_by');
    }

    /**
     * Get the review for this target
     */
    public function review(): HasOne
    {
        return $this->hasOne(UlasanTargetMingguan::class, 'weekly_target_id');
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

    /**
     * Check if target can accept submission (masih terbuka)
     * Target tertutup jika:
     * 1. is_open = false (ditutup manual atau otomatis karena deadline)
     * 2. Sudah direview dosen
     * 3. Past final deadline (deadline + grace period) if auto_close enabled
     */
    public function canAcceptSubmission(): bool
    {
        // Jika sudah direview, tidak bisa submit lagi
        if ($this->is_reviewed) {
            return false;
        }

        // Jika is_open = false, tidak bisa submit
        if (!$this->is_open) {
            return false;
        }

        // Jika auto_close enabled dan sudah lewat final deadline
        if ($this->auto_close && $this->isPastFinalDeadline()) {
            return false;
        }

        return true;
    }

    /**
     * Check if submission can be cancelled by student
     * Kondisi bisa cancel:
     * 1. Sudah submit (status: submitted/late)
     * 2. Belum direview oleh dosen
     * 3. Deadline belum lewat
     * 4. Target masih terbuka (is_open = true)
     */
    public function canCancelSubmission(): bool
    {
        // Hanya bisa cancel jika sudah submit tapi belum direview
        if (!in_array($this->submission_status, ['submitted', 'late'])) {
            return false;
        }

        // Tidak bisa cancel jika sudah direview
        if ($this->is_reviewed) {
            return false;
        }

        // Tidak bisa cancel jika target sudah ditutup
        if ($this->isClosed()) {
            return false;
        }

        // Tidak bisa cancel jika deadline sudah lewat
        if ($this->deadline && now()->gt($this->deadline)) {
            return false;
        }

        return true;
    }

    /**
     * Check if target is closed (tertutup)
     */
    public function isClosed(): bool
    {
        return !$this->is_open;
    }

    /**
     * Check if deadline has passed and target should be closed
     * Ini untuk auto-close target yang melewati deadline
     */
    public function shouldBeClosed(): bool
    {
        if (!$this->deadline) return false;
        
        // Jika sudah direview dosen, tidak perlu ditutup (sudah final)
        if ($this->is_reviewed) {
            return false;
        }

        // Jika masih open tapi deadline sudah lewat
        // Berlaku untuk semua target (baik yang sudah submit maupun belum)
        return $this->is_open && now()->gt($this->deadline);
    }

    /**
     * Close target (untuk auto-close atau manual close oleh dosen)
     */
    public function closeTarget(): void
    {
        $this->update(['is_open' => false]);
    }

    /**
     * Reopen target (oleh dosen)
     * Reset review status agar mahasiswa bisa submit ulang
     */
    public function reopenTarget($dosenId): void
    {
        $this->update([
            'is_open' => true,
            'reopened_by' => $dosenId,
            'reopened_at' => now(),
            // Reset review status agar mahasiswa bisa submit ulang
            'is_reviewed' => false,
            'reviewed_at' => null,
            'reviewer_id' => null,
        ]);
    }

    /**
     * Get closure reason message
     */
    public function getClosureReason(): string
    {
        if (!$this->isClosed()) {
            return '';
        }

        if ($this->deadline && now()->gt($this->deadline) && !$this->isSubmitted()) {
            return 'Target ditutup karena melewati deadline';
        }

        return 'Target ditutup oleh dosen';
    }

    /**
     * Check if target is past final deadline (deadline + grace period)
     */
    public function isPastFinalDeadline(): bool
    {
        if (!$this->deadline) {
            return false;
        }

        $finalDeadline = $this->deadline->copy()->addMinutes($this->grace_period_minutes ?? 0);
        return now()->gt($finalDeadline);
    }

    /**
     * Check if target should be auto-closed
     */
    public function shouldAutoClose(): bool
    {
        return $this->auto_close 
            && $this->isPastFinalDeadline() 
            && $this->is_open 
            && !$this->isSubmitted();
    }

    /**
     * Get final deadline including grace period
     */
    public function getFinalDeadline()
    {
        if (!$this->deadline) {
            return null;
        }

        return $this->deadline->copy()->addMinutes($this->grace_period_minutes ?? 0);
    }

    /**
     * Check if currently in grace period
     */
    public function isInGracePeriod(): bool
    {
        if (!$this->deadline || !$this->grace_period_minutes) {
            return false;
        }

        return now()->gt($this->deadline) && now()->lte($this->getFinalDeadline());
    }

    /**
     * Check if can submit (considering deadline + grace period)
     */
    public function canSubmitNow(): bool
    {
        // Must be open
        if (!$this->is_open) {
            return false;
        }

        // If auto_close enabled, check deadline
        if ($this->auto_close && $this->isPastFinalDeadline()) {
            return false;
        }

        // If already reviewed, cannot submit
        if ($this->is_reviewed) {
            return false;
        }

        return true;
    }
}






