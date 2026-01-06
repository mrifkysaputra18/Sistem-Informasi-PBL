<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model untuk relasi Dosen PBL dengan Kelas
 * 
 * Dosen PBL adalah dosen yang bertanggung jawab untuk proyek PBL di kelas tertentu
 * dan bisa membuat target mingguan untuk kelompok di kelas tersebut.
 * 
 * UPDATE: 1 Dosen PBL untuk full semester (tidak ada rolling sebelum/sesudah UTS)
 */
class DosenPblKelas extends Model
{
    protected $table = 'dosen_pbl_kelas';

    protected $fillable = [
        'dosen_id',
        'class_room_id',
        'academic_period_id',
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
     * Get periode akademik
     */
    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(PeriodeAkademik::class, 'academic_period_id');
    }

    /**
     * Scope untuk filter yang aktif saja
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
