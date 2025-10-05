<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ClassRoom;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\GroupScore;
use App\Models\Criterion;
use App\Models\WeeklyTarget;
use App\Models\WeeklyProgress;
use App\Models\Subject;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CompletePBLDataSeeder extends Seeder
{
    /**
     * Seeder untuk data dummy lengkap Sistem PBL
     * - 5 Kelas: TI-3A sampai TI-3E
     * - 25 Kelompok: 5 kelompok per kelas
     * - 125 Mahasiswa: 5 orang per kelompok
     * - Data scoring lengkap untuk ranking
     * 
     * @return void
     */
    public function run(): void
    {
        echo "\n🚀 Memulai pembuatan data dummy PBL...\n\n";

        // 1. Buat Subject PBL
        $subject = Subject::firstOrCreate(
            ['code' => 'PBL301'],
            [
                'name' => 'Project Based Learning 3',
                'description' => 'Mata kuliah PBL Semester 3 - Pengembangan Sistem Informasi',
                'is_pbl_related' => true,
                'is_active' => true,
            ]
        );

        echo "✅ Subject PBL301 created/found\n";

        // 2. Buat Project
        $project = Project::firstOrCreate(
            ['title' => 'Sistem Informasi Akademik'],
            [
                'description' => 'Proyek pembuatan sistem informasi akademik berbasis web dengan Laravel dan MySQL',
                'start_date' => now()->subMonths(2),
                'end_date' => now()->addMonths(2),
                'status' => 'active',
                'program_studi' => 'Teknik Informatika',
                'max_members' => 5,
            ]
        );

        echo "✅ Project created/found\n";

        // 3. Ambil semua kelas TI-3A sampai TI-3E
        $classes = ClassRoom::whereIn('code', ['TI3A', 'TI3B', 'TI3C', 'TI3D', 'TI3E'])->get();

        if ($classes->count() < 5) {
            echo "⚠️  Warning: Hanya ditemukan {$classes->count()} kelas. Seharusnya 5 kelas.\n";
        }

        // Update kelas dengan subject_id
        foreach ($classes as $class) {
            $class->update(['subject_id' => $subject->id]);
        }

        echo "✅ Updated {$classes->count()} classes with subject_id\n\n";

        // 4. Ambil kriteria penilaian
        $criteria = Criterion::where('segment', 'group')->get();

        if ($criteria->count() < 4) {
            echo "⚠️  Warning: Hanya ditemukan {$criteria->count()} kriteria. Seharusnya 4.\n";
        }

        $mahasiswaCounter = 100; // Mulai dari 100 agar tidak bentrok
        $allMahasiswa = [];

        // Daftar nama mahasiswa Indonesia (realistic)
        $namaDepan = [
            'Andi', 'Budi', 'Citra', 'Deni', 'Eka', 'Fajar', 'Gita', 'Hendra',
            'Indra', 'Joko', 'Kurnia', 'Lestari', 'Made', 'Nanda', 'Omar', 'Putu',
            'Qori', 'Rizki', 'Sari', 'Taufik', 'Usman', 'Vera', 'Wulan', 'Xavier',
            'Yanti', 'Zahra', 'Agus', 'Bella', 'Cahya', 'Dewi', 'Endang', 'Fitri',
            'Galuh', 'Hani', 'Intan', 'Jaya', 'Kartika', 'Linda', 'Maya', 'Nina',
            'Oki', 'Pandu', 'Qonita', 'Rina', 'Sinta', 'Tina', 'Ulfa', 'Vina',
            'Winda', 'Yuda', 'Zaki', 'Arif', 'Bayu', 'Candra', 'Diana', 'Eko'
        ];
        
        $namaBelakang = [
            'Pratama', 'Saputra', 'Wijaya', 'Kusuma', 'Permana', 'Hidayat', 'Rahman',
            'Santoso', 'Nugroho', 'Hakim', 'Putra', 'Putri', 'Setiawan', 'Wibowo',
            'Firmansyah', 'Maulana', 'Suryanto', 'Kurniawan', 'Rahmawati', 'Lestari',
            'Prasetyo', 'Gunawan', 'Handoko', 'Suharto', 'Budiman', 'Susanto',
            'Cahyono', 'Utomo', 'Subagyo', 'Aprilia', 'Anggraini', 'Safitri'
        ];

        // 5. Loop untuk setiap kelas
        foreach ($classes as $classIndex => $class) {
            $className = $class->name;
            echo "📚 Membuat data untuk kelas {$className}...\n";

            // 6. Buat 5 kelompok per kelas
            for ($groupNum = 1; $groupNum <= 5; $groupNum++) {
                $groupName = "Kelompok {$groupNum}";
                
                echo "   ├─ {$groupName}... ";

                // Buat kelompok
                $group = Group::create([
                    'class_room_id' => $class->id,
                    'project_id' => $project->id,
                    'name' => $groupName,
                    'max_members' => 5,
                ]);

                // 7. Buat 5 mahasiswa per kelompok
                $groupMembers = [];
                for ($memberNum = 1; $memberNum <= 5; $memberNum++) {
                    $politalaId = sprintf('2341080%03d', $mahasiswaCounter);
                    
                    // Generate nama random yang realistic
                    $depan = $namaDepan[array_rand($namaDepan)];
                    $belakang = $namaBelakang[array_rand($namaBelakang)];
                    $nama = $depan . ' ' . $belakang;
                    
                    // Pastikan nama unique dengan menambah suffix jika perlu
                    $namaUnique = $nama;
                    $suffix = 1;
                    while (User::where('name', $namaUnique)->exists()) {
                        $namaUnique = $nama . ' ' . chr(64 + $suffix); // A, B, C, dst
                        $suffix++;
                    }
                    
                    $mahasiswa = User::create([
                        'name' => $namaUnique,
                        'email' => sprintf('mhs%03d@mhs.politala.ac.id', $mahasiswaCounter),
                        'password' => Hash::make('password'),
                        'role' => 'mahasiswa',
                        'politala_id' => $politalaId,
                        'phone' => '08' . rand(1000000000, 9999999999),
                        'program_studi' => 'Teknik Informatika',
                        'is_active' => true,
                    ]);

                    // Tambahkan ke grup
                    GroupMember::create([
                        'group_id' => $group->id,
                        'user_id' => $mahasiswa->id,
                        'is_leader' => ($memberNum === 1),
                    ]);

                    if ($memberNum === 1) {
                        $group->update(['leader_id' => $mahasiswa->id]);
                    }

                    $groupMembers[] = $mahasiswa;
                    $allMahasiswa[] = $mahasiswa;
                    $mahasiswaCounter++;
                }

                // 8. Generate nilai untuk kelompok ini
                $baseScore = 100 - (($groupNum - 1) * 5) + rand(-10, 10);
                
                // Buat Weekly Targets (8 minggu)
                $completionRate = max(30, min(100, $baseScore + rand(-20, 20)));
                $totalTargets = 8;
                $completedTargets = round(($completionRate / 100) * $totalTargets);

                for ($week = 1; $week <= $totalTargets; $week++) {
                    $isCompleted = $week <= $completedTargets;
                    
                    WeeklyTarget::create([
                        'group_id' => $group->id,
                        'week_number' => $week,
                        'title' => $this->getTargetTitle($week),
                        'description' => $this->getTargetDescription($week),
                        'is_completed' => $isCompleted,
                        'completed_at' => $isCompleted ? now()->subWeeks($totalTargets - $week) : null,
                        'completed_by' => $isCompleted ? $groupMembers[0]->id : null,
                    ]);

                    // Buat Weekly Progress jika target completed
                    if ($isCompleted) {
                        WeeklyProgress::create([
                            'group_id' => $group->id,
                            'week_number' => $week,
                            'title' => "Progress Minggu {$week}",
                            'description' => $this->getProgressDescription($week),
                            'activities' => $this->getProgressDescription($week),
                            'achievements' => "Target minggu {$week} berhasil diselesaikan",
                            'status' => 'reviewed',
                            'submitted_at' => now()->subWeeks($totalTargets - $week),
                            'is_checked_only' => rand(0, 1) == 1,
                        ]);
                    }
                }

                // 9. Generate Score untuk setiap kriteria
                foreach ($criteria as $criterion) {
                    $score = $this->generateScore($criterion->nama, $groupNum, $classIndex);
                    
                    GroupScore::create([
                        'group_id' => $group->id,
                        'criterion_id' => $criterion->id,
                        'skor' => $score,
                    ]);
                }

                // 10. Hitung total score
                $this->calculateGroupScore($group, $criteria);

                echo "✓ (5 anggota, {$completedTargets}/{$totalTargets} targets)\n";
            }
            echo "\n";
        }

        $totalMahasiswa = $mahasiswaCounter - 100;
        $totalKelompok = Group::count();
        $totalTargets = WeeklyTarget::count();
        $totalProgress = WeeklyProgress::count();
        $totalScores = GroupScore::count();

        echo "\n" . str_repeat("=", 60) . "\n";
        echo "✅ SEEDER SELESAI!\n";
        echo str_repeat("=", 60) . "\n\n";
        echo "📊 SUMMARY DATA:\n";
        echo "   ├─ Kelas: 5 (TI-3A s/d TI-3E)\n";
        echo "   ├─ Kelompok: {$totalKelompok}\n";
        echo "   ├─ Mahasiswa Baru: {$totalMahasiswa}\n";
        echo "   ├─ Weekly Targets: {$totalTargets}\n";
        echo "   ├─ Weekly Progress: {$totalProgress}\n";
        echo "   └─ Group Scores: {$totalScores}\n\n";
        echo "🎓 Email Format: mhs100@mhs.politala.ac.id - mhs" . ($mahasiswaCounter-1) . "@mhs.politala.ac.id\n";
        echo "🔑 Password: password (semua mahasiswa)\n\n";
        echo "💡 Cara Lihat Data:\n";
        echo "   1. Login sebagai admin@politala.ac.id\n";
        echo "   2. Buka menu Scores/Penilaian Kelompok\n";
        echo "   3. Filter berdasarkan kelas untuk lihat ranking\n\n";
    }

    /**
     * Generate score berdasarkan kriteria dan posisi kelompok
     */
    private function generateScore($criteriaName, $groupNum, $classIndex): float
    {
        // Base score: kelompok 1 lebih tinggi, kelompok 5 lebih rendah
        $baseScore = 100 - (($groupNum - 1) * 8);
        
        // Variasi antar kelas
        $classVariation = rand(-5, 5);
        
        // Variasi per kriteria
        $score = match($criteriaName) {
            'Kecepatan Progres' => $baseScore + rand(-10, 10) + $classVariation,
            'Hasil Review Dosen' => $baseScore + rand(-15, 15) + $classVariation,
            'Ketepatan Waktu' => $baseScore + rand(-8, 8) + $classVariation,
            'Kolaborasi Anggota' => $baseScore + rand(-12, 12) + $classVariation,
            default => $baseScore + rand(-10, 10),
        };

        // Pastikan score dalam range 40-100
        return max(40, min(100, $score));
    }

    /**
     * Hitung total score kelompok
     */
    private function calculateGroupScore(Group $group, $criteria): void
    {
        $totalScore = 0;
        $scores = GroupScore::where('group_id', $group->id)->get();

        foreach ($scores as $score) {
            $criterion = $criteria->firstWhere('id', $score->criterion_id);
            if ($criterion) {
                $totalScore += $score->skor * $criterion->bobot;
            }
        }

        $group->update(['total_score' => round($totalScore, 2)]);
    }

    /**
     * Generate target title berdasarkan minggu
     */
    private function getTargetTitle($week): string
    {
        $titles = [
            1 => 'Analisis Kebutuhan Sistem',
            2 => 'Perancangan Database',
            3 => 'Pembuatan ERD dan Use Case',
            4 => 'Setup Project dan Framework',
            5 => 'Implementasi Backend API',
            6 => 'Implementasi Frontend',
            7 => 'Testing dan Bug Fixing',
            8 => 'Dokumentasi dan Deployment',
        ];

        return $titles[$week] ?? "Target Minggu {$week}";
    }

    /**
     * Generate target description
     */
    private function getTargetDescription($week): string
    {
        $descriptions = [
            1 => 'Melakukan analisis kebutuhan sistem informasi akademik, identifikasi fitur-fitur utama dan stakeholder',
            2 => 'Merancang struktur database, menentukan tabel-tabel utama dan relasi antar tabel',
            3 => 'Membuat ERD (Entity Relationship Diagram) dan Use Case Diagram untuk dokumentasi sistem',
            4 => 'Setup Laravel framework, instalasi dependencies, konfigurasi environment dan database',
            5 => 'Implementasi REST API untuk CRUD data akademik (mahasiswa, dosen, mata kuliah)',
            6 => 'Implementasi antarmuka pengguna dengan Blade templates dan Tailwind CSS',
            7 => 'Melakukan testing fungsional dan memperbaiki bug yang ditemukan',
            8 => 'Membuat dokumentasi lengkap sistem dan melakukan deployment ke server',
        ];

        return $descriptions[$week] ?? "Deskripsi target minggu {$week}";
    }

    /**
     * Generate progress description
     */
    private function getProgressDescription($week): string
    {
        return "Progress kelompok untuk minggu ke-{$week} telah diselesaikan sesuai dengan target yang ditentukan. "
             . "Tim telah berkolaborasi dengan baik dan menyelesaikan semua task yang diberikan dengan hasil yang memuaskan.";
    }
}
