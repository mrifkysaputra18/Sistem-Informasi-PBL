<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ruang_kelas', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // TI-3A, TI-3B, TI-3C, TI-3D, TI-3E
            $table->string('code')->unique();  // TI3A, TI3B, etc
            $table->string('semester')->default('3');  // Semester
            $table->string('program_studi')->default('Teknik Informatika');
            $table->integer('max_groups')->default(5);  // Maksimal 5 kelompok per kelas
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ruang_kelas');
    }
};





