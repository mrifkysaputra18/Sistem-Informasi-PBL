<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{HasMany, BelongsTo};

class Criterion extends Model
{
    protected $fillable = ['subject_id', 'nama', 'bobot', 'tipe', 'segment'];
    
    /**
     * Get the subject
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
    
    /**
     * Get group scores
     */
    public function groupScores(): HasMany
    {
        return $this->hasMany(GroupScore::class, 'criterion_id');
    }
}
