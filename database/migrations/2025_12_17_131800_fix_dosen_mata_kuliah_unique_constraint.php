<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Fix: Unique constraint harus menyertakan periode agar satu dosen
     * bisa mengajar mata kuliah yang sama di periode berbeda (sebelum/sesudah UTS)
     */
    public function up(): void
    {
        // Hapus data duplikat jika ada (keep yang terbaru)
        DB::statement('
            DELETE d1 FROM dosen_mata_kuliah d1
            INNER JOIN dosen_mata_kuliah d2
            WHERE d1.id < d2.id
            AND d1.dosen_id = d2.dosen_id
            AND d1.mata_kuliah_id = d2.mata_kuliah_id
        ');
        
        // Drop unique constraint lama menggunakan raw SQL
        try {
            DB::statement('ALTER TABLE dosen_mata_kuliah DROP INDEX dosen_mata_kuliah_dosen_id_mata_kuliah_id_unique');
        } catch (\Exception $e) {
            // Ignore if index doesn't exist
        }
        
        // Buat unique constraint baru dengan periode
        DB::statement('ALTER TABLE dosen_mata_kuliah ADD UNIQUE INDEX dosen_matkul_periode_unique (dosen_id, mata_kuliah_id, periode)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            DB::statement('ALTER TABLE dosen_mata_kuliah DROP INDEX dosen_matkul_periode_unique');
        } catch (\Exception $e) {
            // Ignore
        }
        
        // Kembalikan constraint lama
        DB::statement('ALTER TABLE dosen_mata_kuliah ADD UNIQUE INDEX dosen_mata_kuliah_dosen_id_mata_kuliah_id_unique (dosen_id, mata_kuliah_id)');
    }
};
