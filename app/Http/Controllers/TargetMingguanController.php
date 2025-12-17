<?php

namespace App\Http\Controllers;

use App\Models\{Kelompok, TargetMingguan, RuangKelas};
use App\Services\TargetMingguanService;
use App\Http\Requests\{StoreWeeklyTargetRequest, UpdateWeeklyTargetRequest};
use Illuminate\Http\Request;

/**
 * Controller untuk mengelola Target Mingguan
 * Refactored: Menggunakan Service, Policy, dan Form Request
 */
class TargetMingguanController extends Controller
{
    public function __construct(
        protected TargetMingguanService $targetService
    ) {}

    /**
     * Display list of targets
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', TargetMingguan::class);
        
        $user = auth()->user();
        $result = $this->targetService->getTargetsForUser($user, $request->all());

        if (isset($result['message'])) {
            return view('target.daftar', $result);
        }

        return view('target.daftar', $result);
    }

    /**
     * Show the form for creating a new target
     */
    public function create(Request $request)
    {
        $this->authorize('create', TargetMingguan::class);
        
        $user = auth()->user();
        $classRoomId = $request->get('class_room_id');
        $groupId = $request->get('group_id');
        
        // Get semua kelas untuk dropdown (dosen bisa akses semua kelas)
        $classRooms = RuangKelas::with('groups')->orderBy('name')->get();
        
        $groups = null;
        if ($classRoomId) {
            $this->authorize('createForClassRoom', [TargetMingguan::class, (int) $classRoomId]);
            $groups = Kelompok::where('class_room_id', $classRoomId)->orderBy('name')->get();
        }
        
        $selectedGroup = null;
        if ($groupId) {
            $selectedGroup = Kelompok::with('classRoom')->findOrFail($groupId);
            $this->authorize('createForClassRoom', [TargetMingguan::class, $selectedGroup->classRoom->id]);
        }

        return view('target.tambah', compact('classRooms', 'groups', 'selectedGroup'));
    }

