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
            // Drop foreign key constraint first if exists
            $table->dropForeign(['subject_id']);
            
            // Then drop the column
            $table->dropColumn('subject_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ruang_kelas', function (Blueprint $table) {
            // Re-add subject_id column
            $table->foreignId('subject_id')->nullable()
                  ->after('code')
                  ->constrained('mata_kuliah')
                  ->nullOnDelete();
        });
    }
};
