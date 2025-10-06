<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentScore extends Model
{
    protected $fillable = ['user_id', 'criterion_id', 'skor'];

    protected $casts = [
        'skor' => 'decimal:2',
    ];

    /**
     * Get the user (mahasiswa)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the criterion
     */
    public function criterion(): BelongsTo
    {
        return $this->belongsTo(Criterion::class);
    }
}

