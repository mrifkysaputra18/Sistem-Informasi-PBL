<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('criteria', function (Blueprint $table) {
            $table->id();
            $table->string('nama');                           // Kecepatan Progres, dst.
            $table->decimal('bobot', 5, 2)->default(0);       // 0..1 (atau 0..100)
            $table->enum('tipe', ['benefit', 'cost'])->default('benefit');
            $table->enum('segment', ['group', 'student'])->default('group');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('criteria');
    }
};
