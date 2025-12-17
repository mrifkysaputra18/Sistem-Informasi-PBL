<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model pivot untuk relasi Kelas - Mata Kuliah.
 * Setiap kelas bisa memiliki beberapa mata kuliah terkait proyek PBL.
 * Dosen mata kuliah bisa berbeda per kelas dan per periode (sebelum/sesudah UTS).
 */
class KelasMataKuliah extends Model
{
    protected $table = 'kelas_mata_kuliah';

    protected $fillable = [
        'class_room_id',
        'mata_kuliah_id',
        'rubrik_penilaian_id',
        'dosen_sebelum_uts_id',
        'dosen_sesudah_uts_id',
    ];

    /**
     * Get kelas (ruang kelas)
     */
    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(RuangKelas::class, 'class_room_id');
    }

    /**
     * Get mata kuliah
     */
    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    /**
     * Get rubrik penilaian yang dipilih
     */
    public function rubrikPenilaian(): BelongsTo
    {
        return $this->belongsTo(RubrikPenilaian::class, 'rubrik_penilaian_id');
    }

    /**
     * Get dosen sebelum UTS (per kelas, bukan global)
     */
    public function dosenSebelumUts(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'dosen_sebelum_uts_id');
    }

    /**
     * Get dosen sesudah UTS (per kelas, bukan global)
     */
    public function dosenSesudahUts(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'dosen_sesudah_uts_id');
    }

    /**
     * Get semua nilai rubrik untuk relasi ini
     */
    public function nilaiRubriks(): HasMany
    {
        return $this->hasMany(NilaiRubrik::class, 'kelas_mata_kuliah_id');
    }

    /**
     * Cek apakah rubrik sudah lengkap (total persentase = 100%)
     */
    public function isRubrikComplete(): bool
    {
        if (!$this->rubrikPenilaian) {
            return false;
        }
        return $this->rubrikPenilaian->isComplete();
    }

    /**
     * Get semua mahasiswa dalam kelas ini
     */
    public function getMahasiswas()
    {
        return $this->classRoom?->students ?? collect();
    }

    /**
     * Cek apakah user adalah dosen untuk kelas-matkul ini (any period)
     */
    public function isDosen(int $userId): bool
    {
        return $this->dosen_sebelum_uts_id === $userId 
            || $this->dosen_sesudah_uts_id === $userId;
    }

    /**
     * Cek apakah user adalah dosen UTS
     */
    public function isDosenUts(int $userId): bool
    {
        return $this->dosen_sebelum_uts_id === $userId;
    }

    /**
     * Cek apakah user adalah dosen UAS
     */
    public function isDosenUas(int $userId): bool
    {
        return $this->dosen_sesudah_uts_id === $userId;
    }

    /**
     * Cek apakah user bisa input nilai UTS
     * - Harus dosen UTS
     * - Periode ujian UTS harus aktif di periode akademik
     */
    public function canInputNilaiUts(int $userId): bool
    {
        // Cek apakah user adalah dosen UTS
        if ($this->dosen_sebelum_uts_id !== $userId) {
            return false;
        }

        // Cek apakah periode ujian UTS sedang aktif
        $periodeAktif = PeriodeAkademik::getActiveWithExamPeriod();
        if (!$periodeAktif || !$periodeAktif->isUtsActive()) {
            return false;
        }

        return true;
    }

    /**
     * Cek apakah user bisa input nilai UAS
     * - Harus dosen UAS
     * - Periode ujian UAS harus aktif di periode akademik
     */
    public function canInputNilaiUas(int $userId): bool
    {
        // Cek apakah user adalah dosen UAS
        if ($this->dosen_sesudah_uts_id !== $userId) {
            return false;
        }

        // Cek apakah periode ujian UAS sedang aktif
        $periodeAktif = PeriodeAkademik::getActiveWithExamPeriod();
        if (!$periodeAktif || !$periodeAktif->isUasActive()) {
            return false;
        }

        return true;
    }

    /**
     * Cek apakah user bisa view nilai (dosen lama tetap bisa view)
     */
    public function canViewNilai(int $userId): bool
    {
        return $this->isDosen($userId);
    }

    /**
     * Get periode ujian yang bisa di-input oleh user berdasarkan status aktif
     * @return array ['uts', 'uas'] atau subset nya
     */
    public function getPeriodeYangBisaDiinput(int $userId): array
    {
        $periode = [];
        
        if ($this->canInputNilaiUts($userId)) {
            $periode[] = 'uts';
        }
        
        if ($this->canInputNilaiUas($userId)) {
            $periode[] = 'uas';
        }
        
        return $periode;
    }
}

