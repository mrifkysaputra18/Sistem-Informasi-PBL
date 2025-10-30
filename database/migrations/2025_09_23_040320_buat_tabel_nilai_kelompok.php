<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('nilai_kelompok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('kelompok')->cascadeOnDelete();
            $table->foreignId('criterion_id')->constrained('kriteria')->cascadeOnDelete();
            $table->decimal('skor', 6, 2)->nullable();       // nilai mentah 0..100
            $table->timestamps();
            $table->unique(['group_id', 'criterion_id']);     // 1 nilai per kriteria per kelompok
        });
    }

    public function down()
    {
        Schema::dropIfExists('nilai_kelompok');
    }
};





