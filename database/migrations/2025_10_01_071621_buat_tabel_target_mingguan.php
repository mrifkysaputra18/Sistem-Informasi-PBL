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
        Schema::create('target_mingguan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('kelompok')->onDelete('cascade');
            $table->integer('week_number'); // Minggu ke-berapa
            $table->string('title'); // Judul target
            $table->text('description')->nullable(); // Deskripsi target
            $table->boolean('is_completed')->default(false); // Status selesai
            $table->string('evidence_file')->nullable(); // File bukti (opsional)
            $table->timestamp('completed_at')->nullable(); // Kapan diselesaikan
            $table->foreignId('completed_by')->nullable()->constrained('pengguna'); // Siapa yang menyelesaikan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('target_mingguan');
    }
};





