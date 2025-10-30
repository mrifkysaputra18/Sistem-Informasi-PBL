<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RuangKelas extends Model
{
    protected $table = 'ruang_kelas';

    protected $fillable = [
        'name',
        'code',
        'subject_id',
        'academic_period_id',
        'dosen_id',
        'semester', // Keep for backward compatibility
        'program_studi',
        'max_groups',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'max_groups' => 'integer',
    ];

    /**
     * Get the subject that owns the class
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'subject_id');
    }

    /**
     * Get the academic period that owns the class
     */
    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(PeriodeAkademik::class, 'academic_period_id');
    }

    /**
     * Get the dosen assigned to this class
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'dosen_id');
    }

    /**
     * Get groups for this class
     */
    public function groups(): HasMany
    {
        return $this->hasMany(Kelompok::class, 'class_room_id');
    }

    /**
     * Get students in this class
     */
    public function students(): HasMany
    {
        return $this->hasMany(Pengguna::class, 'class_room_id')->where('role', 'mahasiswa');
    }

    /**
     * Get active groups count
     */
    public function getActiveGroupsCountAttribute()
    {
        return $this->groups()->count();
    }

    /**
     * Check if class can have more groups
     */
    public function canAddGroup(): bool
    {
        return $this->groups()->count() < $this->max_groups;
    }

    /**
     * Check if class is full (reached max groups)
     */
    public function isFull(): bool
    {
        return $this->groups()->count() >= $this->max_groups;
    }
}






