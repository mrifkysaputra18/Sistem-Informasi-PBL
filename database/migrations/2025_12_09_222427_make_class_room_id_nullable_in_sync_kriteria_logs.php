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
        Schema::table('sync_kriteria_logs', function (Blueprint $table) {
            // Make class_room_id nullable to support syncing all classes
            $table->foreignId('class_room_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sync_kriteria_logs', function (Blueprint $table) {
            $table->foreignId('class_room_id')->nullable(false)->change();
        });
    }
};
