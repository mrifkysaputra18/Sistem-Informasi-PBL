<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'politala_id' => 'ADMIN001',
            'name' => 'Admin System',
            'email' => 'admin@politala.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'program_studi' => 'Sistem Informasi',
            'is_active' => true,
        ]);

        // Koordinator
        User::create([
            'politala_id' => 'KOORD001',
            'name' => 'Koordinator PBL',
            'email' => 'koordinator@politala.ac.id',
            'password' => Hash::make('password'),
            'role' => 'koordinator',
            'program_studi' => 'Sistem Informasi',
            'is_active' => true,
        ]);

        // Dosen
        User::create([
            'politala_id' => 'DSN001',
            'name' => 'Dr. John Doe',
            'email' => 'dosen1@politala.ac.id',
            'password' => Hash::make('password'),
            'role' => 'dosen',
            'program_studi' => 'Sistem Informasi',
            'is_active' => true,
        ]);

        // Mahasiswa
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'politala_id' => 'MHS' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nim' => '2024' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'name' => 'Mahasiswa ' . $i,
                'email' => 'mahasiswa' . $i . '@politala.ac.id',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'program_studi' => 'Sistem Informasi',
                'is_active' => true,
            ]);
        }
    }
}