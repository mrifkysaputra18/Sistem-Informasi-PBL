<?php
// Model AhpComparison - Menyimpan perbandingan berpasangan AHP (tabel: ahp_comparisons)

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AhpComparison extends Model
{
    // Kolom yang boleh diisi
    protected $fillable = [
        'segment',        // grup atau mahasiswa
        'criterion_a_id', // ID kriteria A
        'criterion_b_id', // ID kriteria B
        'value'           // Nilai perbandingan (skala 1-9)
    ];

    // Konversi nilai ke desimal 4 digit
    protected $casts = [
        'value' => 'decimal:4',
    ];

    // Relasi: Perbandingan ini milik Kriteria A (Banyak-ke-Satu)
    public function criterionA(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'criterion_a_id');
    }

    // Relasi: Perbandingan ini milik Kriteria B (Banyak-ke-Satu)
    public function criterionB(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'criterion_b_id');
    }
}
