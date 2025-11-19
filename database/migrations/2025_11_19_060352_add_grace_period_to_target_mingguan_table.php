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
            // Grace period in minutes (default 0 = no grace period)
            $table->unsignedInteger('grace_period_minutes')->default(0)->after('deadline');
            
            // Auto close after deadline + grace period
            $table->boolean('auto_close')->default(true)->after('grace_period_minutes');
            
            // Track when target was auto-closed
            $table->timestamp('auto_closed_at')->nullable()->after('auto_close');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('target_mingguan', function (Blueprint $table) {
            $table->dropColumn(['grace_period_minutes', 'auto_close', 'auto_closed_at']);
        });
    }
};
