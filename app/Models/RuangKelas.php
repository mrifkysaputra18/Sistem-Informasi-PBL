<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RuangKelas extends Model
{
    use HasFactory;
    
    protected $table = 'ruang_kelas';

    protected $fillable = [
        'name',
        'code',
        'academic_period_id',
        'program_studi',
        'max_groups',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'max_groups' => 'integer',
    ];

    /**
     * Get the academic period that owns the class
     */
    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(PeriodeAkademik::class, 'academic_period_id');
    }

    /**
     * Get groups for this class
     */
    public function groups(): HasMany
    {
        return $this->hasMany(Kelompok::class, 'class_room_id');
    }

    /**
     * Get students in this class
     */
    public function students(): HasMany
    {
        return $this->hasMany(Pengguna::class, 'class_room_id')->where('role', 'mahasiswa');
    }

    /**
     * Get active groups count
     */
    public function getActiveGroupsCountAttribute()
    {
        return $this->groups()->count();
    }

    /**
     * Check if class can have more groups
     */
    public function canAddGroup(): bool
    {
        return $this->groups()->count() < $this->max_groups;
    }

    /**
     * Check if class is full (reached max groups)
     */
    public function isFull(): bool
    {
        return $this->groups()->count() >= $this->max_groups;
    }

    /**
     * Get mata kuliah terkait proyek PBL untuk kelas ini
     */
    public function mataKuliahs()
    {
        return $this->belongsToMany(MataKuliah::class, 'kelas_mata_kuliah', 'class_room_id', 'mata_kuliah_id')
            ->withPivot('rubrik_penilaian_id')
            ->withTimestamps();
    }

    /**
     * Get relasi kelas-mata kuliah (untuk akses rubrik)
     */
    public function kelasMataKuliahs()
    {
        return $this->hasMany(KelasMataKuliah::class, 'class_room_id');
    }

    /**
     * Get semua Dosen PBL untuk kelas ini (semua periode)
     */
    public function dosenPblKelas()
    {
        return $this->hasMany(DosenPblKelas::class, 'class_room_id');
    }

    /**
     * Get Dosen PBL yang aktif (all periods)
     */
    public function dosenPbls()
    {
        return $this->belongsToMany(Pengguna::class, 'dosen_pbl_kelas', 'class_room_id', 'dosen_id')
            ->withPivot('periode', 'is_active')
            ->withTimestamps();
    }

    /**
     * Get Dosen PBL sebelum UTS
     */
    public function dosenPblSebelumUts()
    {
        return $this->dosenPbls()->wherePivot('periode', 'sebelum_uts')->wherePivot('is_active', true);
    }

    /**
     * Get Dosen PBL sesudah UTS
     */
    public function dosenPblSesudahUts()
    {
        return $this->dosenPbls()->wherePivot('periode', 'sesudah_uts')->wherePivot('is_active', true);
    }

    /**
     * Cek apakah user adalah Dosen PBL di kelas ini (any active period)
     */
    public function isDosenPbl(int $userId): bool
    {
        return $this->dosenPblKelas()
            ->where('dosen_id', $userId)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Cek apakah user adalah Dosen PBL di periode tertentu
     */
    public function isDosenPblPeriode(int $userId, string $periode): bool
    {
        return $this->dosenPblKelas()
            ->where('dosen_id', $userId)
            ->where('periode', $periode)
            ->where('is_active', true)
            ->exists();
    }
}
