<?php

namespace App\Http\Controllers;

use App\Models\{Group, WeeklyTarget, ClassRoom};
use Illuminate\Http\Request;

/**
 * Controller untuk DOSEN kelola target mingguan
 */
class WeeklyTargetController extends Controller
{
    /**
     * Display list of targets (untuk dosen)
     */
    public function index(Request $request)
    {
        $query = WeeklyTarget::with(['group.classRoom', 'creator', 'completedByUser']);

        // Base query untuk statistik (sebelum pagination)
        $statsQuery = clone $query;

        // Filter by class
        if ($request->has('class_room_id') && $request->class_room_id != '') {
            $query->whereHas('group', function($q) use ($request) {
                $q->where('class_room_id', $request->class_room_id);
            });
            $statsQuery->whereHas('group', function($q) use ($request) {
                $q->where('class_room_id', $request->class_room_id);
            });
        }

        // Filter by week
        if ($request->has('week_number') && $request->week_number != '') {
            $query->where('week_number', $request->week_number);
            $statsQuery->where('week_number', $request->week_number);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('submission_status', $request->status);
        }

        // Calculate statistics
        $stats = [
            'total' => $statsQuery->count(),
            'submitted' => $statsQuery->where('submission_status', 'submitted')->count(),
            'approved' => $statsQuery->where('submission_status', 'approved')->count(),
            'revision' => $statsQuery->where('submission_status', 'revision')->count(),
            'pending' => $statsQuery->where('submission_status', 'pending')->count(),
            'late' => $statsQuery->where('submission_status', 'late')->count(),
        ];

        // Calculate percentage
        $stats['submitted_percentage'] = $stats['total'] > 0 
            ? round(($stats['submitted'] + $stats['approved'] + $stats['revision']) / $stats['total'] * 100) 
            : 0;

        $targets = $query->orderBy('week_number')
            ->orderBy('deadline')
            ->paginate(20);

        // Get filter options
        $classRooms = ClassRoom::orderBy('name')->get();

        return view('targets.index', compact('targets', 'classRooms', 'stats'));
    }

    /**
     * Show the form for creating a new target (DOSEN)
     */
    public function create(Request $request)
    {
        $classRoomId = $request->get('class_room_id');
        $groupId = $request->get('group_id');
        
        // Get class rooms untuk dropdown
        $classRooms = ClassRoom::with('groups')->orderBy('name')->get();
        
        // Jika ada class_room_id, ambil groups nya
        $groups = null;
        if ($classRoomId) {
            $groups = Group::where('class_room_id', $classRoomId)
                ->orderBy('name')
                ->get();
        }
        
        // Jika ada group_id spesifik
        $selectedGroup = null;
        if ($groupId) {
            $selectedGroup = Group::findOrFail($groupId);
        }

        return view('targets.create', compact('classRooms', 'groups', 'selectedGroup'));
    }

