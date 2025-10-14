<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Checking NIM vs Politala ID for mahasiswa...\n";
echo str_repeat("=", 80) . "\n\n";

// Get mahasiswa with both nim and politala_id
$users = \App\Models\User::where('role', 'mahasiswa')
    ->whereNotNull('nim')
    ->take(10)
    ->get();

foreach ($users as $user) {
    echo "Nama: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "NIM (kolom nim): " . ($user->nim ?? 'NULL') . "\n";
    echo "Politala ID: " . ($user->politala_id ?? 'NULL') . "\n";
    
    if ($user->nim && $user->politala_id && $user->nim != $user->politala_id) {
        echo "⚠️  BERBEDA! NIM di kolom 'nim' berbeda dengan 'politala_id'\n";
    }
    
    echo str_repeat("-", 80) . "\n";
}

// Count statistics
$totalMahasiswa = \App\Models\User::where('role', 'mahasiswa')->count();
$withNim = \App\Models\User::where('role', 'mahasiswa')->whereNotNull('nim')->count();
$withoutNim = \App\Models\User::where('role', 'mahasiswa')->whereNull('nim')->count();

echo "\nStatistik:\n";
echo "Total Mahasiswa: {$totalMahasiswa}\n";
echo "Mahasiswa dengan NIM (kolom nim): {$withNim}\n";
echo "Mahasiswa tanpa NIM (kolom nim): {$withoutNim}\n";
