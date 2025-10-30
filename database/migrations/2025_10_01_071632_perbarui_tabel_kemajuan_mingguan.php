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
        Schema::table('kemajuan_mingguan', function (Blueprint $table) {
            // Ubah documents menjadi nullable karena sekarang opsional
            $table->json('documents')->nullable()->change();
            // Tambah field untuk menandai apakah hanya centang tanpa upload
            $table->boolean('is_checked_only')->default(false)->after('documents');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kemajuan_mingguan', function (Blueprint $table) {
            $table->dropColumn('is_checked_only');
        });
    }
};





