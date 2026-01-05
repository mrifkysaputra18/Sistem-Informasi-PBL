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
        'created_by',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'semester' => 'integer',
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

    /**
     * Relasi ke semua items (flat)
     */
    public function items(): HasMany
    {
        return $this->hasMany(RubrikItem::class, 'rubrik_penilaian_id')->orderBy('urutan');
    }

    /**
     * Relasi ke kategori penilaian
     */
    public function kategoris(): HasMany
    {
        return $this->hasMany(RubrikKategori::class, 'rubrik_penilaian_id')->orderBy('urutan');
    }

    /**
     * Get kategori dengan items (eager loaded)
     */
    public function kategorisWithItems(): HasMany
    {
        return $this->kategoris()->with('itemsWithSubItems');
    }

    /**
     * Hitung total bobot semua kategori (harus 100%)
     */
    public function getTotalBobotKategoriAttribute(): float
    {
        return $this->kategoris()->sum('bobot');
    }

    /**
     * Cek apakah rubrik lengkap:
     * - Total bobot kategori = 100%
     * - Setiap kategori memiliki total item = 100%
     */
    public function isComplete(): bool
    {
        // Total bobot kategori harus 100%
        if ($this->total_bobot_kategori != 100) {
            return false;
        }

        // Setiap kategori harus lengkap
        foreach ($this->kategoris as $kategori) {
            if (!$kategori->isComplete()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get status validasi detail
     */
    public function getValidationStatus(): array
    {
        $kategoriStatus = [];
        foreach ($this->kategoris as $kategori) {
            $kategoriStatus[] = [
                'nama' => $kategori->nama,
                'bobot' => $kategori->bobot,
                'total_items' => $kategori->total_persentase_items,
                'valid' => $kategori->isComplete(),
            ];
        }

        return [
            'bobot_valid' => $this->total_bobot_kategori == 100,
            'total_bobot' => $this->total_bobot_kategori,
            'kategoris' => $kategoriStatus,
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

    // =====================================
    // Legacy Support - Backward Compatibility
    // =====================================

    /**
     * Get kategori UTS (backward compatibility)
     */
    public function kategoriUts()
    {
        return $this->kategoris()->where('kode', 'uts')->first();
    }

    /**
     * Get kategori UAS (backward compatibility)
     */
    public function kategoriUas()
    {
        return $this->kategoris()->where('kode', 'uas')->first();
    }

    /**
     * Get root items untuk kategori UTS (backward compatibility)
     */
    public function itemsUts(): HasMany
    {
        $kategoriUts = $this->kategoriUts();
        if (!$kategoriUts) {
            return $this->hasMany(RubrikItem::class, 'rubrik_penilaian_id')->whereRaw('1=0');
        }
        return $this->hasMany(RubrikItem::class, 'rubrik_penilaian_id')
            ->where('rubrik_kategori_id', $kategoriUts->id)
            ->whereNull('parent_id')
            ->orderBy('urutan');
    }

    /**
     * Get root items untuk kategori UAS (backward compatibility)
     */
    public function itemsUas(): HasMany
    {
        $kategoriUas = $this->kategoriUas();
        if (!$kategoriUas) {
            return $this->hasMany(RubrikItem::class, 'rubrik_penilaian_id')->whereRaw('1=0');
        }
        return $this->hasMany(RubrikItem::class, 'rubrik_penilaian_id')
            ->where('rubrik_kategori_id', $kategoriUas->id)
            ->whereNull('parent_id')
            ->orderBy('urutan');
    }

    /**
     * Get bobot UTS (backward compatibility)
     */
    public function getBobotUtsAttribute(): float
    {
        return $this->kategoriUts()?->bobot ?? 0;
    }

    /**
     * Get bobot UAS (backward compatibility)
     */
    public function getBobotUasAttribute(): float
    {
        return $this->kategoriUas()?->bobot ?? 0;
    }

    /**
     * Hitung total persentase UTS (backward compatibility)
     */
    public function getTotalPersentaseUtsAttribute(): float
    {
        return $this->kategoriUts()?->total_persentase_items ?? 0;
    }

    /**
     * Hitung total persentase UAS (backward compatibility)
     */
    public function getTotalPersentaseUasAttribute(): float
    {
        return $this->kategoriUas()?->total_persentase_items ?? 0;
    }

    /**
     * Legacy support - total persentase rata-rata
     */
    public function getTotalPersentaseAttribute(): float
    {
        $kategoris = $this->kategoris;
        if ($kategoris->isEmpty()) {
            return 0;
        }
        
        $total = 0;
        foreach ($kategoris as $kategori) {
            $total += $kategori->total_persentase_items;
        }
        return $total / $kategoris->count();
    }
}
