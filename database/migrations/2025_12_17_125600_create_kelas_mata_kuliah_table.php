<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pivot table untuk menghubungkan Kelas dengan Mata Kuliah terkait proyek PBL.
     * Setiap kelas bisa memiliki beberapa mata kuliah terkait (misal: Web, TPK, Integrasi Sistem).
     * Rubrik dipilih per kelas, jadi semua kelompok dalam 1 kelas menggunakan rubrik yang sama.
     */
    public function up(): void
    {
        Schema::create('kelas_mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_room_id')->constrained('ruang_kelas')->cascadeOnDelete();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->foreignId('rubrik_penilaian_id')->nullable()->constrained('rubrik_penilaian')->nullOnDelete();
            $table->timestamps();
            
            // Satu kelas hanya bisa punya satu relasi dengan satu mata kuliah
            $table->unique(['class_room_id', 'mata_kuliah_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas_mata_kuliah');
    }
};
