<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Model Pengaturan Sistem
 * Menyimpan pengaturan sistem dalam format kunci-nilai
 */
class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
    ];

    /**
     * Ambil nilai pengaturan berdasarkan kunci
     * 
     * @param string $kunci Kunci pengaturan
     * @param mixed $default Nilai default jika tidak ditemukan
     * @return mixed
     */
    public static function get(string $kunci, $default = null)
    {
        $pengaturan = Cache::remember("setting_{$kunci}", 3600, function () use ($kunci) {
            return self::where('key', $kunci)->first();
        });

        if (!$pengaturan) {
            return $default;
        }

        return self::konversiNilai($pengaturan->value, $pengaturan->type);
    }

    /**
     * Simpan nilai pengaturan
     * 
     * @param string $kunci Kunci pengaturan
     * @param mixed $nilai Nilai yang disimpan
     * @param string $tipe Tipe data (string, boolean, integer, float, json, array)
     * @param string $grup Grup pengaturan
     * @param string|null $deskripsi Deskripsi pengaturan
     * @return self
     */
    public static function set(string $kunci, $nilai, string $tipe = 'string', string $grup = 'umum', string $deskripsi = null): self
    {
        $pengaturan = self::updateOrCreate(
            ['key' => $kunci],
            [
                'value' => is_array($nilai) ? json_encode($nilai) : $nilai,
                'type' => $tipe,
                'group' => $grup,
                'description' => $deskripsi,
            ]
        );

        Cache::forget("setting_{$kunci}");

        return $pengaturan;
    }

    /**
     * Ambil semua pengaturan berdasarkan grup
     * 
     * @param string $grup Nama grup
     * @return array
     */
    public static function getByGroup(string $grup): array
    {
        $daftarPengaturan = self::where('group', $grup)->get();
        
        $hasil = [];
        foreach ($daftarPengaturan as $pengaturan) {
            $hasil[$pengaturan->key] = self::konversiNilai($pengaturan->value, $pengaturan->type);
        }
        
        return $hasil;
    }

    /**
     * Konversi nilai berdasarkan tipe data
     * 
     * @param mixed $nilai Nilai yang akan dikonversi
     * @param string $tipe Tipe data target
     * @return mixed
     */
    protected static function konversiNilai($nilai, string $tipe)
    {
        // Jika nilai kosong, kembalikan default berdasarkan tipe
        if ($nilai === null || $nilai === '') {
            return match ($tipe) {
                'boolean' => false,
                'integer' => 0,
                'float' => 0.0,
                'json', 'array' => [],
                default => null,
            };
        }

        // Konversi nilai sesuai tipe
        return match ($tipe) {
            'boolean' => filter_var($nilai, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $nilai,
            'float' => (float) $nilai,
            'json', 'array' => json_decode($nilai, true) ?? [],
            default => $nilai,
        };
    }
}
