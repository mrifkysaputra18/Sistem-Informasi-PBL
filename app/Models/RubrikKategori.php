<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model untuk kategori penilaian dalam rubrik.
 * Contoh kategori: UTS, UAS, Quiz, Tugas, Partisipasi, dll.
 */
class RubrikKategori extends Model
{
    protected $table = 'rubrik_kategori';

    protected $fillable = [
        'rubrik_penilaian_id',
        'nama',
        'bobot',
        'urutan',
        'deskripsi',
        'kode',
    ];

    protected $casts = [
        'bobot' => 'decimal:2',
        'urutan' => 'integer',
    ];

    /**
     * Relasi ke rubrik penilaian
     */
    public function rubrikPenilaian(): BelongsTo
    {
        return $this->belongsTo(RubrikPenilaian::class, 'rubrik_penilaian_id');
    }

    /**
     * Relasi ke items dalam kategori ini (root items saja)
     */
    public function items(): HasMany
    {
        return $this->hasMany(RubrikItem::class, 'rubrik_kategori_id')
            ->whereNull('parent_id')
            ->orderBy('urutan');
    }

    /**
     * Relasi ke semua items dalam kategori ini (termasuk sub-items)
     */
    public function allItems(): HasMany
    {
        return $this->hasMany(RubrikItem::class, 'rubrik_kategori_id')
            ->orderBy('urutan');
    }

    /**
     * Get items dengan sub-items (eager loaded)
     */
    public function itemsWithSubItems(): HasMany
    {
        return $this->items()->with('allSubItems');
    }

    /**
     * Hitung total persentase semua root items
     */
    public function getTotalPersentaseItemsAttribute(): float
    {
        return $this->items()->sum('persentase');
    }

    /**
     * Cek apakah kategori valid (total item = 100%)
     */
    public function isComplete(): bool
    {
        return $this->total_persentase_items == 100;
    }

    /**
     * Scope untuk kategori dengan kode tertentu
     */
    public function scopeByKode($query, string $kode)
    {
        return $query->where('kode', $kode);
    }

    /**
     * Cek apakah ini kategori UTS (untuk backward compatibility)
     */
    public function isUts(): bool
    {
        return $this->kode === 'uts';
    }

    /**
     * Cek apakah ini kategori UAS (untuk backward compatibility)
     */
    public function isUas(): bool
    {
        return $this->kode === 'uas';
    }
}
