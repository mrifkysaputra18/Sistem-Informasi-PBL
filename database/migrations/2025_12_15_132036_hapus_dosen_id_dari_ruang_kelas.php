<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menghapus kolom dosen_id dari tabel ruang_kelas karena dosen
     * tidak lagi ditetapkan ke kelas tertentu - dosen bisa akses semua kelas.
     */
    public function up(): void
    {
        Schema::table('ruang_kelas', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['dosen_id']);
            // Hapus kolom dosen_id
            $table->dropColumn('dosen_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ruang_kelas', function (Blueprint $table) {
            // Tambahkan kembali kolom dosen_id jika rollback
            $table->foreignId('dosen_id')->nullable()->after('subject_id')->constrained('pengguna')->onDelete('set null');
        });
    }
};
