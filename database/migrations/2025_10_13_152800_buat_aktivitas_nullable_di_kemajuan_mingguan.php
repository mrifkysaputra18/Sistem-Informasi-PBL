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
            // Make activities, achievements, challenges, next_week_plan nullable
            // since upload form doesn't require detailed fields
            $table->text('activities')->nullable()->change();
            $table->text('description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kemajuan_mingguan', function (Blueprint $table) {
            $table->text('activities')->nullable(false)->change();
            $table->text('description')->nullable(false)->change();
        });
    }
};





