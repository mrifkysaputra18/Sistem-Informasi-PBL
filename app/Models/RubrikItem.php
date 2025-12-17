<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RubrikItem extends Model
{
    protected $table = 'rubrik_item';

    // Konstanta untuk periode ujian
    const PERIODE_UTS = 'uts';
    const PERIODE_UAS = 'uas';

    protected $fillable = [
        'rubrik_penilaian_id',
        'periode_ujian',
        'nama',
        'persentase',
        'deskripsi',
        'urutan',
    ];

    protected $casts = [
        'persentase' => 'decimal:2',
        'urutan' => 'integer',
    ];

    public function rubrikPenilaian(): BelongsTo
    {
        return $this->belongsTo(RubrikPenilaian::class, 'rubrik_penilaian_id');
    }

    /**
     * Scope untuk item UTS
     */
    public function scopeUts($query)
    {
        return $query->where('periode_ujian', self::PERIODE_UTS);
    }

    /**
     * Scope untuk item UAS
     */
    public function scopeUas($query)
    {
        return $query->where('periode_ujian', self::PERIODE_UAS);
    }

    /**
     * Cek apakah item ini untuk UTS
     */
    public function isUts(): bool
    {
        return $this->periode_ujian === self::PERIODE_UTS;
    }

    /**
     * Cek apakah item ini untuk UAS
     */
    public function isUas(): bool
    {
        return $this->periode_ujian === self::PERIODE_UAS;
    }
}