    /**
     * Store a newly created target (DOSEN)
     */
    public function store(Request $request)
    {
        \Log::info('WeeklyTarget Store Request (Dosen)', [
            'created_by' => auth()->id(),
            'target_type' => $request->target_type,
            'class_room_id' => $request->class_room_id,
            'group_id' => $request->group_id,
            'group_ids' => $request->group_ids,
            'deadline' => $request->deadline,
            'all_request' => $request->all(),
        ]);

        try {
            $validated = $request->validate([
            'target_type' => 'required|in:single,multiple,all_class', // single group, multiple groups, atau semua kelas
            'class_room_id' => 'required_if:target_type,all_class|nullable|exists:class_rooms,id',
            'group_id' => 'required_if:target_type,single|nullable|exists:groups,id',
            'group_ids' => 'required_if:target_type,multiple|nullable|array',
            'group_ids.*' => 'exists:groups,id',
            'week_number' => 'required|integer|min:1|max:16',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date|after:today',
        ], [
            'target_type.required' => 'Tipe target harus dipilih.',
            'class_room_id.required_if' => 'Kelas harus dipilih untuk target semua kelas.',
            'class_room_id.exists' => 'Kelas yang dipilih tidak valid.',
            'group_id.required_if' => 'Kelompok harus dipilih untuk target single.',
            'group_id.exists' => 'Kelompok yang dipilih tidak valid.',
            'group_ids.required_if' => 'Minimal satu kelompok harus dipilih.',
            'week_number.required' => 'Minggu harus dipilih.',
            'title.required' => 'Judul target harus diisi.',
            'description.required' => 'Deskripsi target harus diisi.',
            'deadline.required' => 'Deadline harus diisi.',
            'deadline.after' => 'Deadline harus setelah hari ini.',
        ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation Failed', [
                'errors' => $e->errors(),
                'request' => $request->all(),
            ]);
            throw $e;
        }

        $targetGroups = [];

        // Tentukan target groups berdasarkan tipe
        if ($request->target_type === 'single') {
            $targetGroups = [$request->group_id];
        } elseif ($request->target_type === 'multiple') {
            $targetGroups = $request->group_ids;
        } elseif ($request->target_type === 'all_class') {
            // Semua groups di kelas ini
            $targetGroups = Group::where('class_room_id', $request->class_room_id)
                ->pluck('id')
                ->toArray();
        }

        if (empty($targetGroups)) {
            return back()->with('error', 'Tidak ada kelompok yang dipilih.');
        }

        // Create target untuk setiap group
        $createdCount = 0;
        foreach ($targetGroups as $groupId) {
            WeeklyTarget::create([
                'group_id' => $groupId,
                'created_by' => auth()->id(),
                'week_number' => $request->week_number,
                'title' => $request->title,
                'description' => $request->description,
                'deadline' => $request->deadline,
                'submission_status' => 'pending',
                'is_completed' => false,
                'is_reviewed' => false,
            ]);
            $createdCount++;
        }

        \Log::info('WeeklyTargets Created', [
            'count' => $createdCount,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('targets.index')
            ->with('success', "Target berhasil dibuat untuk {$createdCount} kelompok!");
    }

    /**
     * Display the specified target
     */
    public function show(WeeklyTarget $target)
    {
        $target->load(['group.classRoom', 'group.members.user', 'creator', 'completedByUser', 'reviewer']);

        return view('targets.show', compact('target'));
    }

    /**
     * Show the form for editing the target (DOSEN)
     */
    public function edit(WeeklyTarget $target)
    {
        // Dosen hanya bisa edit target yang dia buat
        if ($target->created_by !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit target ini.');
        }

        // Tidak bisa edit jika sudah ada submission
        if ($target->isSubmitted()) {
            return redirect()->back()
                ->with('error', 'Target yang sudah ada submission tidak dapat diedit. Silakan buat target baru.');
        }

        $group = $target->group;

        return view('targets.edit', compact('target', 'group'));
    }

    /**
     * Update the target (DOSEN)
     */
    public function update(Request $request, WeeklyTarget $target)
    {
        // Check permission
        if ($target->created_by !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit target ini.');
        }

        // Check if already submitted
        if ($target->isSubmitted()) {
            return redirect()->back()
                ->with('error', 'Target yang sudah ada submission tidak dapat diedit.');
        }

        $validated = $request->validate([
            'week_number' => 'required|integer|min:1|max:16',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date',
        ]);

        $target->update($validated);

        \Log::info('WeeklyTarget Updated', [
            'target_id' => $target->id,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('targets.index')
            ->with('success', 'Target berhasil diupdate!');
    }

    /**
     * Remove the target (DOSEN)
     */
    public function destroy(WeeklyTarget $target)
    {
        // Check permission
        if ($target->created_by !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus target ini.');
        }

        // Check if already submitted
        if ($target->isSubmitted()) {
            return redirect()->back()
                ->with('error', 'Target yang sudah ada submission tidak dapat dihapus.');
        }

        \Log::info('WeeklyTarget Deleted', [
            'target_id' => $target->id,
            'title' => $target->title,
            'deleted_by' => auth()->id(),
        ]);

        $target->delete();

        return redirect()->route('targets.index')
            ->with('success', 'Target berhasil dihapus!');
    }

    /**
     * Review submission (DOSEN)
     */
    public function review(WeeklyTarget $target)
    {
        // Check if target has been submitted
        if (!$target->isSubmitted()) {
            return redirect()->back()
                ->with('error', 'Target ini belum disubmit oleh mahasiswa.');
        }

        $target->load(['group.classRoom', 'group.members.user', 'creator', 'completedByUser']);

        return view('targets.review', compact('target'));
    }

    /**
     * Store review (DOSEN)
     */
    public function storeReview(Request $request, WeeklyTarget $target)
    {
        // Check if target has been submitted
        if (!$target->isSubmitted()) {
            return redirect()->back()
                ->with('error', 'Target ini belum disubmit oleh mahasiswa.');
        }

        // Check if already reviewed
        if ($target->isReviewed()) {
            return redirect()->back()
                ->with('error', 'Target ini sudah direview.');
        }

        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:approved,needs_revision,rejected',
            'feedback' => 'required|string',
            'suggestions' => 'nullable|string',
        ]);

        // Map status to submission_status
        $statusMap = [
            'approved' => 'approved',
            'needs_revision' => 'revision',
            'rejected' => 'rejected'
        ];
        
        $newStatus = $statusMap[$request->status];

        $target->update([
            'submission_status' => $newStatus,
            'is_reviewed' => true,
            'reviewed_at' => now(),
            'reviewer_id' => auth()->id(),
        ]);

        // Create review record if model exists
        if (class_exists('App\Models\WeeklyTargetReview')) {
            $reviewData = [
                'reviewer_id' => auth()->id(),
                'status' => $newStatus,
                'score' => $request->score,
                'feedback' => $request->feedback,
                'suggestions' => $request->suggestions,
                'notes' => $request->feedback, // Untuk backward compatibility
            ];
            
            $target->review()->create($reviewData);
        }

        \Log::info('Target Reviewed', [
            'target_id' => $target->id,
            'reviewer_id' => auth()->id(),
            'status' => $newStatus,
            'score' => $request->score,
        ]);

        return redirect()->route('targets.index')
            ->with('success', 'Review berhasil disimpan dengan nilai ' . $request->score . '!');
    }
}
