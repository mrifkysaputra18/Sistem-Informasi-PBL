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
            ['nama' => 'Kecepatan Progres', 'bobot' => 0.244465446, 'tipe' => 'benefit', 'segment' => 'group'],
            ['nama' => 'Nilai Akhir PBL', 'bobot' => 0.530599135, 'tipe' => 'benefit', 'segment' => 'group'],
            ['nama' => 'Ketepatan Waktu', 'bobot' => 0.153145735, 'tipe' => 'benefit', 'segment' => 'group'],
            ['nama' => 'Penilaian Teman Kelompok', 'bobot' => 0.071789684, 'tipe' => 'benefit', 'segment' => 'group'],
        ];

        foreach ($group as $c) \App\Models\Kriteria::updateOrCreate(
            ['nama' => $c['nama'], 'segment' => 'group'],
            $c
        );

        $student = [
            ['nama' => 'Nilai PBL Mahasiswa', 'bobot' => 0.5, 'tipe' => 'benefit', 'segment' => 'student'],
            ['nama' => 'Penilaian Teman Sekelompok', 'bobot' => 0.3, 'tipe' => 'benefit', 'segment' => 'student'],
            ['nama' => 'Kehadiran', 'bobot' => 0.2, 'tipe' => 'benefit', 'segment' => 'student'],
        ];
        foreach ($student as $c) \App\Models\Kriteria::updateOrCreate(
            ['nama' => $c['nama'], 'segment' => 'student'],
            $c
        );
    }
}

