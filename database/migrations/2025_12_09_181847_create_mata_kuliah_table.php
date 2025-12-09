<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Mata Kuliah
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama', 100);
            $table->text('deskripsi')->nullable();
            $table->integer('sks')->default(3);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Pivot tabel Dosen - Mata Kuliah
        Schema::create('dosen_mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->constrained('pengguna')->cascadeOnDelete();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->timestamps();
            
            $table->unique(['dosen_id', 'mata_kuliah_id']);
        });

        // Tabel Rubrik Penilaian (template rubrik)
        Schema::create('rubrik_penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->string('nama', 100);
            $table->text('deskripsi')->nullable();
            $table->foreignId('periode_akademik_id')->constrained('periode_akademik')->cascadeOnDelete();
            $table->integer('semester');
            $table->foreignId('created_by')->constrained('pengguna')->cascadeOnDelete();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        // Tabel Item Rubrik (UTS, UAS, dll dengan persentase)
        Schema::create('rubrik_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rubrik_penilaian_id')->constrained('rubrik_penilaian')->cascadeOnDelete();
            $table->string('nama', 100);
            $table->decimal('persentase', 5, 2);
            $table->text('deskripsi')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rubrik_item');
        Schema::dropIfExists('rubrik_penilaian');
        Schema::dropIfExists('dosen_mata_kuliah');
        Schema::dropIfExists('mata_kuliah');
    }
};
