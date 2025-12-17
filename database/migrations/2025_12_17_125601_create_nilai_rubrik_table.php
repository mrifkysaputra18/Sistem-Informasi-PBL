<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel untuk menyimpan nilai mahasiswa per rubrik item.
     * Nilai dihitung: total = sum(nilai × persentase / 100) untuk setiap item rubrik.
     * Terintegrasi dengan SAW melalui RankingService.
     */
    public function up(): void
    {
        Schema::create('nilai_rubrik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('pengguna')->cascadeOnDelete(); // Mahasiswa yang dinilai
            $table->foreignId('kelas_mata_kuliah_id')->constrained('kelas_mata_kuliah')->cascadeOnDelete(); // Relasi ke pivot
            $table->foreignId('rubrik_item_id')->constrained('rubrik_item')->cascadeOnDelete(); // Item rubrik (UTS, UAS, dll)
            $table->decimal('nilai', 5, 2)->default(0); // Nilai 0-100
            $table->text('catatan')->nullable(); // Catatan dari dosen
            $table->foreignId('dinilai_oleh')->constrained('pengguna'); // Dosen yang menilai
            $table->timestamp('dinilai_pada')->useCurrent();
            $table->timestamps();
            
            // Satu mahasiswa hanya bisa punya satu nilai per item rubrik per kelas-matkul
            $table->unique(['user_id', 'kelas_mata_kuliah_id', 'rubrik_item_id'], 'nilai_rubrik_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_rubrik');
    }
};
