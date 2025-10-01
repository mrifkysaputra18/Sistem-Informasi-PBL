<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('groups', function (Blueprint $table) {
            // Add class_room_id
            $table->foreignId('class_room_id')->nullable()->after('id')->constrained('class_rooms')->onDelete('cascade');
            
            // Update leader_id to be nullable
            $table->foreignId('leader_id')->nullable()->change();
            
            // Add max_members
            $table->integer('max_members')->default(5)->after('ranking');
        });
    }

    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropForeign(['class_room_id']);
            $table->dropColumn(['class_room_id', 'max_members']);
        });
    }
};