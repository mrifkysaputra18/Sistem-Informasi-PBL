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

    public function items(): HasMany
    {
        return $this->hasMany(RubrikItem::class, 'rubrik_penilaian_id')->orderBy('urutan');
    }

    public function getTotalPersentaseAttribute(): float
    {
        return $this->items()->sum('persentase');
    }

    public function isComplete(): bool
    {
        return $this->total_persentase == 100;
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
}
