<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\{User, Group, StudentScore, GroupScore, Criterion};

echo "🎯 MENGATUR MAHASISWA & KELOMPOK TERBAIK\n\n";

// ========================================
// 1. CARI MAHASISWA
// ========================================
echo "🔍 Mencari mahasiswa...\n";

$rifky = User::where('role', 'mahasiswa')
    ->where(function($q) {
        $q->where('name', 'like', '%Rifky%')
          ->orWhere('name', 'like', '%Muhammad Rifky%');
    })
    ->first();

$djenar = User::where('role', 'mahasiswa')
    ->where(function($q) {
        $q->where('name', 'like', '%Djenar%')
          ->orWhere('name', 'like', '%Kanaya%')
          ->orWhere('name', 'like', '%Ajeng%');
    })
    ->first();

if (!$rifky) {
    echo "❌ Muhammad Rifky Saputra tidak ditemukan!\n";
    echo "Membuat user baru...\n";
    
    // Create new user
    $rifky = User::create([
        'name' => 'Muhammad Rifky Saputra',
        'email' => 'rifky.saputra@mhs.politala.ac.id',
        'nim' => '2402250099',
        'password' => bcrypt('password'),
        'role' => 'mahasiswa',
        'phone' => '081234567890',
        'program_studi' => 'Teknik Informatika',
        'class_room_id' => 2, // TI-3B
    ]);
    
    // Add to a group in TI-3B
    $groupTI3B = Group::where('class_room_id', 2)->first();
    if ($groupTI3B) {
        \App\Models\GroupMember::create([
            'group_id' => $groupTI3B->id,
            'user_id' => $rifky->id,
            'is_leader' => false,
            'status' => 'active',
        ]);
    }
    
    echo "✅ User Muhammad Rifky Saputra berhasil dibuat!\n";
} else {
    echo "✅ Ditemukan: {$rifky->name} (NIM: {$rifky->nim})\n";
}

if (!$djenar) {
    echo "❌ Djenar Kanaya Ajeng tidak ditemukan!\n";
    echo "Membuat user baru...\n";
    
    // Create new user
    $djenar = User::create([
        'name' => 'Djenar Kanaya Ajeng',
        'email' => 'djenar.kanaya@mhs.politala.ac.id',
        'nim' => '2402250098',
        'password' => bcrypt('password'),
        'role' => 'mahasiswa',
        'phone' => '081234567891',
        'program_studi' => 'Teknik Informatika',
        'class_room_id' => 2, // TI-3B
    ]);
    
    // Add to a group in TI-3B
    $groupTI3B = Group::where('class_room_id', 2)->first();
    if ($groupTI3B) {
        \App\Models\GroupMember::create([
            'group_id' => $groupTI3B->id,
            'user_id' => $djenar->id,
            'is_leader' => false,
            'status' => 'active',
        ]);
    }
    
    echo "✅ User Djenar Kanaya Ajeng berhasil dibuat!\n";
} else {
    echo "✅ Ditemukan: {$djenar->name} (NIM: {$djenar->nim})\n";
}

// ========================================
// 2. SET NILAI MAHASISWA TERBAIK
// ========================================
echo "\n📊 Mengatur nilai mahasiswa...\n";

$studentCriteria = Criterion::where('segment', 'student')->get();

// Set nilai tertinggi untuk Rifky (Rank 1)
foreach ($studentCriteria as $criterion) {
    StudentScore::updateOrCreate(
        [
            'user_id' => $rifky->id,
            'criterion_id' => $criterion->id,
        ],
        [
            'skor' => 98, // Nilai sangat tinggi
        ]
    );
}
echo "✅ {$rifky->name} - Nilai diset ke 98 untuk semua kriteria\n";

// Set nilai tinggi untuk Djenar (Rank 2)
foreach ($studentCriteria as $criterion) {
    StudentScore::updateOrCreate(
        [
            'user_id' => $djenar->id,
            'criterion_id' => $criterion->id,
        ],
        [
            'skor' => 96, // Nilai tinggi tapi di bawah Rifky
        ]
    );
}
echo "✅ {$djenar->name} - Nilai diset ke 96 untuk semua kriteria\n";

// ========================================
// 3. SET KELOMPOK 4 TI-3B SEBAGAI TERBAIK
// ========================================
echo "\n🏆 Mengatur kelompok terbaik...\n";

// Find Kelompok 4 in TI-3B
$kelompok4TI3B = Group::whereHas('classRoom', function($q) {
    $q->where('name', 'like', '%3B%');
})->where('name', 'Kelompok 4')->first();

if (!$kelompok4TI3B) {
    echo "❌ Kelompok 4 TI-3B tidak ditemukan!\n";
} else {
    echo "✅ Ditemukan: {$kelompok4TI3B->name} (Kelas: {$kelompok4TI3B->classRoom->name})\n";
    
    $groupCriteria = Criterion::where('segment', 'group')->get();
    
    // Set nilai tertinggi untuk Kelompok 4 TI-3B
    foreach ($groupCriteria as $criterion) {
        GroupScore::updateOrCreate(
            [
                'group_id' => $kelompok4TI3B->id,
                'criterion_id' => $criterion->id,
            ],
            [
                'skor' => 99, // Nilai tertinggi
            ]
        );
    }
    echo "✅ {$kelompok4TI3B->name} - Nilai diset ke 99 untuk semua kriteria\n";
}

echo "\n🎉 Selesai! Silakan jalankan calculate_rankings.php untuk melihat hasilnya.\n";
