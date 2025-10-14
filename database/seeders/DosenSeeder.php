<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dosens = [
            [
                'name' => 'Billy Sabella, S.Kom., M.Kom.',
                'email' => 'billy.sabella@politala.ac.id',
            ],
            [
                'name' => 'Nindy Perinatasari, S.Kom., M.Kom',
                'email' => 'nindy.perinatasari@politala.ac.id',
            ],
            [
                'name' => 'Jaka Permadi, S.Si., M.Cs',
                'email' => 'jaka.permadi@politala.ac.id',
            ],
            [
                'name' => 'Ir. Agustian Noor, M.Kom',
                'email' => 'agustian.noor@politala.ac.id',
            ],
        ];

        $total = count($dosens);
        $this->command->info("Menambahkan {$total} dosen...");

        foreach ($dosens as $dosen) {
            User::updateOrCreate(
                ['email' => $dosen['email']],
                [
                    'name' => $dosen['name'],
                    'email' => $dosen['email'],
                    'password' => Hash::make('password123'),
                    'role' => 'dosen',
                    'is_active' => true,
                ]
            );
            
            $this->command->info("✅ {$dosen['name']} berhasil ditambahkan");
        }

        $this->command->info("\n🎉 Total {$total} dosen berhasil ditambahkan!");
        $this->command->info("Default password untuk semua dosen: password123");
    }
}
