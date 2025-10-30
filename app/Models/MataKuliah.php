<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MataKuliah extends Model
{
    protected $table = 'mata_kuliah';

    protected $fillable = [
        'academic_period_id',
        'code',
        'name',
        'description',
        'is_pbl_related',
        'is_active'
    ];

    protected $casts = [
        'is_pbl_related' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the academic period that owns the subject
     */
    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(PeriodeAkademik::class, 'academic_period_id');
    }

    /**
     * Get class rooms for this subject
     */
    public function classRooms(): HasMany
    {
        return $this->hasMany(RuangKelas::class, 'subject_id');
    }

    /**
     * Get criteria for this subject
     */
    public function criteria(): HasMany
    {
        return $this->hasMany(Kriteria::class, 'subject_id');
    }
}








