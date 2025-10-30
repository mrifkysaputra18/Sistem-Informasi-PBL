<?php

namespace App\Jobs;

use App\Models\Kelompok;
use App\Models\Proyek;
use App\Notifications\ProgressReminderNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendProgressReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $activeProjects = Project::where('status', 'active')->get();

        foreach ($activeProjects as $project) {
            $currentWeek = $project->getCurrentWeek();
            
            if ($currentWeek > 0 && $currentWeek <= $project->getTotalWeeks()) {
                $groups = $project->groups()->with(['members'])->get();
                
                foreach ($groups as $group) {
                    // Check if progress for current week exists and is not submitted
                    $weeklyProgress = $group->weeklyProgress()
                        ->where('week_number', $currentWeek)
                        ->first();
                    
                    if (!$weeklyProgress || $weeklyProgress->status === 'draft') {
                        // Send reminder to all group members
                        foreach ($group->members as $member) {
                            $member->notify(new ProgressReminderNotification($group, $currentWeek));
                        }
                    }
                }
            }
        }
    }
}


