<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kemajuan_mingguan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('kelompok')->onDelete('cascade');
            $table->integer('week_number');
            $table->string('title');
            $table->text('description');
            $table->text('activities');
            $table->text('achievements')->nullable();
            $table->text('challenges')->nullable();
            $table->text('next_week_plan')->nullable();
            $table->json('documents')->nullable(); // Google Drive file IDs
            $table->enum('status', ['draft', 'submitted', 'reviewed'])->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('deadline')->nullable();
            $table->boolean('is_locked')->default(false);
            $table->timestamps();
            
            $table->unique(['group_id', 'week_number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('kemajuan_mingguan');
    }
};





