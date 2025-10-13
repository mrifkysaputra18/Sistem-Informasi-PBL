<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\RankingService;

echo "🔍 VERIFIKASI RANKING MAHASISWA TI-3B\n\n";

$service = new RankingService();

// Get ranking for class TI-3B (class_room_id = 2)
$ranking = $service->getStudentRankingWithDetails(2);

echo "TOP 10 MAHASISWA TI-3B:\n";
echo str_repeat("=", 80) . "\n\n";

foreach (array_slice($ranking, 0, 10) as $r) {
    echo sprintf(
        "%2d. %-30s | NIM: %-15s | Skor: %.2f\n",
        $r['rank'],
        $r['student']->name,
        $r['student']->nim ?? '-',
        $r['total_score']
    );
}

echo "\n" . str_repeat("=", 80) . "\n";
echo "\n✅ Jika Muhammad Rifky Saputra dan Djenar Kanaya Ajeng ada di posisi 1 & 2,\n";
echo "   maka data sudah benar. Silakan REFRESH halaman browser (Ctrl+F5)!\n";
