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
        Schema::table('kriteria', function (Blueprint $table) {
            // Ubah presisi bobot dari decimal(5,2) menjadi decimal(10,9)
            // Untuk menyimpan nilai seperti 0.244465446 dengan presisi tinggi
            $table->decimal('bobot', 10, 9)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kriteria', function (Blueprint $table) {
            // Kembalikan ke decimal(5,2)
            $table->decimal('bobot', 5, 2)->default(0)->change();
        });
    }
};
