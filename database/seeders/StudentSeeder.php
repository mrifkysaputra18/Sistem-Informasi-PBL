<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ClassRoom;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classRooms = ClassRoom::all();
        
        if ($classRooms->isEmpty()) {
            $this->command->error('Tidak ada kelas yang ditemukan! Buat kelas terlebih dahulu.');
            return;
        }
        
        $this->command->info("Ditemukan {$classRooms->count()} kelas");
        
        $firstNames = [
            'Ahmad', 'Budi', 'Citra', 'Dedi', 'Eka', 'Fajar', 'Gita', 'Hadi', 'Indra', 'Joko',
            'Kartika', 'Lina', 'Maya', 'Nanda', 'Oki', 'Putri', 'Qori', 'Rina', 'Sari', 'Tono',
            'Umar', 'Vina', 'Wati', 'Yudi', 'Zahra', 'Andi', 'Bella', 'Candra', 'Dewi', 'Eko',
            'Fitri', 'Gilang', 'Hana', 'Irfan', 'Jihan', 'Kiki', 'Lani', 'Mira', 'Nisa', 'Omar'
        ];
        
        $lastNames = [
            'Pratama', 'Saputra', 'Wijaya', 'Kusuma', 'Permana', 'Santoso', 'Wibowo', 'Nugroho',
            'Hidayat', 'Ramadhan', 'Setiawan', 'Firmansyah', 'Hakim', 'Maulana', 'Putra', 'Putri',
            'Rahayu', 'Salsabila', 'Utama', 'Yanto', 'Zainal', 'Aditya', 'Budiman', 'Cahyono'
        ];
        
        $programStudi = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Teknik Komputer',
            'Rekayasa Perangkat Lunak'
        ];
        
        $totalCreated = 0;
        
        foreach ($classRooms as $classRoom) {
            $this->command->info("\nMembuat mahasiswa untuk kelas: {$classRoom->name}");
            
            for ($i = 1; $i <= 25; $i++) {
                $firstName = $firstNames[array_rand($firstNames)];
                $lastName = $lastNames[array_rand($lastNames)];
                $name = "{$firstName} {$lastName}";
                
                // Generate unique NIM based on class and iteration
                // Format: 24XXYYZZZZ where XX = class id, YY = year, ZZZZ = sequence
                $nim = sprintf('24%02d%02d%04d', $classRoom->id, date('y'), $i);
                
                // Check if NIM already exists
                while (User::where('nim', $nim)->exists()) {
                    $nim = sprintf('24%02d%02d%04d', $classRoom->id, date('y'), rand(1000, 9999));
                }
                
                // Generate unique email
                $emailBase = strtolower(str_replace(' ', '.', $name));
                $email = $emailBase . $i . '@mhs.politala.ac.id';
                
                // Check if email already exists
                $emailCounter = $i;
                while (User::where('email', $email)->exists()) {
                    $emailCounter++;
                    $email = $emailBase . $emailCounter . rand(10, 99) . '@mhs.politala.ac.id';
                }
                
                User::create([
                    'nim' => $nim,
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make('password123'), // Default password
                    'role' => 'mahasiswa',
                    'phone' => '08' . rand(1000000000, 9999999999),
                    'program_studi' => $programStudi[array_rand($programStudi)],
                    'class_room_id' => $classRoom->id,
                    'is_active' => true,
                ]);
                
                $totalCreated++;
            }
            
            $this->command->info("✅ 25 mahasiswa berhasil dibuat untuk {$classRoom->name}");
        }
        
        $this->command->info("\n🎉 Total {$totalCreated} mahasiswa berhasil dibuat!");
        $this->command->info("Default password untuk semua mahasiswa: password123");
    }
}
