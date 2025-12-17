<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel pivot untuk assign Dosen PBL ke Kelas per Periode
     * 
     * Dosen PBL bisa:
     * - > 1 orang per kelas
     * - Berbeda antara sebelum UTS dan sesudah UTS
     * - Periode aktif ditentukan manual oleh admin
     */
    public function up(): void
    {
        Schema::create('dosen_pbl_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->constrained('pengguna')->cascadeOnDelete();
            $table->foreignId('class_room_id')->constrained('ruang_kelas')->cascadeOnDelete();
            $table->enum('periode', ['sebelum_uts', 'sesudah_uts']);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Unique: 1 dosen - 1 kelas - 1 periode
            $table->unique(['dosen_id', 'class_room_id', 'periode'], 'dosen_pbl_kelas_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dosen_pbl_kelas');
    }
};
