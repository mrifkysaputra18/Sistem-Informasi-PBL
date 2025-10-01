<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('progress_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('weekly_progress_id')->constrained('weekly_progress')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users');
            $table->decimal('score_progress_speed', 3, 1)->nullable(); // 0-10
            $table->decimal('score_quality', 3, 1)->nullable(); // 0-10
            $table->decimal('score_timeliness', 3, 1)->nullable(); // 0-10
            $table->decimal('score_collaboration', 3, 1)->nullable(); // 0-10
            $table->decimal('total_score', 4, 1)->nullable(); // 0-40
            $table->text('feedback')->nullable();
            $table->text('suggestions')->nullable();
            $table->enum('status', ['approved', 'needs_revision', 'rejected'])->default('approved');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('progress_reviews');
    }
};