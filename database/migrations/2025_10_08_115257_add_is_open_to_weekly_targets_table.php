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
            // Field untuk menandakan apakah target masih terbuka untuk submission
            $table->boolean('is_open')->default(true)->after('deadline')
                  ->comment('Apakah target masih bisa disubmit (false jika sudah lewat deadline atau ditutup manual)');
            
            // Field untuk mencatat siapa yang membuka kembali target
            $table->foreignId('reopened_by')->nullable()->after('is_open')
                  ->constrained('users')->onDelete('set null')
                  ->comment('Dosen yang membuka kembali target setelah ditutup');
            
            // Field untuk mencatat kapan target dibuka kembali
            $table->timestamp('reopened_at')->nullable()->after('reopened_by')
                  ->comment('Waktu target dibuka kembali oleh dosen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('weekly_targets', function (Blueprint $table) {
            $table->dropForeign(['reopened_by']);
            $table->dropColumn(['is_open', 'reopened_by', 'reopened_at']);
        });
    }
};
