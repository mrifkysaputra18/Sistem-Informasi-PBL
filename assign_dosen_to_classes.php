<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\{ClassRoom, User};

echo "📚 ASSIGN DOSEN KE KELAS\n\n";

// Get first dosen
$dosen = User::where('role', 'dosen')->first();

if (!$dosen) {
    echo "❌ Tidak ada dosen di database!\n";
    exit;
}

echo "✅ Dosen: {$dosen->name}\n";
echo "   ID: {$dosen->id}\n\n";

// Get all classrooms
$classRooms = ClassRoom::all();

echo "📋 Daftar Kelas:\n";
foreach ($classRooms as $classRoom) {
    $dosenName = $classRoom->dosen_id ? User::find($classRoom->dosen_id)->name ?? 'Unknown' : 'NULL';
    echo "   {$classRoom->id}. {$classRoom->name} - Dosen: {$dosenName}\n";
}

echo "\n🔄 Mengassign dosen ke semua kelas...\n";

// Assign dosen to all classes
foreach ($classRooms as $classRoom) {
    $classRoom->update(['dosen_id' => $dosen->id]);
    echo "   ✅ {$classRoom->name} -> {$dosen->name}\n";
}

echo "\n🎉 Selesai! Semua kelas sudah diassign ke {$dosen->name}\n";
