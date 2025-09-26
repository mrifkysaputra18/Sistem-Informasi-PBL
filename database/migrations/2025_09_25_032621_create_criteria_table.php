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
        Schema::create('criteria', function (Blueprint $table) {
            $table->id();
            $table->string('nama');                           // Kecepatan Progres, dst.
            $table->decimal('bobot', 5, 2)->default(0);       // 0..1 (atau 0..100)
            $table->enum('tipe', ['benefit', 'cost'])->default('benefit');
            $table->enum('segment', ['group', 'student'])->default('group');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criteria');
    }
};
