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
     * Get rubrik item
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
     * Menggunakan sistem kategori fleksibel
     * 
     * @param int $userId
     * @param int $kelasMataKuliahId
     * @return float Total nilai (0-100)
     */
    public static function hitungTotalNilai(int $userId, int $kelasMataKuliahId): float
    {
        // Ambil kelas mata kuliah dengan rubrik penilaian dan kategori
        $kelasMataKuliah = KelasMataKuliah::with('rubrikPenilaian.kategoris')->find($kelasMataKuliahId);
        $rubrik = $kelasMataKuliah?->rubrikPenilaian;
        
        // Jika tidak ada rubrik, return 0
        if (!$rubrik) {
            return 0;
        }
        
        $totalNilai = 0;
        
        // Iterasi setiap kategori
        foreach ($rubrik->kategoris as $kategori) {
            // Hitung nilai untuk kategori ini
            $nilaiKategori = self::hitungNilaiKategori($userId, $kelasMataKuliahId, $kategori->id);
            
            // Terapkan bobot kategori
            $totalNilai += ($nilaiKategori * $kategori->bobot / 100);
        }
        
        return round($totalNilai, 2);
    }

    /**
     * Hitung nilai untuk kategori tertentu
     * 
     * @param int $userId
     * @param int $kelasMataKuliahId
     * @param int $kategoriId
     * @return float Nilai kategori (0-100)
     */
    public static function hitungNilaiKategori(int $userId, int $kelasMataKuliahId, int $kategoriId): float
    {
        return self::where('user_id', $userId)
            ->where('kelas_mata_kuliah_id', $kelasMataKuliahId)
            ->whereHas('rubrikItem', fn($q) => $q->where('rubrik_kategori_id', $kategoriId))
            ->with('rubrikItem')
            ->get()
            ->sum('nilai_terbobot');
    }
    
    /**
     * Get detail nilai per kategori untuk mahasiswa
     * 
     * @param int $userId
     * @param int $kelasMataKuliahId
     * @return array
     */
    public static function getDetailNilai(int $userId, int $kelasMataKuliahId): array
    {
        $kelasMataKuliah = KelasMataKuliah::with('rubrikPenilaian.kategoris')->find($kelasMataKuliahId);
        $rubrik = $kelasMataKuliah?->rubrikPenilaian;
        
        if (!$rubrik) {
            return [
                'kategoris' => [],
                'total_nilai' => 0,
            ];
        }
        
        $kategoriDetails = [];
        $totalNilai = 0;
        
        foreach ($rubrik->kategoris as $kategori) {
            $nilaiRaw = self::hitungNilaiKategori($userId, $kelasMataKuliahId, $kategori->id);
            $nilaiTerbobot = $nilaiRaw * $kategori->bobot / 100;
            $totalNilai += $nilaiTerbobot;
            
            $kategoriDetails[] = [
                'id' => $kategori->id,
                'nama' => $kategori->nama,
                'kode' => $kategori->kode,
                'bobot' => $kategori->bobot,
                'nilai_raw' => round($nilaiRaw, 2),
                'nilai_terbobot' => round($nilaiTerbobot, 2),
            ];
        }
        
        return [
            'kategoris' => $kategoriDetails,
            'total_nilai' => round($totalNilai, 2),
        ];
    }

    // =====================================
    // Legacy Support - Backward Compatibility
    // =====================================
    
    /**
     * Hitung total nilai UTS saja untuk mahasiswa (backward compatibility)
     */
    public static function hitungNilaiUts(int $userId, int $kelasMataKuliahId): float
    {
        $kelasMataKuliah = KelasMataKuliah::with('rubrikPenilaian.kategoris')->find($kelasMataKuliahId);
        $rubrik = $kelasMataKuliah?->rubrikPenilaian;
        $kategoriUts = $rubrik?->kategoris->where('kode', 'uts')->first();
        
        if (!$kategoriUts) {
            return 0;
        }
        
        return self::hitungNilaiKategori($userId, $kelasMataKuliahId, $kategoriUts->id);
    }
    
    /**
     * Hitung total nilai UAS saja untuk mahasiswa (backward compatibility)
     */
    public static function hitungNilaiUas(int $userId, int $kelasMataKuliahId): float
    {
        $kelasMataKuliah = KelasMataKuliah::with('rubrikPenilaian.kategoris')->find($kelasMataKuliahId);
        $rubrik = $kelasMataKuliah?->rubrikPenilaian;
        $kategoriUas = $rubrik?->kategoris->where('kode', 'uas')->first();
        
        if (!$kategoriUas) {
            return 0;
        }
        
        return self::hitungNilaiKategori($userId, $kelasMataKuliahId, $kategoriUas->id);
    }
}
