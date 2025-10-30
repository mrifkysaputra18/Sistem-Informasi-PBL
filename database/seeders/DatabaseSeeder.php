<?php

namespace Database\Seeders;

use App\Models\Pengguna;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Data Master (PENTING - Jangan dihapus)
            AcademicTermSeeder::class,      // Periode akademik
            SubjectSeeder::class,            // Mata kuliah
            CriterionSeeder::class,          // Kriteria penilaian
            ClassRoomSeeder::class,          // Kelas TI-3A sampai TI-3E
            DosenSeeder::class,              // Dosen real Politala
            UserSeeder::class,               // Admin dan Koordinator
            
            // ❌ DUMMY SEEDER - DINONAKTIFKAN
            // StudentSeeder::class,         // Generate 25 mahasiswa dummy per kelas
            // GroupSeeder::class,           // Generate kelompok dummy
            // CompletePBLDataSeeder::class, // Generate 125 mahasiswa + kelompok dummy
        ]);
    }
}

