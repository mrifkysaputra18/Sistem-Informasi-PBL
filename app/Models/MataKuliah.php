<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class MataKuliah extends Model
{
    protected $table = 'mata_kuliah';

    const PERIODE_SEBELUM_UTS = 'sebelum_uts';
    const PERIODE_SESUDAH_UTS = 'sesudah_uts';

    const MINGGU_SEBELUM_UTS = [1, 2, 3, 4, 5, 6, 7, 8];
    const MINGGU_SESUDAH_UTS = [9, 10, 11, 12, 13, 14, 15, 16];

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
                    ->withPivot('periode')
                    ->withTimestamps();
    }

    /**
     * Get dosen sebelum UTS
     */
    public function dosenSebelumUts()
    {
        return $this->dosens()->wherePivot('periode', self::PERIODE_SEBELUM_UTS)->first();
    }

    /**
     * Get dosen sesudah UTS
     */
    public function dosenSesudahUts()
    {
        return $this->dosens()->wherePivot('periode', self::PERIODE_SESUDAH_UTS)->first();
    }

    /**
     * Get dosen by week number
     */
    public function getDosenByWeek(int $weekNumber): ?Pengguna
    {
        $periode = in_array($weekNumber, self::MINGGU_SEBELUM_UTS) 
            ? self::PERIODE_SEBELUM_UTS 
            : self::PERIODE_SESUDAH_UTS;
        
        return $this->dosens()->wherePivot('periode', $periode)->first();
    }

    /**
     * Sync dosen for both periods
     */
    public function syncDosens(?int $dosenSebelumUtsId, ?int $dosenSesudahUtsId): void
    {
        // Remove existing assignments
        DB::table('dosen_mata_kuliah')->where('mata_kuliah_id', $this->id)->delete();

        // Add dosen sebelum UTS
        if ($dosenSebelumUtsId) {
            DB::table('dosen_mata_kuliah')->insert([
                'dosen_id' => $dosenSebelumUtsId,
                'mata_kuliah_id' => $this->id,
                'periode' => self::PERIODE_SEBELUM_UTS,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Add dosen sesudah UTS
        if ($dosenSesudahUtsId) {
            DB::table('dosen_mata_kuliah')->insert([
                'dosen_id' => $dosenSesudahUtsId,
                'mata_kuliah_id' => $this->id,
                'periode' => self::PERIODE_SESUDAH_UTS,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
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

    /**
     * Get kelas yang menggunakan mata kuliah ini untuk proyek PBL
     */
    public function classRooms()
    {
        return $this->belongsToMany(RuangKelas::class, 'kelas_mata_kuliah', 'mata_kuliah_id', 'class_room_id')
            ->withPivot('rubrik_penilaian_id')
            ->withTimestamps();
    }

    /**
     * Get relasi kelas-mata kuliah
     */
    public function kelasMataKuliahs()
    {
        return $this->hasMany(KelasMataKuliah::class, 'mata_kuliah_id');
    }

    /**
     * Cek apakah dosen tertentu ditugaskan ke mata kuliah ini
     * Dosen dianggap ditugaskan jika ada di kelas_mata_kuliah sebagai dosen sebelum/sesudah UTS
     */
    public function isDosenAssigned(int $dosenId): bool
    {
        return $this->kelasMataKuliahs()
            ->where(function ($query) use ($dosenId) {
                $query->where('dosen_sebelum_uts_id', $dosenId)
                      ->orWhere('dosen_sesudah_uts_id', $dosenId);
            })
            ->exists();
    }
}
