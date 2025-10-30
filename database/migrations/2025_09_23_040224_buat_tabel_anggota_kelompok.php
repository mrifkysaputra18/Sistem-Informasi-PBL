<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('anggota_kelompok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('kelompok')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('pengguna')->onDelete('cascade');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
            $table->unique(['group_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('anggota_kelompok');
    }
};





