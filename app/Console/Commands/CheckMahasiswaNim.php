<?php

namespace App\Console\Commands;

use App\Models\Pengguna;
use Illuminate\Console\Command;

class CheckMahasiswaNim extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mahasiswa:check-nim';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check mahasiswa users and their NIM numbers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking mahasiswa users and their NIM...');
        
        $mahasiswaUsers = Pengguna::where('role', 'mahasiswa')->get(['name', 'nim', 'email']);
        
        if ($mahasiswaUsers->isEmpty()) {
            $this->warn('No mahasiswa users found.');
            return;
        }
        
        $this->table(['Name', 'NIM', 'Email'], $mahasiswaUsers->map(function($user) {
            return [
                $user->name,
                $user->nim ?? 'NULL',
                $user->email
            ];
        })->toArray());
        
        $withNim = $mahasiswaUsers->whereNotNull('nim')->count();
        $withoutNim = $mahasiswaUsers->whereNull('nim')->count();
        
        $this->info("Total mahasiswa: {$mahasiswaUsers->count()}");
        $this->info("With NIM: {$withNim}");
        $this->info("Without NIM: {$withoutNim}");
    }
}


