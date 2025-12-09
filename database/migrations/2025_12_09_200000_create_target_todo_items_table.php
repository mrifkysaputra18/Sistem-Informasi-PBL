<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat tabel untuk menyimpan todo items per target mingguan
     */
    public function up(): void
    {
        Schema::create('target_todo_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('target_mingguan_id')->constrained('target_mingguan')->cascadeOnDelete();
            $table->string('title');                                      // Judul todo item
            $table->text('description')->nullable();                      // Deskripsi optional
            $table->integer('order')->default(0);                         // Urutan tampilan
            $table->boolean('is_completed_by_student')->default(false);   // Claim mahasiswa
            $table->timestamp('completed_at')->nullable();                // Waktu mahasiswa menyelesaikan
            $table->boolean('is_verified_by_reviewer')->default(false);   // Verify dosen
            $table->foreignId('verified_by')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            
            // Index untuk query performa
            $table->index(['target_mingguan_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('target_todo_items');
    }
};
