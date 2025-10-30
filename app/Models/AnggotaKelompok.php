<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnggotaKelompok extends Model
{
    protected $table = 'anggota_kelompok';

    protected $fillable = [
        'group_id',
        'user_id',
        'is_leader',
        'status'
    ];

    protected $casts = [
        'is_leader' => 'boolean',
    ];

    /**
     * Get the group
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Kelompok::class, 'group_id');
    }

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }
}





