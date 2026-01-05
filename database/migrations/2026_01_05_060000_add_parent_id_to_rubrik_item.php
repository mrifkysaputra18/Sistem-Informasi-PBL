<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom parent_id untuk mendukung nested/hierarchical items
     */
    public function up(): void
    {
        Schema::table('rubrik_item', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')
                  ->nullable()
                  ->after('id');
            
            $table->integer('level')
                  ->default(0)
                  ->after('parent_id')
                  ->comment('Kedalaman level: 0=root, 1=sub-item');
            
            // Foreign key constraint untuk self-reference
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('rubrik_item')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rubrik_item', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'level']);
        });
    }
};
