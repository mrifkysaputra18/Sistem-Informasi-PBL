<?php

namespace Database\Seeders;

use App\Models\RuangKelas;
use Illuminate\Database\Seeder;

class ClassRoomSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [
            [
                'name' => 'TI-3A',
                'code' => 'TI3A',
                'semester' => '3',
                'program_studi' => 'Teknologi Informasi',
                'max_groups' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'TI-3B',
                'code' => 'TI3B',
                'semester' => '3',
                'program_studi' => 'Teknologi Informasi',
                'max_groups' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'TI-3C',
                'code' => 'TI3C',
                'semester' => '3',
                'program_studi' => 'Teknologi Informasi',
                'max_groups' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'TI-3D',
                'code' => 'TI3D',
                'semester' => '3',
                'program_studi' => 'Teknologi Informasi',
                'max_groups' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'TI-3E',
                'code' => 'TI3E',
                'semester' => '3',
                'program_studi' => 'Teknologi Informasi',
                'max_groups' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($classes as $class) {
            RuangKelas::create($class);
        }
    }
}

