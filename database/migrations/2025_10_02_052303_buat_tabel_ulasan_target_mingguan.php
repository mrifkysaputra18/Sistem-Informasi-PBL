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
        Schema::create('ulasan_target_mingguan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('weekly_target_id')->constrained('target_mingguan')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('pengguna')->onDelete('cascade');
            
            // Scores (sesuai rubrik: progress speed, quality, timeliness, collaboration)
            $table->decimal('score', 5, 2)->nullable(); // Total score
            
            // Feedback
            $table->text('feedback')->nullable();
            $table->text('suggestions')->nullable();
            
            // Status review
            $table->enum('status', ['approved', 'needs_revision', 'rejected'])->default('approved');
            
            $table->timestamps();
            
            // One review per target
            $table->unique('weekly_target_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ulasan_target_mingguan');
    }
};





