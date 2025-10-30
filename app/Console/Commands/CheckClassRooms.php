<?php

namespace App\Console\Commands;

use App\Models\RuangKelas;
use Illuminate\Console\Command;

class CheckClassRooms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'classrooms:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check class rooms in database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking class rooms...');
        
        $classRooms = RuangKelas::withCount('groups')->get();
        
        if ($classRooms->isEmpty()) {
            $this->warn('No class rooms found in database.');
            return;
        }
        
        $this->table(
            ['ID', 'Name', 'Code', 'Semester', 'Program Studi', 'Groups', 'Max Groups', 'Active'],
            $classRooms->map(function($class) {
                return [
                    $class->id,
                    $class->name,
                    $class->code,
                    $class->semester,
                    $class->program_studi,
                    $class->groups_count,
                    $class->max_groups,
                    $class->is_active ? 'Yes' : 'No'
                ];
            })->toArray()
        );
        
        $this->info("Total class rooms: {$classRooms->count()}");
    }
}


