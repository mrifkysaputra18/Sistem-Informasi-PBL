<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menghapus kolom semester dari tabel ruang_kelas karena semester
     * sekarang didapat dari relasi ke periode_akademik.semester_number
     */
    public function up(): void
    {
        Schema::table('ruang_kelas', function (Blueprint $table) {
            $table->dropColumn('semester');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ruang_kelas', function (Blueprint $table) {
            $table->string('semester')->nullable()->after('code');
        });
    }
};
