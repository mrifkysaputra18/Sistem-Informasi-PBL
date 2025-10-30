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
        Schema::table('target_mingguan', function (Blueprint $table) {
            $table->json('evidence_files')->nullable()->after('description'); // Store file paths
            $table->boolean('is_checked_only')->default(false)->after('evidence_files'); // Checkbox option
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('target_mingguan', function (Blueprint $table) {
            $table->dropColumn(['evidence_files', 'is_checked_only']);
        });
    }
};





