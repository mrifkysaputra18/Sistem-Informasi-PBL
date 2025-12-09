<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RubrikItem extends Model
{
    protected $table = 'rubrik_item';

    protected $fillable = [
        'rubrik_penilaian_id',
        'nama',
        'persentase',
        'deskripsi',
        'urutan',
    ];

    protected $casts = [
        'persentase' => 'decimal:2',
        'urutan' => 'integer',
    ];

    public function rubrikPenilaian(): BelongsTo
    {
        return $this->belongsTo(RubrikPenilaian::class, 'rubrik_penilaian_id');
    }
}
