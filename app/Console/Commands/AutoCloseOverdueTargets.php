<?php

namespace App\Console\Commands;

use App\Models\WeeklyTarget;
use Illuminate\Console\Command;

class AutoCloseOverdueTargets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'targets:auto-close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatis menutup target mingguan yang sudah melewati deadline';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai proses auto-close target yang sudah melewati deadline...');

        // Get all targets that should be closed
        // Termasuk yang sudah disubmit tapi belum direview
        $targets = WeeklyTarget::where('is_open', true)
            ->where('is_reviewed', false)
            ->whereNotNull('deadline')
            ->where('deadline', '<', now())
            ->get();

        $closedCount = 0;
        $skippedCount = 0;

        foreach ($targets as $target) {
            if ($target->shouldBeClosed()) {
                $target->closeTarget();
                $closedCount++;
                
                $this->line("✓ Menutup target: {$target->title} (Kelompok: {$target->group->name})");
            } else {
                $skippedCount++;
            }
        }

        $this->newLine();
        $this->info("Selesai!");
        $this->info("Total target ditutup: {$closedCount}");
        
        if ($skippedCount > 0) {
            $this->warn("Target dilewati: {$skippedCount}");
        }

        \Log::info('Auto-closed overdue targets', [
            'count' => $closedCount,
            'skipped' => $skippedCount,
        ]);

        return Command::SUCCESS;
    }
}
