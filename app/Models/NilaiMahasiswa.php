<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiMahasiswa extends Model
{
    protected $table = 'nilai_mahasiswa';

    protected $fillable = ['user_id', 'criterion_id', 'skor'];

    protected $casts = [
        'skor' => 'decimal:2',
    ];

    /**
     * Get the user (mahasiswa)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }

    /**
     * Get the criterion
     */
    public function criterion(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'criterion_id');
    }
}








