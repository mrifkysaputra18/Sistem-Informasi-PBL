<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataKuliah extends Model
{
    protected $table = 'mata_kuliah';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'sks',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sks' => 'integer',
    ];

    public function dosens(): BelongsToMany
    {
        return $this->belongsToMany(Pengguna::class, 'dosen_mata_kuliah', 'mata_kuliah_id', 'dosen_id')
                    ->withTimestamps();
    }

    public function rubrikPenilaians(): HasMany
    {
        return $this->hasMany(RubrikPenilaian::class, 'mata_kuliah_id');
    }

    public function activeRubrik()
    {
        return $this->rubrikPenilaians()->where('is_active', true)->first();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
