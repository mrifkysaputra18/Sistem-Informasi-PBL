<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom current_exam_period untuk menentukan 
     * status periode ujian aktif (UTS/UAS/none)
     */
    public function up(): void
    {
        Schema::table('periode_akademik', function (Blueprint $table) {
            $table->enum('current_exam_period', ['none', 'uts', 'uas'])
                  ->default('none')
                  ->after('is_active')
                  ->comment('Status periode ujian yang sedang aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('periode_akademik', function (Blueprint $table) {
            $table->dropColumn('current_exam_period');
        });
    }
};
