<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add periode column first
        Schema::table('dosen_mata_kuliah', function (Blueprint $table) {
            $table->enum('periode', ['sebelum_uts', 'sesudah_uts'])->default('sebelum_uts')->after('mata_kuliah_id');
        });

        // Update existing records to 'sebelum_uts' as default
        DB::table('dosen_mata_kuliah')->whereNull('periode')->update(['periode' => 'sebelum_uts']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosen_mata_kuliah', function (Blueprint $table) {
            $table->dropColumn('periode');
        });
    }
};
