<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel untuk menyimpan log sinkronisasi ke kriteria
     */
    public function up(): void
    {
        Schema::create('sync_kriteria_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_room_id')->constrained('ruang_kelas')->cascadeOnDelete();
            $table->foreignId('synced_by')->constrained('pengguna')->cascadeOnDelete();
            $table->json('criteria_synced');           // Array kriteria yang disinkronkan
            $table->json('previous_values')->nullable(); // Nilai sebelum sync (untuk rollback)
            $table->json('new_values');                // Nilai baru hasil sync
            $table->timestamp('synced_at');
            $table->boolean('is_reverted')->default(false);
            $table->timestamp('reverted_at')->nullable();
            $table->foreignId('reverted_by')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamps();
            
            $table->index(['class_room_id', 'synced_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sync_kriteria_logs');
    }
};
