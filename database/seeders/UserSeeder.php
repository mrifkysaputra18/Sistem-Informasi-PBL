<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin utama
        Pengguna::create([
            'politala_id' => 'ADMIN_001',
            'name' => 'Administrator',
            'email' => 'admin@politala.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'program_studi' => 'Teknologi Informasi',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Koordinator (opsional)
        Pengguna::create([
            'politala_id' => 'KOORD_001',
            'name' => 'Koordinator PBL',
            'email' => 'koordinator@politala.ac.id',
            'password' => Hash::make('password'),
            'role' => 'koordinator',
            'program_studi' => 'Teknologi Informasi',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}


