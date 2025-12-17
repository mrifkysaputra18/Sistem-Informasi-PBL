<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom dosen per kelas-mata kuliah
     * Memungkinkan dosen berbeda di setiap kelas untuk mata kuliah yang sama
     */
    public function up(): void
    {
        Schema::table('kelas_mata_kuliah', function (Blueprint $table) {
            $table->foreignId('dosen_sebelum_uts_id')
                ->nullable()
                ->after('rubrik_penilaian_id')
                ->constrained('pengguna')
                ->nullOnDelete();
            
            $table->foreignId('dosen_sesudah_uts_id')
                ->nullable()
                ->after('dosen_sebelum_uts_id')
                ->constrained('pengguna')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('kelas_mata_kuliah', function (Blueprint $table) {
            $table->dropForeign(['dosen_sebelum_uts_id']);
            $table->dropForeign(['dosen_sesudah_uts_id']);
            $table->dropColumn(['dosen_sebelum_uts_id', 'dosen_sesudah_uts_id']);
        });
    }
};
