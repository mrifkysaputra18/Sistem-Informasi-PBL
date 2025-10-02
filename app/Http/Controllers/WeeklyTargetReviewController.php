<?php

namespace App\Http\Controllers;

use App\Models\WeeklyTarget;
use App\Models\WeeklyTargetReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WeeklyTargetReviewController extends Controller
{
    /**
     * Display a listing of targets that need review
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get all completed targets from groups that haven't been reviewed yet
        $pendingTargets = WeeklyTarget::with(['group.classRoom', 'group.members'])
            ->where('is_completed', true)
            ->where('is_reviewed', false)
            ->orderBy('completed_at', 'asc')
            ->paginate(20);

        return view('reviews.targets.index', compact('pendingTargets'));
    }

    /**
     * Show the form for reviewing a specific target
     */
    public function show(WeeklyTarget $weeklyTarget)
    {
        $target = $weeklyTarget->load(['group.members', 'group.classRoom', 'completedByUser', 'review']);
        
        // Check if already reviewed
        if ($target->isReviewed()) {
            return view('reviews.targets.show', compact('target'));
        }

        return view('reviews.targets.create', compact('target'));
    }

    /**
     * Store a newly created review
     */
    public function store(Request $request, WeeklyTarget $weeklyTarget)
    {
        $target = $weeklyTarget;
        
        // Check if already reviewed
        if ($target->isReviewed()) {
            return redirect()
                ->back()
                ->with('error', 'Target ini sudah dinilai sebelumnya!');
        }

        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'feedback' => 'required|string|max:1000',
            'suggestions' => 'nullable|string|max:1000',
            'status' => 'required|in:approved,needs_revision,rejected',
        ]);

        \Log::info('Creating WeeklyTarget Review', [
            'target_id' => $target->id,
            'reviewer_id' => Auth::id(),
            'score' => $validated['score'],
            'status' => $validated['status'],
        ]);

        // Create review
        $review = WeeklyTargetReview::create([
            'weekly_target_id' => $target->id,
            'reviewer_id' => Auth::id(),
            'score' => $validated['score'],
            'feedback' => $validated['feedback'],
            'suggestions' => $validated['suggestions'],
            'status' => $validated['status'],
        ]);

        // Update target as reviewed
        $target->update([
            'is_reviewed' => true,
            'reviewed_at' => now(),
            'reviewer_id' => Auth::id(),
        ]);

        \Log::info('WeeklyTarget Review Created', [
            'review_id' => $review->id,
            'target_id' => $target->id,
        ]);

        return redirect()
            ->route('target-reviews.index')
            ->with('success', 'Review berhasil disimpan! Target sudah dinilai.');
    }

    /**
     * Show the form for editing the specified review
     */
    public function edit(WeeklyTarget $weeklyTarget)
    {
        $target = $weeklyTarget->load(['group.members', 'group.classRoom', 'review']);
        
        if (!$target->isReviewed()) {
            return redirect()
                ->route('target-reviews.show', $target)
                ->with('error', 'Target ini belum dinilai!');
        }

        return view('reviews.targets.edit', compact('target'));
    }

    /**
     * Update the specified review
     */
    public function update(Request $request, WeeklyTarget $weeklyTarget)
    {
        $target = $weeklyTarget;
        $review = $target->review;

        if (!$review) {
            return redirect()
                ->back()
                ->with('error', 'Review tidak ditemukan!');
        }

        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'feedback' => 'required|string|max:1000',
            'suggestions' => 'nullable|string|max:1000',
            'status' => 'required|in:approved,needs_revision,rejected',
        ]);

        \Log::info('Updating WeeklyTarget Review', [
            'review_id' => $review->id,
            'target_id' => $target->id,
            'reviewer_id' => Auth::id(),
        ]);

        $review->update($validated);

        return redirect()
            ->route('target-reviews.show', $target)
            ->with('success', 'Review berhasil diperbarui!');
    }
}
