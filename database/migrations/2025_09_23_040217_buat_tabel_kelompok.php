<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kelompok', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('project_id')->nullable()->constrained('proyek')->onDelete('cascade');
            $table->foreignId('leader_id')->nullable()->constrained('pengguna');
            $table->string('google_drive_folder_id')->nullable();
            $table->decimal('total_score', 5, 2)->default(0);
            $table->integer('ranking')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kelompok');
    }
};





