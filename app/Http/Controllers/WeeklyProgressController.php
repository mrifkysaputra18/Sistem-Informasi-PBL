<?php

namespace App\Http\Controllers;

use App\Models\WeeklyProgress;
use App\Models\Group;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WeeklyProgressController extends Controller
{
    protected $googleDriveService;

    public function __construct(GoogleDriveService $googleDriveService)
    {
        $this->googleDriveService = $googleDriveService;
    }

    public function index(Group $group)
    {
        $this->authorize('view', $group);
        
        $weeklyProgress = $group->weeklyProgress()
            ->with('review')
            ->orderBy('week_number')
            ->get();

        return view('weekly-progress.index', compact('group', 'weeklyProgress'));
    }

    public function create(Group $group, $weekNumber)
    {
        $this->authorize('update', $group);
        
        // Check if progress for this week already exists
        $existingProgress = WeeklyProgress::where('group_id', $group->id)
            ->where('week_number', $weekNumber)
            ->first();

        if ($existingProgress) {
            return redirect()->route('weekly-progress.edit', [$group, $existingProgress]);
        }

        return view('weekly-progress.create', compact('group', 'weekNumber'));
    }

    public function store(Request $request, Group $group)
    {
        $this->authorize('update', $group);
        
        $validated = $request->validate([
            'week_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'activities' => 'required|string',
            'achievements' => 'nullable|string',
            'challenges' => 'nullable|string',
            'next_week_plan' => 'nullable|string',
            'documents' => 'nullable|array',
            'documents.*' => 'file|max:10240', // 10MB max per file
        ]);

        // Handle document uploads to Google Drive
        $documentIds = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $documentId = $this->googleDriveService->uploadFile(
                    $file,
                    $group->google_drive_folder_id
                );
                $documentIds[] = $documentId;
            }
        }

        $weeklyProgress = WeeklyProgress::create([
            'group_id' => $group->id,
            'week_number' => $validated['week_number'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'activities' => $validated['activities'],
            'achievements' => $validated['achievements'],
            'challenges' => $validated['challenges'],
            'next_week_plan' => $validated['next_week_plan'],
            'documents' => $documentIds,
            'status' => 'draft',
            'deadline' => $this->calculateDeadline($group, $validated['week_number']),
        ]);

        return redirect()->route('weekly-progress.show', [$group, $weeklyProgress])
            ->with('success', 'Progress mingguan berhasil disimpan.');
    }

    public function show(Group $group, WeeklyProgress $weeklyProgress)
    {
        $this->authorize('view', $group);
        
        $weeklyProgress->load('review.reviewer');
        
        return view('weekly-progress.show', compact('group', 'weeklyProgress'));
    }

    public function edit(Group $group, WeeklyProgress $weeklyProgress)
    {
        $this->authorize('update', $group);
        
        if (!$weeklyProgress->canBeEdited()) {
            return redirect()->back()->with('error', 'Progress ini tidak dapat diedit lagi.');
        }

        return view('weekly-progress.edit', compact('group', 'weeklyProgress'));
    }

    public function update(Request $request, Group $group, WeeklyProgress $weeklyProgress)
    {
        $this->authorize('update', $group);
        
        if (!$weeklyProgress->canBeEdited()) {
            return redirect()->back()->with('error', 'Progress ini tidak dapat diedit lagi.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'activities' => 'required|string',
            'achievements' => 'nullable|string',
            'challenges' => 'nullable|string',
            'next_week_plan' => 'nullable|string',
            'documents' => 'nullable|array',
            'documents.*' => 'file|max:10240',
        ]);

        // Handle new document uploads
        $documentIds = $weeklyProgress->documents ?? [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $documentId = $this->googleDriveService->uploadFile(
                    $file,
                    $group->google_drive_folder_id
                );
                $documentIds[] = $documentId;
            }
        }

        $weeklyProgress->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'activities' => $validated['activities'],
            'achievements' => $validated['achievements'],
            'challenges' => $validated['challenges'],
            'next_week_plan' => $validated['next_week_plan'],
            'documents' => $documentIds,
        ]);

        return redirect()->route('weekly-progress.show', [$group, $weeklyProgress])
            ->with('success', 'Progress mingguan berhasil diperbarui.');
    }

    public function submit(Group $group, WeeklyProgress $weeklyProgress)
    {
        $this->authorize('update', $group);
        
        if (!$weeklyProgress->canBeEdited()) {
            return redirect()->back()->with('error', 'Progress ini tidak dapat disubmit lagi.');
        }

        $weeklyProgress->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return redirect()->route('weekly-progress.show', [$group, $weeklyProgress])
            ->with('success', 'Progress mingguan berhasil disubmit untuk direview.');
    }

    private function calculateDeadline(Group $group, int $weekNumber)
    {
        $project = $group->project;
        $startDate = $project->start_date;
        
        // Deadline is end of Sunday of the specified week
        return $startDate->copy()->addWeeks($weekNumber - 1)->endOfWeek();
    }
}