<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah academic_period_id ke dosen_pbl_kelas dan dosen_mata_kuliah
     * Agar data tidak hilang jika kelas/matkul dihapus
     */
    public function up(): void
    {
        // Update dosen_pbl_kelas
        Schema::table('dosen_pbl_kelas', function (Blueprint $table) {
            $table->foreignId('academic_period_id')
                ->nullable()
                ->after('is_active')
                ->constrained('periode_akademik')
                ->nullOnDelete();
        });

        // Update dosen_mata_kuliah
        Schema::table('dosen_mata_kuliah', function (Blueprint $table) {
            $table->foreignId('academic_period_id')
                ->nullable()
                ->after('periode')
                ->constrained('periode_akademik')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('dosen_pbl_kelas', function (Blueprint $table) {
            $table->dropForeign(['academic_period_id']);
            $table->dropColumn('academic_period_id');
        });

        Schema::table('dosen_mata_kuliah', function (Blueprint $table) {
            $table->dropForeign(['academic_period_id']);
            $table->dropColumn('academic_period_id');
        });
    }
};
