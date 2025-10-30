<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TargetMingguan;
use App\Models\KemajuanMingguan;

class DebugWeeklyTargets extends Command
{
    protected $signature = 'debug:weekly-targets';
    protected $description = 'Debug weekly targets submission status';

    public function handle()
    {
        $this->info('🔍 Debugging Weekly Targets & Progress...');
        $this->newLine();

        // Check WeeklyTargets
        $this->info('📋 WEEKLY TARGETS:');
        $targets = TargetMingguan::with(['group', 'completedByUser'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        if ($targets->count() === 0) {
            $this->warn('   No weekly targets found!');
        } else {
            $this->table(
                ['ID', 'Group', 'Week', 'Title', 'Status', 'Is Completed', 'Completed At', 'Is Reviewed'],
                $targets->map(function ($target) {
                    return [
                        $target->id,
                        $target->group->name ?? '-',
                        $target->week_number,
                        \Str::limit($target->title, 30),
                        $target->submission_status ?? 'pending',
                        $target->is_completed ? '✅ Yes' : '❌ No',
                        $target->completed_at ? $target->completed_at->format('Y-m-d H:i') : '-',
                        $target->is_reviewed ? '✅ Yes' : '❌ No',
                    ];
                })
            );
        }

        $this->newLine();

        // Check WeeklyProgress
        $this->info('📊 WEEKLY PROGRESS:');
        $progress = WeeklyProgress::with('group')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        if ($progress->count() === 0) {
            $this->warn('   No weekly progress found!');
        } else {
            $this->table(
                ['ID', 'Group', 'Week', 'Title', 'Status', 'Submitted At'],
                $progress->map(function ($p) {
                    return [
                        $p->id,
                        $p->group->name ?? '-',
                        $p->week_number,
                        \Str::limit($p->title, 30),
                        $p->status,
                        $p->submitted_at ? $p->submitted_at->format('Y-m-d H:i') : '-',
                    ];
                })
            );
        }

        $this->newLine();

        // Check targets yang sudah submitted tapi belum reviewed
        $this->info('🔔 TARGETS PENDING REVIEW:');
        $pendingReview = TargetMingguan::whereIn('submission_status', ['submitted', 'late'])
            ->where('is_reviewed', false)
            ->with(['group', 'completedByUser'])
            ->get();

        if ($pendingReview->count() === 0) {
            $this->warn('   No targets pending review!');
        } else {
            $this->info("   Found {$pendingReview->count()} target(s) pending review:");
            $this->table(
                ['ID', 'Group', 'Week', 'Title', 'Submitted By', 'Submitted At'],
                $pendingReview->map(function ($target) {
                    return [
                        $target->id,
                        $target->group->name ?? '-',
                        $target->week_number,
                        \Str::limit($target->title, 30),
                        $target->completedByUser->name ?? '-',
                        $target->completed_at ? $target->completed_at->format('Y-m-d H:i') : '-',
                    ];
                })
            );
        }

        $this->newLine();
        $this->info('✅ Debug completed!');

        return 0;
    }
}



