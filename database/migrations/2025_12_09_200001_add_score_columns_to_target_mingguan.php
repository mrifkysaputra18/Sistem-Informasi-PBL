<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom untuk scoring berdasarkan todo list
     */
    public function up(): void
    {
        Schema::table('target_mingguan', function (Blueprint $table) {
            // Nilai kualitas dari dosen (0-100)
            $table->decimal('quality_score', 5, 2)->nullable()->after('is_reviewed');
            // Hasil kalkulasi: (verified/total) × quality_score
            $table->decimal('final_score', 5, 2)->nullable()->after('quality_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('target_mingguan', function (Blueprint $table) {
            $table->dropColumn(['quality_score', 'final_score']);
        });
    }
};
