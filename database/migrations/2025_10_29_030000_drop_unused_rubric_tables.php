<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Drop tabel rubric_components dan group_rubric_scores yang tidak digunakan
     */
    public function up(): void
    {
        // Drop tabel yang tidak digunakan
        Schema::dropIfExists('group_rubric_scores');
        Schema::dropIfExists('rubric_components');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate jika perlu rollback (tapi tidak perlu karena tabel kosong)
        Schema::create('rubric_components', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('group_rubric_scores', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }
};
