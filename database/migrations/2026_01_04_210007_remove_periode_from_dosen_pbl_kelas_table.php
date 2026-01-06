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
     * Menghapus field 'periode' dari tabel dosen_pbl_kelas
     * karena requirement baru: 1 Dosen PBL untuk full semester (tidak ada rolling)
     */
    public function up(): void
    {
        // Hapus data duplikat terlebih dahulu (keep sebelum_uts only)
        DB::statement("
            DELETE FROM dosen_pbl_kelas 
            WHERE periode = 'sesudah_uts' 
            AND CONCAT(dosen_id, '-', class_room_id) IN (
                SELECT CONCAT(dosen_id, '-', class_room_id)
                FROM (SELECT * FROM dosen_pbl_kelas) AS temp
                WHERE periode = 'sebelum_uts'
            )
        ");
        
        Schema::table('dosen_pbl_kelas', function (Blueprint $table) {
            // Hapus kolom periode
            $table->dropColumn('periode');
        });
        
        // Tambah unique constraint: 1 dosen per kelas
        Schema::table('dosen_pbl_kelas', function (Blueprint $table) {
            $table->unique(['dosen_id', 'class_room_id'], 'unique_dosen_class');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosen_pbl_kelas', function (Blueprint $table) {
            // Drop unique constraint
            $table->dropUnique('unique_dosen_class');
            
            // Tambah kembali kolom periode
            $table->string('periode')->nullable()->after('class_room_id');
        });
    }
};
