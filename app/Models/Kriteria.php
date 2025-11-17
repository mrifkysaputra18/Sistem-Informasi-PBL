<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kriteria extends Model
{
    protected $table = 'kriteria';

    protected $fillable = ['nama', 'bobot', 'tipe', 'segment'];
    
    /**
     * Get group scores
     */
    public function groupScores(): HasMany
    {
        return $this->hasMany(NilaiKelompok::class, 'criterion_id');
    }
}
