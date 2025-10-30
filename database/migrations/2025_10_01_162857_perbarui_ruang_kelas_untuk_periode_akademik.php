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
        Schema::table('ruang_kelas', function (Blueprint $table) {
            // Drop old foreign keys first
            $table->dropForeign(['academic_year_id']);
            $table->dropForeign(['semester_id']);
            
            // Drop old columns
            $table->dropColumn(['academic_year_id', 'semester_id']);
            
            // Add new academic_period_id
            $table->foreignId('academic_period_id')->nullable()->after('code')->constrained('periode_akademik')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ruang_kelas', function (Blueprint $table) {
            // Drop new foreign key and column
            $table->dropForeign(['academic_period_id']);
            $table->dropColumn('academic_period_id');
            
            // Restore old columns
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->onDelete('set null');
            $table->foreignId('semester_id')->nullable()->constrained('semesters')->onDelete('set null');
        });
    }
};





