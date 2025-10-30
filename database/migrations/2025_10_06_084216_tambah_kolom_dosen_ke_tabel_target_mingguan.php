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
            // Dosen yang membuat target
            $table->foreignId('created_by')->nullable()->after('group_id')
                  ->constrained('pengguna')->onDelete('set null')
                  ->comment('Dosen yang membuat target');
            
            // Deadline untuk submit
            $table->timestamp('deadline')->nullable()->after('week_number')
                  ->comment('Batas waktu submit target');
            
            // Catatan dari mahasiswa saat submit
            $table->text('submission_notes')->nullable()->after('description')
                  ->comment('Catatan dari mahasiswa saat submit');
            
            // Status submission (untuk tracking lebih detail)
            $table->enum('submission_status', ['pending', 'submitted', 'late', 'approved', 'revision'])
                  ->default('pending')->after('is_completed')
                  ->comment('Status submission: pending, submitted, late, approved, revision');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('target_mingguan', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn(['created_by', 'deadline', 'submission_notes', 'submission_status']);
        });
    }
};





