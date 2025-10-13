<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CriterionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $group = [
            ['nama' => 'Kecepatan Progres', 'bobot' => 0.25, 'tipe' => 'benefit', 'segment' => 'group'],
            ['nama' => 'Hasil Review Dosen', 'bobot' => 0.35, 'tipe' => 'benefit', 'segment' => 'group'],
            ['nama' => 'Ketepatan Waktu', 'bobot' => 0.20, 'tipe' => 'benefit', 'segment' => 'group'],
            ['nama' => 'Kolaborasi Anggota', 'bobot' => 0.20, 'tipe' => 'benefit', 'segment' => 'group'],
        ];

        foreach ($group as $c) \App\Models\Criterion::updateOrCreate(
            ['nama' => $c['nama'], 'segment' => 'group'],
            $c
        );

        $student = [
            ['nama' => 'Nilai PBL Mahasiswa', 'bobot' => 0.5, 'tipe' => 'benefit', 'segment' => 'student'],
            ['nama' => 'Penilaian Teman Sekelompok', 'bobot' => 0.3, 'tipe' => 'benefit', 'segment' => 'student'],
            ['nama' => 'Kehadiran', 'bobot' => 0.2, 'tipe' => 'benefit', 'segment' => 'student'],
        ];
        foreach ($student as $c) \App\Models\Criterion::updateOrCreate(
            ['nama' => $c['nama'], 'segment' => 'student'],
            $c
        );
    }
}
