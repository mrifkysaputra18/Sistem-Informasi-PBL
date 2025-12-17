<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model untuk menyimpan nilai mahasiswa per rubrik item.
 * Terintegrasi dengan SAW melalui RankingService.
 */
class NilaiRubrik extends Model
{
    protected $table = 'nilai_rubrik';

    protected $fillable = [
        'user_id',
        'kelas_mata_kuliah_id',
        'rubrik_item_id',
        'nilai',
        'catatan',
        'dinilai_oleh',
        'dinilai_pada',
    ];

    protected $casts = [
        'nilai' => 'decimal:2',
        'dinilai_pada' => 'datetime',
    ];

    /**
     * Get mahasiswa yang dinilai
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }

    /**
     * Get relasi kelas-mata kuliah
     */
    public function kelasMataKuliah(): BelongsTo
    {
        return $this->belongsTo(KelasMataKuliah::class, 'kelas_mata_kuliah_id');
    }

    /**
     * Get rubrik item (UTS, UAS, dll)
     */
    public function rubrikItem(): BelongsTo
    {
        return $this->belongsTo(RubrikItem::class, 'rubrik_item_id');
    }

    /**
     * Get dosen yang menilai
     */
    public function penilai(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'dinilai_oleh');
    }

    /**
     * Hitung nilai terbobot (nilai × persentase / 100)
     */
    public function getNilaiTerbobotAttribute(): float
    {
        $persentase = $this->rubrikItem?->persentase ?? 0;
        return round(($this->nilai * $persentase) / 100, 2);
    }

    /**
     * Get nama mata kuliah
     */
    public function getNamaMataKuliahAttribute(): string
    {
        return $this->kelasMataKuliah?->mataKuliah?->nama ?? '-';
    }

    /**
     * Get nama item rubrik
     */
    public function getNamaItemAttribute(): string
    {
        return $this->rubrikItem?->nama ?? '-';
    }

    /**
     * Scope untuk filter berdasarkan kelas
     */
    public function scopeByKelas($query, $classRoomId)
    {
        return $query->whereHas('kelasMataKuliah', function ($q) use ($classRoomId) {
            $q->where('class_room_id', $classRoomId);
        });
    }

    /**
     * Scope untuk filter berdasarkan mata kuliah
     */
    public function scopeByMataKuliah($query, $mataKuliahId)
    {
        return $query->whereHas('kelasMataKuliah', function ($q) use ($mataKuliahId) {
            $q->where('mata_kuliah_id', $mataKuliahId);
        });
    }

    /**
     * Hitung total nilai mahasiswa untuk satu kelas-mata kuliah
     * Mempertimbangkan bobot UTS dan UAS dari rubrik penilaian
     * 
     * @param int $userId
     * @param int $kelasMataKuliahId
     * @return float Total nilai (0-100)
     */
    public static function hitungTotalNilai(int $userId, int $kelasMataKuliahId): float
    {
        // Ambil kelas mata kuliah dengan rubrik penilaian
        $kelasMataKuliah = KelasMataKuliah::with('rubrikPenilaian')->find($kelasMataKuliahId);
        $rubrik = $kelasMataKuliah?->rubrikPenilaian;
        
        // Jika tidak ada rubrik, gunakan perhitungan lama
        if (!$rubrik) {
            $nilaiRubriks = self::where('user_id', $userId)
                ->where('kelas_mata_kuliah_id', $kelasMataKuliahId)
                ->with('rubrikItem')
                ->get();
            
            return round($nilaiRubriks->sum('nilai_terbobot'), 2);
        }
        
        // Hitung total nilai item UTS (persentase internal 0-100)
        $nilaiUts = self::where('user_id', $userId)
            ->where('kelas_mata_kuliah_id', $kelasMataKuliahId)
            ->whereHas('rubrikItem', fn($q) => $q->where('periode_ujian', 'uts'))
            ->with('rubrikItem')
            ->get()
            ->sum('nilai_terbobot');
        
        // Hitung total nilai item UAS (persentase internal 0-100)
        $nilaiUas = self::where('user_id', $userId)
            ->where('kelas_mata_kuliah_id', $kelasMataKuliahId)
            ->whereHas('rubrikItem', fn($q) => $q->where('periode_ujian', 'uas'))
            ->with('rubrikItem')
            ->get()
            ->sum('nilai_terbobot');
        
        // Terapkan bobot UTS dan UAS ke total
        // Contoh: UTS=40%, UAS=60%
        // Total = (nilaiUts × 40/100) + (nilaiUas × 60/100)
        $bobotUts = floatval($rubrik->bobot_uts);
        $bobotUas = floatval($rubrik->bobot_uas);
        
        $total = ($nilaiUts * $bobotUts / 100) + ($nilaiUas * $bobotUas / 100);
        
        return round($total, 2);
    }
    
    /**
     * Hitung total nilai UTS saja untuk mahasiswa
     * 
     * @param int $userId
     * @param int $kelasMataKuliahId
     * @return float Total nilai UTS (0-100 dari porsi UTS)
     */
    public static function hitungNilaiUts(int $userId, int $kelasMataKuliahId): float
    {
        return self::where('user_id', $userId)
            ->where('kelas_mata_kuliah_id', $kelasMataKuliahId)
            ->whereHas('rubrikItem', fn($q) => $q->where('periode_ujian', 'uts'))
            ->with('rubrikItem')
            ->get()
            ->sum('nilai_terbobot');
    }
    
    /**
     * Hitung total nilai UAS saja untuk mahasiswa
     * 
     * @param int $userId
     * @param int $kelasMataKuliahId
     * @return float Total nilai UAS (0-100 dari porsi UAS)
     */
    public static function hitungNilaiUas(int $userId, int $kelasMataKuliahId): float
    {
        return self::where('user_id', $userId)
            ->where('kelas_mata_kuliah_id', $kelasMataKuliahId)
            ->whereHas('rubrikItem', fn($q) => $q->where('periode_ujian', 'uas'))
            ->with('rubrikItem')
            ->get()
            ->sum('nilai_terbobot');
    }
    
    /**
     * Get detail nilai per item untuk mahasiswa
     * 
     * @param int $userId
     * @param int $kelasMataKuliahId
     * @return array
     */
    public static function getDetailNilai(int $userId, int $kelasMataKuliahId): array
    {
        $kelasMataKuliah = KelasMataKuliah::with('rubrikPenilaian')->find($kelasMataKuliahId);
        $rubrik = $kelasMataKuliah?->rubrikPenilaian;
        
        $nilaiUts = self::hitungNilaiUts($userId, $kelasMataKuliahId);
        $nilaiUas = self::hitungNilaiUas($userId, $kelasMataKuliahId);
        $totalNilai = self::hitungTotalNilai($userId, $kelasMataKuliahId);
        
        return [
            'bobot_uts' => $rubrik?->bobot_uts ?? 50,
            'bobot_uas' => $rubrik?->bobot_uas ?? 50,
            'nilai_uts_raw' => round($nilaiUts, 2),
            'nilai_uas_raw' => round($nilaiUas, 2),
            'nilai_uts_terbobot' => round($nilaiUts * ($rubrik?->bobot_uts ?? 50) / 100, 2),
            'nilai_uas_terbobot' => round($nilaiUas * ($rubrik?->bobot_uas ?? 50) / 100, 2),
            'total_nilai' => $totalNilai,
        ];
    }
}
