<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\{Group, Criterion, GroupScore};

echo "🏆 UPDATE NILAI KELOMPOK TERBAIK\n\n";

// Get Kelompok 4 TI-3B
$bestGroup = Group::whereHas('classRoom', function($q) {
    $q->where('name', 'like', '%3B%');
})->where('name', 'Kelompok 4')->first();

if (!$bestGroup) {
    echo "❌ Kelompok 4 TI-3B tidak ditemukan!\n";
    exit;
}

echo "✅ Kelompok Terbaik: {$bestGroup->name} (Kelas: {$bestGroup->classRoom->name})\n\n";

// Get all group criteria
$criteria = Criterion::where('segment', 'group')->get();

echo "📊 Kriteria Kelompok:\n";
foreach ($criteria as $c) {
    echo "   - {$c->nama} (Bobot: " . round($c->bobot * 100, 2) . "%)\n";
}

echo "\n🔄 Mengupdate nilai kelompok terbaik...\n";

// Set nilai tinggi untuk kelompok terbaik
$scores = [
    'Kecepatan Progres' => 95,
    'Nilai Akhir PBL' => 98,
    'Ketepatan Waktu' => 96,
    'Penilaian Teman Kelompok' => 94,
];

foreach ($criteria as $criterion) {
    $score = $scores[$criterion->nama] ?? 90;
    
    GroupScore::updateOrCreate(
        [
            'group_id' => $bestGroup->id,
            'criterion_id' => $criterion->id,
        ],
        [
            'skor' => $score,
        ]
    );
    
    echo "   ✅ {$criterion->nama}: {$score}\n";
}

echo "\n🎉 Selesai! Nilai kelompok terbaik sudah diupdate.\n";
