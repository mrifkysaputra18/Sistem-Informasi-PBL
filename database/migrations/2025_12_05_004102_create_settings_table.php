<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, boolean, json
            $table->string('group')->default('general');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default Google Drive settings
        DB::table('settings')->insert([
            [
                'key' => 'google_drive_folder_id',
                'value' => env('GOOGLE_DRIVE_FOLDER_ID', ''),
                'type' => 'string',
                'group' => 'google_drive',
                'description' => 'ID Folder atau Shared Drive untuk menyimpan file',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'google_drive_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'google_drive',
                'description' => 'Aktifkan penyimpanan ke Google Drive',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
