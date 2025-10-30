<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $subjects = [
            [
                'code' => 'PBL',
                'name' => 'Project Based Learning',
                'description' => 'Mata kuliah berbasis proyek',
                'is_pbl_related' => true,
                'is_active' => true,
            ],
           
        ];

        foreach ($subjects as $subject) {
            MataKuliah::create($subject);
        }
    }
}


