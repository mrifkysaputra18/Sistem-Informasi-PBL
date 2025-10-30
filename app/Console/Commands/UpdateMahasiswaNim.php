<?php

namespace App\Console\Commands;

use App\Models\Pengguna;
use Illuminate\Console\Command;

class UpdateMahasiswaNim extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mahasiswa:update-nim';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing mahasiswa users with NIM numbers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating mahasiswa users with NIM...');

        $mahasiswaUsers = Pengguna::where('role', 'mahasiswa')
            ->whereNull('nim')
            ->get();

        if ($mahasiswaUsers->isEmpty()) {
            $this->info('No mahasiswa users found without NIM.');
            return;
        }

        $counter = 1;
        foreach ($mahasiswaUsers as $user) {
            do {
                $nim = '2024' . str_pad($counter, 6, '0', STR_PAD_LEFT);
                $counter++;
            } while (Pengguna::where('nim', $nim)->exists());
            
            $user->update(['nim' => $nim]);
            $this->line("Updated {$user->name} with NIM: {$nim}");
        }

        $this->info("Successfully updated {$mahasiswaUsers->count()} mahasiswa users with NIM.");
    }
}


