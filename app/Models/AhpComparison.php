<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AhpComparison extends Model
{
    protected $fillable = [
        'segment',
        'criterion_a_id',
        'criterion_b_id',
        'value'
    ];

    protected $casts = [
        'value' => 'decimal:4',
    ];

    /**
     * Get criterion A
     */
    public function criterionA(): BelongsTo
    {
        return $this->belongsTo(Criterion::class, 'criterion_a_id');
    }

    /**
     * Get criterion B
     */
    public function criterionB(): BelongsTo
    {
        return $this->belongsTo(Criterion::class, 'criterion_b_id');
    }
}

