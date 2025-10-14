<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\RankingService;
use App\Models\Group;

echo "🔍 VERIFIKASI SKALA SAW (0-1)\n\n";

$service = new RankingService();

// Get group rankings
$groupRanking = $service->getGroupRankingWithDetails();

echo "🏆 TOP 5 KELOMPOK (Skala 0-1):\n";
echo str_repeat("=", 80) . "\n";
foreach (array_slice($groupRanking, 0, 5) as $r) {
    echo sprintf(
        "%2d. %-25s | Kelas: %-6s | Skor SAW: %.4f\n",
        $r['rank'],
        $r['group']->name,
        $r['group']->classRoom->name,
        $r['total_score']
    );
}

echo "\n👨‍🎓 TOP 5 MAHASISWA (Skala 0-1):\n";
echo str_repeat("=", 80) . "\n";

$studentRanking = $service->getStudentRankingWithDetails();
foreach (array_slice($studentRanking, 0, 5) as $r) {
    echo sprintf(
        "%2d. %-30s | NIM: %-15s | Skor SAW: %.4f\n",
        $r['rank'],
        $r['student']->name,
        $r['student']->nim ?? '-',
        $r['total_score']
    );
}

echo "\n✅ Nilai SAW sekarang dalam skala 0-1 (bukan 0-100)!\n";
echo "   Nilai maksimum = 1.0 (atau 100%)\n";
echo "   Nilai minimum = 0.0\n";
