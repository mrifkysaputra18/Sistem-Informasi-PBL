<?php

namespace App\Http\Controllers;

use App\Models\{Group, WeeklyTarget};
use App\Http\Requests\StoreWeeklyTargetRequest;
use Illuminate\Http\Request;

class WeeklyTargetController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $groupId = $request->get('group_id');
        
        if (!$groupId) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Group ID tidak ditemukan.');
        }
        
        $group = Group::findOrFail($groupId);
        
        // Check if user is member of the group
        $isMember = $group->members()->where('user_id', auth()->id())->exists();
        
        if (!$isMember && !auth()->user()->isAdmin() && !auth()->user()->isKoordinator()) {
            abort(403, 'Anda bukan anggota kelompok ini.');
        }

        return view('targets.create', compact('group'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Log incoming request for debugging
        \Log::info('WeeklyTarget Store Request', [
            'group_id' => $request->group_id,
            'week_number' => $request->week_number,
            'title' => $request->title,
            'is_checked_only' => $request->is_checked_only,
            'has_files' => $request->hasFile('evidence'),
        ]);

        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'week_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_checked_only' => 'nullable|boolean',
            'evidence.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048', // 2MB max per file
        ]);
        
        $group = Group::findOrFail($request->group_id);
        
        // Check if user is member of the group
        $isMember = $group->members()->where('user_id', auth()->id())->exists();
        
        if (!$isMember && !auth()->user()->isAdmin() && !auth()->user()->isKoordinator()) {
            abort(403, 'Anda bukan anggota kelompok ini.');
        }
        
        // Handle evidence uploads
        $evidencePaths = [];
        $isCheckedOnly = $request->has('is_checked_only') && $request->is_checked_only == '1';
        
        if ($request->hasFile('evidence')) {
            \Log::info('Processing file uploads', [
                'count' => count($request->file('evidence')),
                'is_checked_only' => $isCheckedOnly,
            ]);
            
            foreach ($request->file('evidence') as $file) {
                $path = $file->store('evidence', 'public');
                $evidencePaths[] = $path;
                \Log::info('File uploaded', ['path' => $path, 'size' => $file->getSize()]);
            }
            
            // If user uploaded files, uncheck the "checked only" option
            $isCheckedOnly = false;
        }
        
        $target = $group->weeklyTargets()->create([
            'week_number' => $request->week_number,
            'title' => $request->title,
            'description' => $request->description,
            'evidence_files' => $evidencePaths,
            'is_checked_only' => $isCheckedOnly,
        ]);

        \Log::info('WeeklyTarget Created', [
            'target_id' => $target->id,
            'evidence_count' => count($evidencePaths),
        ]);

        return redirect()
            ->route('mahasiswa.dashboard')
            ->with('success', 'Target mingguan berhasil ditambahkan!' . (count($evidencePaths) > 0 ? ' (' . count($evidencePaths) . ' file terupload)' : ''));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WeeklyTarget $weeklyTarget)
    {
        $target = $weeklyTarget;
        $group = $target->group;
        
        // Check if user is member of the group
        $isMember = $group->members()->where('user_id', auth()->id())->exists();
        
        if (!$isMember && !auth()->user()->isAdmin() && !auth()->user()->isKoordinator()) {
            abort(403, 'Anda bukan anggota kelompok ini.');
        }

        // Check if target has been reviewed
        if ($target->isReviewed()) {
            return redirect()
                ->back()
                ->with('error', 'Target yang sudah dinilai dosen tidak dapat diedit!');
        }

        return view('targets.edit', compact('group', 'target'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WeeklyTarget $weeklyTarget)
    {
        $target = $weeklyTarget;
        $group = $target->group;
        
        // Check if user is member of the group
        $isMember = $group->members()->where('user_id', auth()->id())->exists();
        
        if (!$isMember && !auth()->user()->isAdmin() && !auth()->user()->isKoordinator()) {
            abort(403, 'Anda bukan anggota kelompok ini.');
        }

        // Check if target has been reviewed
        if ($target->isReviewed()) {
            return redirect()
                ->back()
                ->with('error', 'Target yang sudah dinilai dosen tidak dapat diubah!');
        }
        
        \Log::info('WeeklyTarget Update Request', [
            'target_id' => $target->id,
            'week_number' => $request->week_number,
            'title' => $request->title,
            'is_checked_only' => $request->is_checked_only,
            'has_files' => $request->hasFile('evidence'),
        ]);

        $validated = $request->validate([
            'week_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_checked_only' => 'nullable|boolean',
            'evidence.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);
        
        // Handle evidence uploads
        $evidencePaths = $target->evidence_files ?? [];
        $isCheckedOnly = $request->has('is_checked_only') && $request->is_checked_only == '1';
        
        // If user uploaded new files
        if ($request->hasFile('evidence')) {
            \Log::info('Processing file uploads for update', [
                'count' => count($request->file('evidence')),
                'existing_count' => count($evidencePaths),
            ]);
            
            foreach ($request->file('evidence') as $file) {
                $path = $file->store('evidence', 'public');
                $evidencePaths[] = $path;
                \Log::info('File uploaded', ['path' => $path, 'size' => $file->getSize()]);
            }
            
            // If user uploaded files, uncheck the "checked only" option
            $isCheckedOnly = false;
        }
        
        // If checkbox is checked (and no files uploaded), clear all evidence
        if ($isCheckedOnly) {
            $evidencePaths = [];
            \Log::info('Clearing evidence files (checked only mode)');
        }
        
        $target->update([
            'week_number' => $request->week_number,
            'title' => $request->title,
            'description' => $request->description,
            'evidence_files' => $evidencePaths,
            'is_checked_only' => $isCheckedOnly,
        ]);

        \Log::info('WeeklyTarget Updated', [
            'target_id' => $target->id,
            'evidence_count' => count($evidencePaths),
        ]);

        return redirect()
            ->route('mahasiswa.dashboard')
            ->with('success', 'Target mingguan berhasil diupdate!' . (count($evidencePaths) > 0 ? ' (' . count($evidencePaths) . ' file)' : ''));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WeeklyTarget $weeklyTarget)
    {
        $target = $weeklyTarget;
        $group = $target->group;
        
        // Check if user is member of the group
        $isMember = $group->members()->where('user_id', auth()->id())->exists();
        
        if (!$isMember && !auth()->user()->isAdmin() && !auth()->user()->isKoordinator()) {
            abort(403, 'Anda bukan anggota kelompok ini.');
        }

        // Check if target has been reviewed
        if ($target->isReviewed()) {
            return redirect()
                ->back()
                ->with('error', 'Target yang sudah dinilai dosen tidak dapat dihapus!');
        }

        \Log::info('WeeklyTarget Deleted', [
            'target_id' => $target->id,
            'title' => $target->title,
            'deleted_by' => auth()->id(),
        ]);

        $target->delete();

        return redirect()
            ->route('mahasiswa.dashboard')
            ->with('success', 'Target mingguan berhasil dihapus!');
    }

    /**
     * Mark target as complete
     */
    public function complete(WeeklyTarget $weeklyTarget)
    {
        $target = $weeklyTarget;
        $group = $target->group;
        
        // Check if user is member of the group
        $isMember = $group->members()->where('user_id', auth()->id())->exists();
        
        if (!$isMember && !auth()->user()->isAdmin() && !auth()->user()->isKoordinator()) {
            abort(403, 'Anda bukan anggota kelompok ini.');
        }

        $target->update([
            'is_completed' => true,
            'completed_at' => now(),
            'completed_by' => auth()->id(),
        ]);

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Target berhasil ditandai selesai!');
    }

    /**
     * Mark target as incomplete
     */
    public function uncomplete(WeeklyTarget $weeklyTarget)
    {
        $target = $weeklyTarget;
        $group = $target->group;
        
        // Check if user is member of the group
        $isMember = $group->members()->where('user_id', auth()->id())->exists();
        
        if (!$isMember && !auth()->user()->isAdmin() && !auth()->user()->isKoordinator()) {
            abort(403, 'Anda bukan anggota kelompok ini.');
        }
        
        $target->update([
            'is_completed' => false,
            'completed_at' => null,
            'completed_by' => null,
        ]);

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Target berhasil ditandai belum selesai!');
    }
}
