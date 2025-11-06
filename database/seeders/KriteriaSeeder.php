<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;
use Illuminate\Support\Facades\DB;

class KriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if criteria already exist
        if (Kriteria::count() > 0) {
            $this->command->warn('Kriteria already exist. Skipping seeding.');
            return;
        }

        // Kriteria untuk Kelompok (Group)
        $groupCriteria = [
            [
                'nama' => 'Kecepatan Progres',
                'bobot' => 0.244465446,  // 24.45%
                'tipe' => 'benefit',
                'segment' => 'group',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Nilai Akhir PBL',
                'bobot' => 0.530599135,  // 53.06%
                'tipe' => 'benefit',
                'segment' => 'group',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ketepatan Waktu',
                'bobot' => 0.153145735,  // 15.31%
                'tipe' => 'benefit',
                'segment' => 'group',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Penilaian Teman (Group)',
                'bobot' => 0.071789684,  // 7.18%
                'tipe' => 'benefit',
                'segment' => 'group',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Kriteria untuk Mahasiswa (Student)
        $studentCriteria = [
            [
                'nama' => 'Nilai Akhir PBL',
                'bobot' => 0.63924572,  // 63%
                'tipe' => 'benefit',
                'segment' => 'student',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Penilaian Teman',
                'bobot' => 0.260497956,  // 26%
                'tipe' => 'benefit',
                'segment' => 'student',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Kehadiran',
                'bobot' => 0.106156324,  // 11%
                'tipe' => 'benefit',
                'segment' => 'student',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert all criteria
        Kriteria::insert(array_merge($groupCriteria, $studentCriteria));

        $this->command->info('✓ Successfully seeded kriteria penilaian');
        $this->command->newLine();
        
        $this->command->info('KRITERIA KELOMPOK (4):');
        $this->command->info('  - Kecepatan Progres (24.45%)');
        $this->command->info('  - Nilai Akhir PBL (53.06%)');
        $this->command->info('  - Ketepatan Waktu (15.31%)');
        $this->command->info('  - Penilaian Teman/Group (7.18%)');
        $this->command->newLine();
        
        $this->command->info('KRITERIA MAHASISWA (3):');
        $this->command->info('  - Nilai Akhir PBL (63%)');
        $this->command->info('  - Penilaian Teman (26%)');
        $this->command->info('  - Kehadiran (11%)');
    }
}
