<?php
// Model Kriteria - Mengelola data kriteria penilaian (tabel: kriteria)

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kriteria extends Model
{
    protected $table = 'kriteria'; // Nama tabel di database

    // Kolom yang boleh diisi: nama, bobot, tipe (benefit/cost), segment (group/student)
    protected $fillable = ['nama', 'bobot', 'tipe', 'segment'];
    
    // Relasi: Satu kriteria punya banyak nilai kelompok (Satu-ke-Banyak)
    public function groupScores(): HasMany
    {
        return $this->hasMany(NilaiKelompok::class, 'criterion_id');
    }
}
