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
        Schema::create('periode_akademik', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // "TA 2024/2025 - Semester 3"
            $table->string('code')->unique(); // "2024-2025-3"
            $table->string('academic_year'); // "2024/2025"
            $table->integer('semester_number'); // 3, 4, 5
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
            
            // Unique constraint untuk academic_year + semester_number
            $table->unique(['academic_year', 'semester_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periode_akademik');
    }
};





