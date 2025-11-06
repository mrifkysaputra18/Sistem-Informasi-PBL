<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GroupRankingService;
use App\Services\StudentRankingService;
use App\Models\RuangKelas;

class CalculateGroupRankings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ranking:calculate 
                            {--class= : Calculate ranking for specific class room ID}
                            {--all : Calculate ranking for all active classes}
                            {--type=both : Type of ranking (group, student, or both)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate rankings for groups and/or students based on weighted criteria';

    protected $groupRankingService;
    protected $studentRankingService;

    /**
     * Create a new command instance.
     */
    public function __construct(GroupRankingService $groupRankingService, StudentRankingService $studentRankingService)
    {
        parent::__construct();
        $this->groupRankingService = $groupRankingService;
        $this->studentRankingService = $studentRankingService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');

        if (!in_array($type, ['group', 'student', 'both'])) {
            $this->error('Invalid type. Use: group, student, or both');
            return 1;
        }

        $this->info('Starting ranking calculation...');
        $this->info("Type: {$type}");
        $this->newLine();

        if ($classId = $this->option('class')) {
            // Calculate for specific class
            $this->calculateForClass($classId, $type);
        } elseif ($this->option('all')) {
            // Calculate for all active classes
            $this->calculateForAllClasses($type);
        } else {
            $this->error('Please specify --class=ID or --all option');
            return 1;
        }

        $this->newLine();
        $this->info('✓ Ranking calculation completed!');
        return 0;
    }

    /**
     * Calculate ranking for a specific class
     */
    private function calculateForClass(int $classId, string $type): void
    {
        $class = RuangKelas::find($classId);

        if (!$class) {
            $this->error("Class room with ID {$classId} not found!");
            return;
        }

        $this->info("Calculating ranking for: {$class->name}");
        
        if ($type === 'group' || $type === 'both') {
            $this->info('  → Calculating group rankings...');
            $this->groupRankingService->calculateClassRankings($classId);
        }
        
        if ($type === 'student' || $type === 'both') {
            $this->info('  → Calculating student rankings...');
            $this->studentRankingService->calculateClassRankings($classId);
        }
        
        $this->info("✓ Completed for class: {$class->name}");
    }

    /**
     * Calculate ranking for all active classes
     */
    private function calculateForAllClasses(string $type): void
    {
        $classes = RuangKelas::where('is_active', true)
            ->withCount('groups')
            ->get();

        if ($classes->isEmpty()) {
            $this->warn('No active classes found.');
            return;
        }

        $this->info("Found {$classes->count()} active classes");
        $this->newLine();

        $bar = $this->output->createProgressBar($classes->count());
        $bar->start();

        foreach ($classes as $class) {
            if ($type === 'group' || $type === 'both') {
                $this->groupRankingService->calculateClassRankings($class->id);
            }
            
            if ($type === 'student' || $type === 'both') {
                $this->studentRankingService->calculateClassRankings($class->id);
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }
}
