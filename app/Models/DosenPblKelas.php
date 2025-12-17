<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model untuk relasi Dosen PBL dengan Kelas
 * 
 * Dosen PBL adalah dosen yang bertanggung jawab untuk proyek PBL di kelas tertentu
 * dan bisa membuat target mingguan untuk kelompok di kelas tersebut.
 */
class DosenPblKelas extends Model
{
    protected $table = 'dosen_pbl_kelas';

    protected $fillable = [
        'dosen_id',
        'class_room_id',
        'periode',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get dosen yang diassign
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'dosen_id');
    }

    /**
     * Get kelas yang diassign
     */
    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(RuangKelas::class, 'class_room_id');
    }

    /**
     * Scope untuk filter berdasarkan periode
     */
    public function scopeSebelumUts($query)
    {
        return $query->where('periode', 'sebelum_uts');
    }

    public function scopeSesudahUts($query)
    {
        return $query->where('periode', 'sesudah_uts');
    }

    /**
     * Scope untuk filter yang aktif saja
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get label periode dalam Bahasa Indonesia
     */
    public function getPeriodeLabelAttribute(): string
    {
        return match($this->periode) {
            'sebelum_uts' => 'Sebelum UTS',
            'sesudah_uts' => 'Sesudah UTS',
            default => $this->periode,
        };
    }
}
