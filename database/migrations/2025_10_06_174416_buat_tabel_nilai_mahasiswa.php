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
        Schema::create('nilai_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('pengguna')->cascadeOnDelete();
            $table->foreignId('criterion_id')->constrained('kriteria')->cascadeOnDelete();
            $table->decimal('skor', 6, 2)->nullable();       // nilai mentah 0..100
            $table->timestamps();
            $table->unique(['user_id', 'criterion_id']);     // 1 nilai per kriteria per mahasiswa
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_mahasiswa');
    }
};