    /**
     * Store a newly created target
     */
    public function store(StoreWeeklyTargetRequest $request)
    {
        $validated = $request->validated();
        
        try {
            $createdCount = $this->targetService->createTargetsForGroups(
                $validated, 
                auth()->user()
            );

            $todoCount = count($validated['todo_items'] ?? []);
            return redirect()->route('targets.index')
                ->with('success', "Target berhasil dibuat untuk {$createdCount} kelompok dengan {$todoCount} todo items!");
        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified target
     */
    public function show(TargetMingguan $target)
    {
        $this->authorize('view', $target);
        
        $target->load(['group.classRoom', 'group.members.user', 'creator', 'completedByUser', 'reviewer']);
        return view('target.tampil', compact('target'));
    }

    /**
     * Show the form for editing the target
     */
    public function edit(TargetMingguan $target)
    {
        $this->authorize('update', $target);
        
        $Kelompok = $target->group;
        return view('target.ubah', compact('target', 'Kelompok'));
    }

    /**
     * Update the target
     */
    public function update(UpdateWeeklyTargetRequest $request, TargetMingguan $target)
    {
        $target->update($request->validated());

        \Log::info('TargetMingguan Updated', [
            'target_id' => $target->id,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('targets.show', $target->id)
            ->with('success', 'Target berhasil diupdate!');
    }

    /**
     * Remove the target
     */
    public function destroy(TargetMingguan $target)
    {
        $this->authorize('delete', $target);

        \Log::warning('TargetMingguan Deleted', [
            'target_id' => $target->id,
            'title' => $target->title,
            'deleted_by' => auth()->id(),
        ]);

        $target->delete();

        return redirect()->route('targets.index')
            ->with('success', 'Target berhasil dihapus!');
    }

    /**
     * Force delete target (ADMIN ONLY)
     */
    public function forceDestroy(TargetMingguan $target)
    {
        $this->authorize('forceDelete', TargetMingguan::class);

        $targetTitle = $target->title;
        
        \Log::warning('TargetMingguan FORCE DELETED', [
            'target_id' => $target->id,
            'title' => $target->title,
            'deleted_by' => auth()->id(),
        ]);

        $target->delete();

        return redirect()->route('targets.index')
            ->with('success', "Target '{$targetTitle}' berhasil dihapus (force delete)!");
    }

    /**
     * Download evidence file
     */
    public function download(TargetMingguan $target, $filePath)
    {
        $this->authorize('download', $target);

        $evidenceFiles = $target->evidence_files ?? [];
        $fileData = null;

        foreach ($evidenceFiles as $file) {
            if (isset($file['local_path']) && $file['local_path'] === $filePath) {
                $fileData = $file;
                break;
            }
        }

        if (!$fileData) {
            abort(404, 'File tidak ditemukan.');
        }

        $fullPath = storage_path('app/public/' . $filePath);
        
        if (!file_exists($fullPath)) {
            abort(404, 'File tidak ditemukan di storage.');
        }

        return response()->download($fullPath, $fileData['file_name'] ?? basename($filePath));
    }

    /**
     * Reopen target
     */
    public function reopen(TargetMingguan $target)
    {
        $this->authorize('toggleStatus', $target);

        $target->reopenTarget(auth()->id());

        \Log::info('Target Reopened', [
            'target_id' => $target->id,
            'reopened_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', 'Target berhasil dibuka kembali.');
    }

    /**
     * Show form for editing all targets in a specific week
     */
    public function editWeek($weekNumber, $classRoomId)
    {
        $this->authorize('manageWeek', [TargetMingguan::class, (int) $classRoomId]);
        
        $classRoom = RuangKelas::findOrFail($classRoomId);
        
        $targets = TargetMingguan::whereHas('group', fn($q) => $q->where('class_room_id', $classRoomId))
            ->where('week_number', $weekNumber)
            ->with('group')
            ->get();
        
        if ($targets->isEmpty()) {
            return redirect()->route('targets.index')->with('error', 'Target tidak ditemukan.');
        }
        
        $firstTarget = $targets->first();
        
        return view('target.ubah-minggu', compact('targets', 'firstTarget', 'weekNumber', 'classRoom'));
    }

    /**
     * Show detailed info page for a specific week's targets
     */
    public function showWeekInfo($weekNumber, $classRoomId)
    {
        $this->authorize('manageWeek', [TargetMingguan::class, (int) $classRoomId]);
        
        $classRoom = RuangKelas::findOrFail($classRoomId);
        
        $targets = TargetMingguan::whereHas('group', fn($q) => $q->where('class_room_id', $classRoomId))
            ->where('week_number', $weekNumber)
            ->with(['group.classRoom', 'completedByUser', 'review.reviewer', 'todoItems'])
            ->get();
        
        if ($targets->isEmpty()) {
            return redirect()->route('targets.index')->with('error', 'Target tidak ditemukan.');
        }
        
        $firstTarget = $targets->first();
        
        $stats = [
            'total' => $targets->count(),
            'submitted' => $targets->where('submission_status', 'submitted')->count(),
            'approved' => $targets->where('submission_status', 'approved')->count(),
            'pending' => $targets->where('submission_status', 'pending')->count(),
            'reviewed' => $targets->where('is_reviewed', true)->count(),
        ];
        
        $isPastDeadline = \Carbon\Carbon::parse($firstTarget->deadline)->isPast();
        $adaTargetTerbuka = $targets->contains(fn($t) => $t->is_open);
        $bisaDitutup = !$isPastDeadline && $adaTargetTerbuka;
        $bisaDibuka = $isPastDeadline || !$adaTargetTerbuka;
        
        return view('target.info-minggu', compact(
            'targets', 'firstTarget', 'weekNumber', 'classRoom',
            'stats', 'isPastDeadline', 'bisaDitutup', 'bisaDibuka'
        ));
    }

    /**
     * Update all targets in a specific week
     */
    public function updateWeek(Request $request, $weekNumber, $classRoomId)
    {
        $this->authorize('manageWeek', [TargetMingguan::class, (int) $classRoomId]);
        
        $validated = $request->validate([
            'week_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date',
        ]);
        
        $updatedCount = $this->targetService->updateWeekTargets(
            $weekNumber, 
            $classRoomId, 
            $validated, 
            auth()->user()
        );
        
        return redirect()->route('targets.index')
            ->with('success', "Berhasil mengupdate {$updatedCount} target minggu {$validated['week_number']}!");
    }

    /**
     * Delete all targets in a specific week
     */
    public function destroyWeek($weekNumber, $classRoomId)
    {
        $this->authorize('manageWeek', [TargetMingguan::class, (int) $classRoomId]);
        
        $deletedCount = $this->targetService->destroyWeekTargets(
            $weekNumber, 
            $classRoomId, 
            auth()->user()
        );
        
        return redirect()->route('targets.index')
            ->with('success', "Berhasil menghapus {$deletedCount} target minggu {$weekNumber}!");
    }

    /**
     * Reopen all targets in a specific week
     */
    public function reopenWeek($weekNumber, $classRoomId)
    {
        $this->authorize('manageWeek', [TargetMingguan::class, (int) $classRoomId]);
        
        $reopenedCount = $this->targetService->reopenWeekTargets(
            $weekNumber, 
            $classRoomId, 
            auth()->user()
        );
        
        return redirect()->route('targets.index')
            ->with('success', "Berhasil membuka kembali {$reopenedCount} target minggu {$weekNumber}!");
    }

    /**
     * Close all targets in a specific week
     */
    public function closeWeek($weekNumber, $classRoomId)
    {
        $this->authorize('manageWeek', [TargetMingguan::class, (int) $classRoomId]);
        
        $closedCount = $this->targetService->closeWeekTargets(
            $weekNumber, 
            $classRoomId, 
            auth()->user()
        );
        
        return redirect()->route('targets.index')
            ->with('success', "Berhasil menutup {$closedCount} target minggu {$weekNumber}!");
    }

    /**
     * Close target manually
     */
    public function close(TargetMingguan $target)
    {
        $this->authorize('toggleStatus', $target);

        $target->closeTarget();

        \Log::info('Target Closed', [
            'target_id' => $target->id,
            'closed_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', 'Target berhasil ditutup.');
    }

    /**
     * Auto-close targets that have passed their deadline
     */
    public function autoCloseOverdueTargets()
    {
        $this->authorize('autoClose', TargetMingguan::class);

        $closedCount = $this->targetService->autoCloseOverdueTargets(auth()->user());

        return redirect()->back()
            ->with('success', "Berhasil menutup {$closedCount} target yang melewati deadline.");
    }

    /**
     * Mark target as complete (Mahasiswa)
     */
    public function complete(TargetMingguan $target)
    {
        $this->authorize('submit', $target);

        $target->update([
            'is_completed' => true,
            'completed_at' => now(),
            'completed_by' => auth()->id(),
            'submission_status' => $target->isLate() ? 'late' : 'submitted',
        ]);

        return redirect()->back()
            ->with('success', 'Target berhasil ditandai selesai!');
    }

    /**
     * Unmark target as complete (Mahasiswa)
     */
    public function uncomplete(TargetMingguan $target)
    {
        $this->authorize('submit', $target);

        $target->update([
            'is_completed' => false,
            'completed_at' => null,
            'completed_by' => null,
            'submission_status' => 'pending',
        ]);

        return redirect()->back()
            ->with('success', 'Status target berhasil dibatalkan!');
    }
}
