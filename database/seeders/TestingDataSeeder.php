<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengguna;
use App\Models\MataKuliah;
use App\Models\RubrikPenilaian;
use App\Models\RubrikItem;
use App\Models\RuangKelas;
use App\Models\PeriodeAkademik;
use App\Models\KelasMataKuliah;
use App\Models\Kelompok;
use App\Models\DosenPblKelas;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TestingDataSeeder extends Seeder
{
    /**
     * Seeder untuk data testing sistem
     * Membuat akun Dosen PBL, Dosen Matkul (UTS & UAS), Mata Kuliah, dan Rubrik
     */
    public function run(): void
    {
        $this->command->info("🚀 Memulai seeding data testing...\n");

        // 1. Buat akun Dosen PBL
        $dosenPbl = $this->createDosenPbl();

        // 2. Buat akun Dosen Mata Kuliah (UTS & UAS)
        [$dosenMatkulUts, $dosenMatkulUas] = $this->createDosenMatkul();

        // 3. Buat Mata Kuliah
        $mataKuliahs = $this->createMataKuliah();

        // 4. Buat Rubrik Penilaian dengan Items (UTS & UAS terpisah)
        $this->createRubrikPenilaian($mataKuliahs, $dosenMatkulUts);

        // 5. Assign Dosen Matkul ke Kelas-Mata Kuliah (dengan rolling UTS/UAS)
        $this->assignDosenMatkulToKelas($mataKuliahs, $dosenMatkulUts, $dosenMatkulUas);

        // 6. Assign Dosen PBL ke Kelompok
        $this->assignDosenPbl($dosenPbl);

        $this->command->info("\n🎉 Seeding data testing selesai!");
        $this->command->info("\n📋 AKUN TESTING:");
        $this->command->table(
            ['Role', 'Nama', 'Email', 'Password'],
            [
                ['Dosen PBL', $dosenPbl->name, $dosenPbl->email, 'password123'],
                ['Dosen Matkul UTS', $dosenMatkulUts->name, $dosenMatkulUts->email, 'password123'],
                ['Dosen Matkul UAS', $dosenMatkulUas->name, $dosenMatkulUas->email, 'password123'],
            ]
        );
        $this->command->info("\n📌 INFO PENTING:");
        $this->command->info("- Dosen Matkul UTS: Mengajar sebelum UTS (minggu 1-8)");
        $this->command->info("- Dosen Matkul UAS: Mengajar sesudah UTS (minggu 9-16) - ROLLING");
    }

    private function createDosenPbl(): Pengguna
    {
        $dosen = Pengguna::updateOrCreate(
            ['email' => 'dosen.pbl@politala.ac.id'],
            [
                'name' => 'Dr. Ahmad Rizki, M.Kom (Dosen PBL)',
                'email' => 'dosen.pbl@politala.ac.id',
                'password' => Hash::make('password123'),
                'role' => 'dosen',
                'is_active' => true,
            ]
        );
        
        $this->command->info("✅ Dosen PBL: {$dosen->name}");
        return $dosen;
    }

    private function createDosenMatkul(): array
    {
        // Dosen UTS (sebelum UTS)
        $dosenUts = Pengguna::updateOrCreate(
            ['email' => 'dosen.uts@politala.ac.id'],
            [
                'name' => 'Pak Jaka, S.Kom., M.Cs (Dosen UTS)',
                'email' => 'dosen.uts@politala.ac.id',
                'password' => Hash::make('password123'),
                'role' => 'dosen',
                'is_active' => true,
            ]
        );
        
        // Dosen UAS (sesudah UTS - rolling)
        $dosenUas = Pengguna::updateOrCreate(
            ['email' => 'dosen.uas@politala.ac.id'],
            [
                'name' => 'Ibu Nindy, S.T., M.T (Dosen UAS)',
                'email' => 'dosen.uas@politala.ac.id',
                'password' => Hash::make('password123'),
                'role' => 'dosen',
                'is_active' => true,
            ]
        );
        
        $this->command->info("✅ Dosen Matkul UTS: {$dosenUts->name}");
        $this->command->info("✅ Dosen Matkul UAS: {$dosenUas->name}");
        
        return [$dosenUts, $dosenUas];
    }

    private function createMataKuliah(): array
    {
        $mataKuliahs = [
            [
                'kode' => 'TI301',
                'nama' => 'Basis Data Lanjut',
                'deskripsi' => 'Mata kuliah lanjutan tentang database management, query optimization, dan NoSQL.',
                'sks' => 3,
            ],
            [
                'kode' => 'TI302',
                'nama' => 'Pemrograman Web',
                'deskripsi' => 'Pengembangan aplikasi web dengan framework modern seperti Laravel dan Vue.js.',
                'sks' => 4,
            ],
            [
                'kode' => 'TI303',
                'nama' => 'Rekayasa Perangkat Lunak',
                'deskripsi' => 'Metodologi pengembangan software, SDLC, Agile, dan best practices.',
                'sks' => 3,
            ],
        ];

        $created = [];
        foreach ($mataKuliahs as $mk) {
            $mataKuliah = MataKuliah::updateOrCreate(
                ['kode' => $mk['kode']],
                array_merge($mk, ['is_active' => true])
            );
            $created[] = $mataKuliah;
            $this->command->info("✅ Mata Kuliah: {$mataKuliah->nama} ({$mataKuliah->kode})");
        }

        return $created;
    }

    private function createRubrikPenilaian(array $mataKuliahs, Pengguna $creator): void
    {
        // Get active periode akademik
        $periode = PeriodeAkademik::where('is_active', true)->first();
        
        if (!$periode) {
            $this->command->warn("⚠️ Tidak ada periode akademik aktif, skip rubrik.");
            return;
        }

        foreach ($mataKuliahs as $mataKuliah) {
            // Hapus rubrik lama jika ada untuk update bersih
            RubrikPenilaian::where('mata_kuliah_id', $mataKuliah->id)
                ->where('periode_akademik_id', $periode->id)
                ->delete();

            // Buat rubrik untuk setiap mata kuliah dengan bobot UTS/UAS
            $rubrik = RubrikPenilaian::create([
                'mata_kuliah_id' => $mataKuliah->id,
                'periode_akademik_id' => $periode->id,
                'nama' => "Rubrik {$mataKuliah->nama}",
                'deskripsi' => "Rubrik penilaian untuk mata kuliah {$mataKuliah->nama}",
                'semester' => $periode->semester_number ?? 1,
                'bobot_uts' => 40, // 40% UTS
                'bobot_uas' => 60, // 60% UAS
                'created_by' => $creator->id,
                'is_active' => true,
            ]);

            // Buat items rubrik UTS (total 100%)
            $itemsUts = [
                ['nama' => 'Pemahaman Konsep', 'deskripsi' => 'Kemampuan memahami teori dan konsep dasar', 'persentase' => 40, 'urutan' => 1, 'periode_ujian' => 'uts'],
                ['nama' => 'Implementasi Dasar', 'deskripsi' => 'Kemampuan mengimplementasikan konsep dasar', 'persentase' => 60, 'urutan' => 2, 'periode_ujian' => 'uts'],
            ];

            // Buat items rubrik UAS (total 100%)
            $itemsUas = [
                ['nama' => 'Implementasi Lanjut', 'deskripsi' => 'Kemampuan mengimplementasikan fitur lanjutan', 'persentase' => 40, 'urutan' => 1, 'periode_ujian' => 'uas'],
                ['nama' => 'Dokumentasi', 'deskripsi' => 'Kualitas dokumentasi dan laporan', 'persentase' => 30, 'urutan' => 2, 'periode_ujian' => 'uas'],
                ['nama' => 'Presentasi', 'deskripsi' => 'Kemampuan menyampaikan hasil kerja', 'persentase' => 30, 'urutan' => 3, 'periode_ujian' => 'uas'],
            ];

            foreach (array_merge($itemsUts, $itemsUas) as $item) {
                RubrikItem::create(array_merge($item, [
                    'rubrik_penilaian_id' => $rubrik->id,
                ]));
            }

            $this->command->info("✅ Rubrik: {$rubrik->nama}");
            $this->command->info("   - UTS (40%): 2 items (Pemahaman 40%, Implementasi Dasar 60%)");
            $this->command->info("   - UAS (60%): 3 items (Implementasi Lanjut 40%, Dokumentasi 30%, Presentasi 30%)");
        }
    }

    private function assignDosenMatkulToKelas(array $mataKuliahs, Pengguna $dosenUts, Pengguna $dosenUas): void
    {
        // Cari kelas yang ada
        $kelasList = RuangKelas::all();
        
        if ($kelasList->isEmpty()) {
            $this->command->warn("⚠️ Tidak ada kelas, skip assign Dosen Matkul.");
            return;
        }

        // Get active periode
        $periode = PeriodeAkademik::where('is_active', true)->first();
        if (!$periode) {
            $this->command->warn("⚠️ Tidak ada periode akademik aktif, skip assign.");
            return;
        }

        // Get rubrik
        foreach ($kelasList as $kelas) {
            foreach ($mataKuliahs as $mataKuliah) {
                // Cari rubrik yang aktif untuk mata kuliah ini
                $rubrik = RubrikPenilaian::where('mata_kuliah_id', $mataKuliah->id)
                    ->where('periode_akademik_id', $periode->id)
                    ->where('is_active', true)
                    ->first();

                // Buat atau update kelas-mata kuliah dengan dosen UTS dan UAS berbeda (rolling)
                KelasMataKuliah::updateOrCreate(
                    [
                        'class_room_id' => $kelas->id,
                        'mata_kuliah_id' => $mataKuliah->id,
                    ],
                    [
                        'dosen_sebelum_uts_id' => $dosenUts->id,
                        'dosen_sesudah_uts_id' => $dosenUas->id, // Rolling - dosen berbeda setelah UTS
                        'rubrik_penilaian_id' => $rubrik?->id,
                    ]
                );

                $this->command->info("✅ Kelas {$kelas->name} - {$mataKuliah->nama}:");
                $this->command->info("   - UTS: {$dosenUts->name}");
                $this->command->info("   - UAS: {$dosenUas->name} (rolling)");
            }
        }
    }

    private function assignDosenPbl(Pengguna $dosen): void
    {
        // Assign dosen PBL ke semua kelas yang ada via pivot table DosenPblKelas
        $kelasList = RuangKelas::all();
        
        if ($kelasList->isEmpty()) {
            $this->command->warn("⚠️ Tidak ada kelas, skip assign Dosen PBL.");
            return;
        }

        foreach ($kelasList as $kelas) {
            // Assign untuk periode sebelum UTS
            DosenPblKelas::updateOrCreate(
                [
                    'dosen_id' => $dosen->id,
                    'class_room_id' => $kelas->id,
                    'periode' => 'sebelum_uts',
                ],
                [
                    'is_active' => true,
                ]
            );

            // Assign untuk periode sesudah UTS
            DosenPblKelas::updateOrCreate(
                [
                    'dosen_id' => $dosen->id,
                    'class_room_id' => $kelas->id,
                    'periode' => 'sesudah_uts',
                ],
                [
                    'is_active' => true,
                ]
            );

            $this->command->info("✅ Assign {$dosen->name} sebagai Dosen PBL di {$kelas->name}");
        }
    }
}

