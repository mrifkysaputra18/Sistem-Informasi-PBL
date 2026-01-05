<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RubrikItem extends Model
{
    protected $table = 'rubrik_item';

    // Maksimal level kedalaman (0=root, 1=sub-item, 2=sub-sub-item)
    const MAX_LEVEL = 2;

    protected $fillable = [
        'rubrik_penilaian_id',
        'rubrik_kategori_id',
        'parent_id',
        'level',
        'nama',
        'persentase',
        'deskripsi',
        'urutan',
    ];

    protected $casts = [
        'persentase' => 'decimal:2',
        'urutan' => 'integer',
        'level' => 'integer',
        'parent_id' => 'integer',
        'rubrik_kategori_id' => 'integer',
    ];

    /**
     * Relasi ke rubrik penilaian
     */
    public function rubrikPenilaian(): BelongsTo
    {
        return $this->belongsTo(RubrikPenilaian::class, 'rubrik_penilaian_id');
    }

    /**
     * Relasi ke kategori
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(RubrikKategori::class, 'rubrik_kategori_id');
    }

    /**
     * Parent item (untuk sub-items)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(RubrikItem::class, 'parent_id');
    }

    /**
     * Sub-items (children) langsung
     */
    public function subItems(): HasMany
    {
        return $this->hasMany(RubrikItem::class, 'parent_id')->orderBy('urutan');
    }

    /**
     * Recursive sub-items (semua descendants)
     */
    public function allSubItems(): HasMany
    {
        return $this->subItems()->with('allSubItems');
    }

    /**
     * Scope untuk item root (tanpa parent)
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope untuk items dalam kategori tertentu
     */
    public function scopeByKategori($query, int $kategoriId)
    {
        return $query->where('rubrik_kategori_id', $kategoriId);
    }

    /**
     * Cek apakah item memiliki sub-items
     */
    public function hasSubItems(): bool
    {
        return $this->subItems()->count() > 0;
    }

    /**
     * Cek apakah item adalah root (tidak punya parent)
     */
    public function isRoot(): bool
    {
        return $this->parent_id === null;
    }

    /**
     * Cek apakah bisa menambah sub-item (belum mencapai max level)
     */
    public function canAddSubItem(): bool
    {
        return $this->level < self::MAX_LEVEL;
    }

    /**
     * Get nama kategori
     */
    public function getNamaKategoriAttribute(): string
    {
        return $this->kategori?->nama ?? '-';
    }

    /**
     * Hitung persentase efektif (relatif ke root/periode)
     * Untuk sub-item, persentasenya adalah relatif terhadap parent
     */
    public function getEffectivePercentageAttribute(): float
    {
        if ($this->isRoot()) {
            return (float) $this->persentase;
        }
        
        // Sub-item: (persentase sub / 100) * persentase parent
        return ($this->persentase / 100) * $this->parent->effective_percentage;
    }

    /**
     * Get total persentase semua sub-items
     */
    public function getTotalSubItemsPercentageAttribute(): float
    {
        return $this->subItems()->sum('persentase');
    }

    /**
     * Cek apakah total sub-items valid (harus 100% jika ada sub-items)
     */
    public function isSubItemsValid(): bool
    {
        if (!$this->hasSubItems()) {
            return true;
        }
        return $this->total_sub_items_percentage == 100;
    }

    // =====================================
    // Legacy Support - Backward Compatibility
    // =====================================

    /**
     * Cek apakah item ini untuk UTS (backward compatibility)
     */
    public function isUts(): bool
    {
        return $this->kategori?->kode === 'uts';
    }

    /**
     * Cek apakah item ini untuk UAS (backward compatibility)
     */
    public function isUas(): bool
    {
        return $this->kategori?->kode === 'uas';
    }
}
