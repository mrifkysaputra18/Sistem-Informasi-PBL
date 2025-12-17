<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tambah kolom bobot UTS dan UAS ke tabel rubrik_penilaian
        Schema::table('rubrik_penilaian', function (Blueprint $table) {
            $table->decimal('bobot_uts', 5, 2)->default(50)->after('semester')
                  ->comment('Persentase bobot UTS (0-100)');
            $table->decimal('bobot_uas', 5, 2)->default(50)->after('bobot_uts')
                  ->comment('Persentase bobot UAS (0-100), total UTS+UAS harus 100');
        });
        
        // Tambah kolom periode_ujian ke tabel rubrik_item
        Schema::table('rubrik_item', function (Blueprint $table) {
            $table->enum('periode_ujian', ['uts', 'uas'])->default('uts')->after('rubrik_penilaian_id')
                  ->comment('Item ini untuk periode UTS atau UAS');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rubrik_penilaian', function (Blueprint $table) {
            $table->dropColumn(['bobot_uts', 'bobot_uas']);
        });
        
        Schema::table('rubrik_item', function (Blueprint $table) {
            $table->dropColumn('periode_ujian');
        });
    }
};
