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
        // Drop all junction/pivot tables that reference mata_kuliah
        Schema::dropIfExists('mata_kuliah_pengguna');
        Schema::dropIfExists('subject_user');
        
        // Drop mata_kuliah table
        Schema::dropIfExists('mata_kuliah');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate mata_kuliah table
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 100);
            $table->foreignId('academic_period_id')->nullable()
                  ->constrained('periode_akademik')
                  ->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_pbl_related')->default(false);
            $table->timestamps();
        });
        
        // Recreate junction table
        Schema::create('mata_kuliah_pengguna', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_kuliah_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pengguna_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
        
        // Note: subject_id in kriteria was never used, so not recreating it
    }
};
