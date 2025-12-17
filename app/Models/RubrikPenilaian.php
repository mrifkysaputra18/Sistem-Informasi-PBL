<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RubrikPenilaian extends Model
{
    protected $table = 'rubrik_penilaian';

    protected $fillable = [
        'mata_kuliah_id',
        'nama',
        'deskripsi',
        'periode_akademik_id',
        'semester',
        'bobot_uts',
        'bobot_uas',
        'created_by',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'semester' => 'integer',
        'bobot_uts' => 'decimal:2',
        'bobot_uas' => 'decimal:2',
    ];

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    public function periodeAkademik(): BelongsTo
    {
        return $this->belongsTo(PeriodeAkademik::class, 'periode_akademik_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(RubrikItem::class, 'rubrik_penilaian_id')->orderBy('urutan');
    }

    /**
     * Get items untuk periode UTS
     */
    public function itemsUts(): HasMany
    {
        return $this->hasMany(RubrikItem::class, 'rubrik_penilaian_id')
            ->where('periode_ujian', 'uts')
            ->orderBy('urutan');
    }

    /**
     * Get items untuk periode UAS
     */
    public function itemsUas(): HasMany
    {
        return $this->hasMany(RubrikItem::class, 'rubrik_penilaian_id')
            ->where('periode_ujian', 'uas')
            ->orderBy('urutan');
    }

    /**
     * Hitung total persentase item UTS (harus 100%)
     */
    public function getTotalPersentaseUtsAttribute(): float
    {
        return $this->itemsUts()->sum('persentase');
    }

    /**
     * Hitung total persentase item UAS (harus 100%)
     */
    public function getTotalPersentaseUasAttribute(): float
    {
        return $this->itemsUas()->sum('persentase');
    }

    /**
     * Cek apakah rubrik lengkap:
     * - Bobot UTS + UAS = 100%
     * - Total item UTS = 100%
     * - Total item UAS = 100%
     */
    public function isComplete(): bool
    {
        $bobotValid = ($this->bobot_uts + $this->bobot_uas) == 100;
        $utsValid = $this->total_persentase_uts == 100;
        $uasValid = $this->total_persentase_uas == 100;
        
        return $bobotValid && $utsValid && $uasValid;
    }

    /**
     * Get status validasi detail
     */
    public function getValidationStatus(): array
    {
        return [
            'bobot_valid' => ($this->bobot_uts + $this->bobot_uas) == 100,
            'uts_valid' => $this->total_persentase_uts == 100,
            'uas_valid' => $this->total_persentase_uas == 100,
            'total_bobot' => $this->bobot_uts + $this->bobot_uas,
            'total_uts' => $this->total_persentase_uts,
            'total_uas' => $this->total_persentase_uas,
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function activate(): void
    {
        // Deactivate other rubrics for the same mata kuliah
        self::where('mata_kuliah_id', $this->mata_kuliah_id)
            ->where('id', '!=', $this->id)
            ->update(['is_active' => false]);
        
        $this->update(['is_active' => true]);
    }

    // Legacy support - untuk kompatibilitas dengan kode lama
    public function getTotalPersentaseAttribute(): float
    {
        // Return rata-rata status validasi item
        $utsTotal = $this->total_persentase_uts;
        $uasTotal = $this->total_persentase_uas;
        return ($utsTotal + $uasTotal) / 2;
    }
}
