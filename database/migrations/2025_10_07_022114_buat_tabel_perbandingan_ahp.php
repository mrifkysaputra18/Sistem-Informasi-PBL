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
        Schema::create('ahp_comparisons', function (Blueprint $table) {
            $table->id();
            $table->string('segment'); // 'group' atau 'student'
            $table->foreignId('criterion_a_id')->constrained('kriteria')->cascadeOnDelete();
            $table->foreignId('criterion_b_id')->constrained('kriteria')->cascadeOnDelete();
            $table->decimal('value', 8, 4); // Nilai perbandingan 1/9 sampai 9
            $table->timestamps();
            
            // Unique constraint: hanya 1 perbandingan per pasangan kriteria per segment
            $table->unique(['segment', 'criterion_a_id', 'criterion_b_id'], 'ahp_unique_comparison');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ahp_comparisons');
    }
};





