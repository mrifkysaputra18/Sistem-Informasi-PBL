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
        Schema::table('weekly_targets', function (Blueprint $table) {
            $table->boolean('is_reviewed')->default(false)->after('is_completed');
            $table->timestamp('reviewed_at')->nullable()->after('is_reviewed');
            $table->foreignId('reviewer_id')->nullable()->after('reviewed_at')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('weekly_targets', function (Blueprint $table) {
            $table->dropForeign(['reviewer_id']);
            $table->dropColumn(['is_reviewed', 'reviewed_at', 'reviewer_id']);
        });
    }
};
