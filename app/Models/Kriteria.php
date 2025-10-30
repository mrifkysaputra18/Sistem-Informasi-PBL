<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{HasMany, BelongsTo};

class Kriteria extends Model
{
    protected $table = 'kriteria';

    protected $fillable = ['subject_id', 'nama', 'bobot', 'tipe', 'segment'];
    
    /**
     * Get the subject
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'subject_id');
    }
    
    /**
     * Get group scores
     */
    public function groupScores(): HasMany
    {
        return $this->hasMany(NilaiKelompok::class, 'criterion_id');
    }
}






