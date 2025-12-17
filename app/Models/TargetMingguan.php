<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TargetMingguan extends Model
{
    use HasFactory;
    
    protected $table = 'target_mingguan';

    protected $fillable = [
        'group_id',
        'created_by',           // Dosen yang membuat target
        'week_number',
        'title',
        'description',
        'deadline',             // Deadline submit
        'grace_period_minutes', // Grace period
        'auto_close',           // Auto close
        'auto_closed_at',       // Auto closed timestamp
        'is_open',              // Apakah target masih bisa disubmit
        'reopened_by',          // Dosen yang membuka kembali target
        'reopened_at',          // Waktu dibuka kembali
        'submission_notes',     // Catatan dari mahasiswa
        'submission_status',    // Status submission
        'is_completed',
        'evidence_file',
        'evidence_files',
        'is_checked_only',
        'completed_at',
        'completed_by',
        'is_reviewed',
        'reviewed_at',
        'reviewer_id',
        'quality_score',        // NEW: Nilai kualitas dari dosen (0-100)
        'final_score',          // NEW: Hasil kalkulasi (verified/total) × quality
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'is_checked_only' => 'boolean',
        'is_reviewed' => 'boolean',
        'is_open' => 'boolean',
        'auto_close' => 'boolean',
        'evidence_files' => 'array',
        'completed_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'deadline' => 'datetime',
        'reopened_at' => 'datetime',
        'auto_closed_at' => 'datetime',
        'quality_score' => 'decimal:2',  // NEW
        'final_score' => 'decimal:2',    // NEW
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
     * Get todo items for this target
     */
    public function todoItems(): HasMany
    {
        return $this->hasMany(TargetTodoItem::class, 'target_mingguan_id')->orderBy('order');
    }

    /**
     * Check if this target has todo items
     */
    public function hasTodoItems(): bool
    {
        return $this->todoItems()->count() > 0;
    }

    /**
     * Get total todo items count
     */
    public function getTotalTodoCount(): int
    {
        return $this->todoItems()->count();
    }

    /**
     * Get completed todo items count (claimed by student)
     */
    public function getCompletedTodoCount(): int
    {
        return $this->todoItems()->where('is_completed_by_student', true)->count();
    }

    /**
     * Get verified todo items count (verified by reviewer)
     */
    public function getVerifiedTodoCount(): int
    {
        return $this->todoItems()->where('is_verified_by_reviewer', true)->count();
    }

    /**
     * Get completion percentage by student
     */
    public function getCompletionPercentage(): float
    {
        $total = $this->getTotalTodoCount();
        if ($total === 0) return 0;
        
        return round(($this->getCompletedTodoCount() / $total) * 100, 2);
    }

    /**
     * Get verified percentage by reviewer
     */
    public function getVerifiedPercentage(): float
    {
        $total = $this->getTotalTodoCount();
        if ($total === 0) return 0;
        
        return round(($this->getVerifiedTodoCount() / $total) * 100, 2);
    }

    /**
     * Calculate final score based on verified todos and quality score
     * Formula: (verified/total) × quality_score
     */
    public function calculateFinalScore(): float
    {
        $total = $this->getTotalTodoCount();
        if ($total === 0) return 0;
        
        $verified = $this->getVerifiedTodoCount();
        $qualityScore = $this->quality_score ?? 0;
        
        return round(($verified / $total) * $qualityScore, 2);
    }

    /**
     * Update and save final score
     */
    public function updateFinalScore(): void
    {
        $this->final_score = $this->calculateFinalScore();
        $this->save();
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
     * Cek apakah target masih bisa menerima submission
     * Target tidak bisa menerima submission jika:
     * 1. is_open = false (ditutup manual)
     * 2. Sudah direview dosen
     * 3. Batas waktu sudah lewat
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

        // Jika batas waktu sudah lewat, tidak bisa submit
        if ($this->deadline && now()->gt($this->deadline)) {
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
     * Buka kembali target (oleh dosen)
     * Reset status review agar mahasiswa bisa submit ulang
     * Jika batas waktu sudah lewat, perpanjang batas waktu otomatis
     */
    public function reopenTarget($idDosen, $batasWaktuBaru = null): void
    {
        $dataUpdate = [
            'is_open' => true,
            'reopened_by' => $idDosen,
            'reopened_at' => now(),
            // Reset status review agar mahasiswa bisa submit ulang
            'is_reviewed' => false,
            'reviewed_at' => null,
            'reviewer_id' => null,
        ];

        // Jika batas waktu sudah lewat, perpanjang batas waktu
        if ($this->deadline && now()->gt($this->deadline)) {
            // Gunakan batas waktu baru jika diberikan, atau tambah 7 hari dari sekarang
            $dataUpdate['deadline'] = $batasWaktuBaru ?? now()->addDays(7);
        }

        $this->update($dataUpdate);
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
     * Cek apakah mahasiswa bisa submit sekarang
     * Mempertimbangkan status target dan batas waktu
     */
    public function canSubmitNow(): bool
    {
        // Harus terbuka (is_open = true)
        if (!$this->is_open) {
            return false;
        }

        // Jika batas waktu sudah lewat, tidak bisa submit
        if ($this->deadline && now()->gt($this->deadline)) {
            return false;
        }

        // Jika sudah direview, tidak bisa submit
        if ($this->is_reviewed) {
            return false;
        }

        return true;
    }
}






