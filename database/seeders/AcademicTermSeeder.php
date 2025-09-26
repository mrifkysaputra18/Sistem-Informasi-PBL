<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AcademicTermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\AcademicTerm::updateOrCreate(
            ['tahun_akademik' => '2024/2025', 'semester' => 'ganjil'],
            ['is_active' => true]
        );
    }
}
