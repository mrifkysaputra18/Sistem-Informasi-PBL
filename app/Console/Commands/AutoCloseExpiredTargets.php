<?php

namespace App\Console\Commands;

use App\Models\TargetMingguan;
use Illuminate\Console\Command;

class AutoCloseExpiredTargets extends Command
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
    protected $description = 'Auto-close targets that have passed their deadline + grace period';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired targets...');

        // Find targets that should be auto-closed
        $targets = TargetMingguan::where('is_open', true)
            ->where('auto_close', true)
            ->whereNotNull('deadline')
            ->whereIn('submission_status', ['pending', 'needs_revision'])
            ->get();

        $closedCount = 0;

        foreach ($targets as $target) {
            if ($target->isPastFinalDeadline()) {
                $target->update([
                    'is_open' => false,
                    'auto_closed_at' => now(),
                ]);

                $this->info("Closed: {$target->title} (Week {$target->week_number}) - Group: {$target->group->name}");
                $closedCount++;
            }
        }

        if ($closedCount === 0) {
            $this->info('No targets to close.');
        } else {
            $this->info("Successfully closed {$closedCount} target(s).");
        }

        return 0;
    }
}
