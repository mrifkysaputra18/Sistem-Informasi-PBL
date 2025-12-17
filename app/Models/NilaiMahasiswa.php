<?php
// Model NilaiMahasiswa - Menyimpan nilai mahasiswa per kriteria (tabel: nilai_mahasiswa)

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiMahasiswa extends Model
{
    protected $table = 'nilai_mahasiswa'; // Nama tabel

    // Kolom yang boleh diisi
    protected $fillable = ['user_id', 'criterion_id', 'skor'];

    // Konversi skor ke desimal 2 digit
    protected $casts = [
        'skor' => 'decimal:2',
    ];

    // Relasi: Nilai ini milik satu mahasiswa (Banyak-ke-Satu)
    public function user(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }

    // Relasi: Nilai ini untuk satu kriteria (Banyak-ke-Satu)
    public function criterion(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'criterion_id');
    }
}
