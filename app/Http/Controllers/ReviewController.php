<?php

namespace App\Http\Controllers;

use App\Models\WeeklyProgress;
use App\Models\ProgressReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $pendingReviews = WeeklyProgress::whereHas('group.project', function ($query) use ($user) {
            $query->where('dosen_id', $user->id);
        })
        ->where('status', 'submitted')
        ->with(['group.project', 'group.members'])
        ->orderBy('submitted_at', 'asc')
        ->paginate(10);

        return view('reviews.index', compact('pendingReviews'));
    }

    public function show(WeeklyProgress $weeklyProgress)
    {
        $this->authorize('review', $weeklyProgress);
        
        $weeklyProgress->load([
            'group.members', 
            'group.project', 
            'review.reviewer'
        ]);

        return view('reviews.show', compact('weeklyProgress'));
    }

    public function store(Request $request, WeeklyProgress $weeklyProgress)
    {
        $this->authorize('review', $weeklyProgress);
        
        $validated = $request->validate([
            'score_progress_speed' => 'required|numeric|min:0|max:10',
            'score_quality' => 'required|numeric|min:0|max:10',
            'score_timeliness' => 'required|numeric|min:0|max:10',
            'score_collaboration' => 'required|numeric|min:0|max:10',
            'feedback' => 'required|string|max:1000',
            'suggestions' => 'nullable|string|max:1000',
            'status' => 'required|in:approved,needs_revision,rejected',
        ]);

        $review = ProgressReview::create([
            'weekly_progress_id' => $weeklyProgress->id,
            'reviewer_id' => Auth::id(),
            'score_progress_speed' => $validated['score_progress_speed'],
            'score_quality' => $validated['score_quality'],
            'score_timeliness' => $validated['score_timeliness'],
            'score_collaboration' => $validated['score_collaboration'],
            'feedback' => $validated['feedback'],
            'suggestions' => $validated['suggestions'],
            'status' => $validated['status'],
        ]);

        $review->calculateTotalScore();
        $review->save();

        // Update weekly progress status
        $weeklyProgress->update([
            'status' => 'reviewed',
            'is_locked' => true
        ]);

        // Update group total score
        $this->updateGroupScore($weeklyProgress->group);

        return redirect()->route('reviews.index')
            ->with('success', 'Review berhasil disimpan.');
    }

    public function update(Request $request, ProgressReview $review)
    {
        $this->authorize('update', $review);
        
        $validated = $request->validate([
            'score_progress_speed' => 'required|numeric|min:0|max:10',
            'score_quality' => 'required|numeric|min:0|max:10',
            'score_timeliness' => 'required|numeric|min:0|max:10',
            'score_collaboration' => 'required|numeric|min:0|max:10',
            'feedback' => 'required|string|max:1000',
            'suggestions' => 'nullable|string|max:1000',
            'status' => 'required|in:approved,needs_revision,rejected',
        ]);

        $review->update($validated);
        $review->calculateTotalScore();
        $review->save();

        // Update group total score
        $this->updateGroupScore($review->weeklyProgress->group);

        return redirect()->route('reviews.show', $review->weeklyProgress)
            ->with('success', 'Review berhasil diperbarui.');
    }

    private function updateGroupScore($group)
    {
        $averageScore = $group->weeklyProgress()
            ->whereHas('review')
            ->with('review')
            ->get()
            ->avg('review.total_score') ?? 0;

        $group->update(['total_score' => $averageScore]);

        // Update ranking within project
        $this->updateProjectRankings($group->project);
    }

    private function updateProjectRankings($project)
    {
        $groups = $project->groups()
            ->orderBy('total_score', 'desc')
            ->get();

        foreach ($groups as $index => $group) {
            $group->update(['ranking' => $index + 1]);
        }
    }
}