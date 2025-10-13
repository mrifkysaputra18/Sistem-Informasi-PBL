<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\{User, StudentScore, Criterion};

echo "🔍 CEK DATA DJENAR KANAYA AJENG\n\n";

$djenar = User::where('name', 'like', '%Djenar%')->first();

if (!$djenar) {
    echo "❌ Djenar tidak ditemukan!\n";
    echo "Mencari dengan nama lain...\n";
    
    $djenar = User::where('name', 'like', '%Kanaya%')->first();
    
    if (!$djenar) {
        echo "❌ Tetap tidak ditemukan!\n";
        exit;
    }
}

echo "✅ Ditemukan: {$djenar->name}\n";
echo "   ID: {$djenar->id}\n";
echo "   NIM: {$djenar->nim}\n";
echo "   Class Room ID: {$djenar->class_room_id}\n\n";

echo "📊 Nilai Djenar:\n";
$scores = StudentScore::where('user_id', $djenar->id)->get();
echo "   Jumlah scores: {$scores->count()}\n\n";

$criteria = Criterion::where('segment', 'student')->get();
foreach ($criteria as $criterion) {
    $score = $scores->where('criterion_id', $criterion->id)->first();
    $nilai = $score ? $score->skor : 0;
    echo "   - {$criterion->nama} (Bobot {$criterion->bobot}): {$nilai}\n";
}

// Calculate total using SAW
$total = 0;
foreach ($criteria as $criterion) {
    $score = $scores->where('criterion_id', $criterion->id)->first();
    $nilai = $score ? $score->skor : 0;
    $normalized = $nilai / 100;
    $weighted = $normalized * $criterion->bobot;
    $total += $weighted;
}

$totalScore = $total * 100;
echo "\n   Total Skor SAW: {$totalScore}\n";

// Check if nilai 96
$allScores = $scores->pluck('skor')->toArray();
echo "\n   Semua nilai: " . implode(', ', $allScores) . "\n";

if (in_array(96, $allScores)) {
    echo "\n✅ Nilai 96 ADA di database!\n";
} else {
    echo "\n❌ Nilai 96 TIDAK ADA! Perlu diupdate!\n";
}
