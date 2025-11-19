<?php

namespace App\Http\Controllers;

use App\Models\{Kelompok, TargetMingguan, RuangKelas};
use Illuminate\Http\Request;

/**
 * Controller untuk DOSEN kelola target mingguan
 */
class TargetMingguanController extends Controller
{
    /**
     * Display list of targets (untuk dosen)
     */
    public function index(Request $request)
    {
        // Get user's assigned class rooms (dosen hanya lihat kelas sendiri)
        $user = auth()->user();
        $myClassRoomIds = [];
        
        if ($user->isDosen()) {
            // Dosen hanya lihat target dari kelas yang dia ampu
            $myClassRoomIds = RuangKelas::where('dosen_id', $user->id)->pluck('id');
            
            // Auto filter untuk dosen
            if ($myClassRoomIds->isEmpty()) {
                return view('target.daftar', [
                    'targets' => collect(),
                    'classRooms' => collect(),
                    'stats' => [
                        'total' => 0, 'submitted' => 0, 'approved' => 0, 
                        'revision' => 0, 'pending' => 0, 'late' => 0,
                        'submitted_percentage' => 0,
                    ],
                    'message' => 'Anda belum ditugaskan ke kelas manapun.'
                ]);
            }
        }

        $query = TargetMingguan::with(['group.classRoom', 'creator', 'completedByUser']);

        // Base query untuk statistik (sebelum pagination)
        $statsQuery = clone $query;

        // Auto filter untuk dosen - Hanya dari kelas yang diampu
        if ($user->isDosen() && count($myClassRoomIds) > 0) {
            $query->whereHas('group', function($q) use ($myClassRoomIds) {
                $q->whereIn('class_room_id', $myClassRoomIds);
            });
            $statsQuery->whereHas('group', function($q) use ($myClassRoomIds) {
                $q->whereIn('class_room_id', $myClassRoomIds);
            });
        }

        // Additional filter by class (untuk admin atau filtering lebih spesifik)
        if ($request->has('class_room_id') && $request->class_room_id != '') {
            // Untuk dosen: hanya allow filter ke kelas yang diampu
            if ($user->isDosen() && !in_array($request->class_room_id, $myClassRoomIds->toArray())) {
                abort(403, 'You are not assigned to this class.');
            }
            
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

        // Get all targets without pagination untuk grouping
        $allTargets = $query->orderBy('week_number')
            ->orderBy('deadline')
            ->get();

        // Group targets by week number
        $targetsByWeek = $allTargets->groupBy('week_number')->map(function($weekTargets) {
            $firstTarget = $weekTargets->first();
            return [
                'week_number' => $firstTarget->week_number,
                'title' => $firstTarget->title,
                'deadline' => $firstTarget->deadline,
                'targets' => $weekTargets->sortBy('group.name')->values(),
                'stats' => [
                    'total' => $weekTargets->count(),
                    'submitted' => $weekTargets->where('submission_status', 'submitted')->count(),
                    'approved' => $weekTargets->where('submission_status', 'approved')->count(),
                    'revision' => $weekTargets->where('submission_status', 'revision')->count(),
                    'pending' => $weekTargets->where('submission_status', 'pending')->count(),
                    'late' => $weekTargets->where('submission_status', 'late')->count(),
                ]
            ];
        });

        // Get filter options - Dosen hanya lihat kelas yang diampu
        if ($user->isDosen()) {
            $classRooms = RuangKelas::where('dosen_id', $user->id)
                ->with('groups')
                ->orderBy('name')
                ->get();
        } else {
            // Admin lihat semua kelas
            $classRooms = RuangKelas::with('groups')->orderBy('name')->get();
        }

        return view('target.daftar', compact('targetsByWeek', 'classRooms', 'stats'));
    }

    /**
     * Show the form for creating a new target (DOSEN)
     */
    public function create(Request $request)
    {
        $user = auth()->user();
        
        // Only admin and dosen can create targets
        if (!$user->isAdmin() && !$user->isDosen()) {
            abort(403, 'Unauthorized action. Hanya admin dan dosen yang dapat membuat target mingguan.');
        }

        $classRoomId = $request->get('class_room_id');
        $groupId = $request->get('group_id');
        
        // Get class rooms untuk dropdown - Dosen hanya lihat kelas yang diampu
        if ($user->isDosen()) {
            $classRooms = RuangKelas::where('dosen_id', $user->id)
                ->with('groups')
                ->orderBy('name')
                ->get();
                
            // Jika dosen tidak ada kelas, jaga-jaga
            if ($classRooms->isEmpty()) {
                return back()->with('error', 'Anda belum ditugaskan ke kelas manapun.');
            }
        } else {
            // Admin lihat semua kelas
            $classRooms = RuangKelas::with('groups')->orderBy('name')->get();
        }
        
        // Jika ada class_room_id, ambil groups nya
        $groups = null;
        if ($classRoomId) {
            // Validasi: dosen hanya bisa akses kelas yang diampu
            if ($user->isDosen()) {
                $RuangKelas = RuangKelas::find($classRoomId);
                if (!$RuangKelas || $RuangKelas->dosen_id !== $user->id) {
                    abort(403, 'You are not assigned to this class.');
                }
            }
            
            $groups = Kelompok::where('class_room_id', $classRoomId)
                ->orderBy('name')
                ->get();
        }
        
        // Jika ada group_id spesifik
        $selectedGroup = null;
        if ($groupId) {
            $selectedGroup = Kelompok::with('RuangKelas')->findOrFail($groupId);
            
            // Validasi: dosen hanya bisa buat target untuk kelas yang diampu
            if ($user->isDosen() && $selectedGroup->RuangKelas->dosen_id !== $user->id) {
                abort(403, 'You are not assigned to this Kelompok\'s class.');
            }
        }

        return view('target.tambah', compact('classRooms', 'groups', 'selectedGroup'));
    }

    /**
     * Store a newly created target (DOSEN)
     */
    public function store(Request $request)
    {
        // Only admin and dosen can store targets
        if (!auth()->user()->isAdmin() && !auth()->user()->isDosen()) {
            abort(403, 'Unauthorized action. Hanya admin dan dosen yang dapat membuat target mingguan.');
        }

        \Log::info('TargetMingguan Store Request (Dosen)', [
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
            'target_type' => 'required|in:single,multiple,all_class', // single Kelompok, multiple groups, atau semua kelas
            'class_room_id' => 'required_if:target_type,all_class|nullable|exists:ruang_kelas,id',
            'group_id' => 'required_if:target_type,single|nullable|exists:kelompok,id',
            'group_ids' => 'required_if:target_type,multiple|nullable|array',
            'group_ids.*' => 'exists:kelompok,id',
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
            
            // Validasi: Dosen hanya bisa create target untuk kelas yang diampu
            if (auth()->user()->isDosen()) {
                $group = Kelompok::with('classRoom')->findOrFail($request->group_id);
                if ($group->classRoom->dosen_id !== auth()->id()) {
                    abort(403, 'Anda tidak memiliki akses untuk membuat target di kelas ini.');
                }
            }
        } elseif ($request->target_type === 'multiple') {
            $targetGroups = $request->group_ids;
            
            // Validasi: Dosen hanya bisa create target untuk kelas yang diampu
            if (auth()->user()->isDosen()) {
                $groups = Kelompok::with('classRoom')->whereIn('id', $targetGroups)->get();
                foreach ($groups as $group) {
                    if ($group->classRoom->dosen_id !== auth()->id()) {
                        abort(403, 'Anda tidak memiliki akses untuk membuat target di kelas ini.');
                    }
                }
            }
        } elseif ($request->target_type === 'all_class') {
            // Validasi: Dosen hanya bisa create target untuk kelas yang diampu
            if (auth()->user()->isDosen()) {
                $classRoom = RuangKelas::findOrFail($request->class_room_id);
                if ($classRoom->dosen_id !== auth()->id()) {
                    abort(403, 'Anda tidak memiliki akses untuk membuat target di kelas ini.');
                }
            }
            
            // Semua groups di kelas ini
            $targetGroups = Kelompok::where('class_room_id', $request->class_room_id)
                ->pluck('id')
                ->toArray();
        }

        if (empty($targetGroups)) {
            return back()->with('error', 'Tidak ada kelompok yang dipilih.');
        }

        // Create target untuk setiap Kelompok
        $createdCount = 0;
        foreach ($targetGroups as $groupId) {
            TargetMingguan::create([
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
    public function show(TargetMingguan $target)
    {
        $user = auth()->user();
        $target->load(['group.classRoom', 'group.members.user', 'creator', 'completedByUser', 'reviewer']);
        
        // Dosen hanya bisa lihat target di kelas yang diampu
        if ($user->isDosen() && $target->group->classRoom->dosen_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat target ini. Target ini bukan dari kelas yang Anda ampu.');
        }
        
        // Mahasiswa hanya bisa lihat target kelompoknya sendiri
        if ($user->isMahasiswa()) {
            $isMember = $target->group->members()->where('user_id', $user->id)->exists();
            if (!$isMember) {
                abort(403, 'Anda tidak memiliki akses untuk melihat target ini. Target ini bukan untuk kelompok Anda.');
            }
        }

        return view('target.tampil', compact('target'));
    }

    /**
     * Show the form for editing the target (DOSEN)
     * Allow editing even if submitted (dosen override)
     */
    public function edit(TargetMingguan $target)
    {
        $user = auth()->user();
        
        // Check permission: Admin, creator, or dosen pengampu
        $canEdit = $user->isAdmin() 
                || $target->created_by === $user->id
                || ($user->isDosen() && $target->group->classRoom->dosen_id === $user->id);
        
        if (!$canEdit) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit target ini.');
        }

        // Allow dosen to edit even if submitted (removed restriction)
        // Original restriction: if ($target->isSubmitted()) return error

        $Kelompok = $target->Kelompok;

        return view('target.ubah', compact('target', 'Kelompok'));
    }

    /**
     * Update the target (DOSEN)
     * Allow updating even if submitted (dosen override)
     */
    public function update(Request $request, TargetMingguan $target)
    {
        $user = auth()->user();
        
        // Check permission: Admin, creator, or dosen pengampu
        $canEdit = $user->isAdmin() 
                || $target->created_by === $user->id
                || ($user->isDosen() && $target->group->classRoom->dosen_id === $user->id);
        
        if (!$canEdit) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit target ini.');
        }

        // Allow dosen to update even if submitted (removed restriction)

        $validated = $request->validate([
            'week_number' => 'required|integer|min:1|max:16',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date',
        ]);

        $target->update($validated);

        \Log::info('TargetMingguan Updated', [
            'target_id' => $target->id,
            'updated_by' => auth()->id(),
            'was_submitted' => $target->isSubmitted(),
        ]);

        return redirect()->route('targets.show', $target->id)
            ->with('success', 'Target berhasil diupdate!');
    }

    /**
     * Remove the target (DOSEN)
     * Allow deleting even if submitted (dosen override)
     */
    public function destroy(TargetMingguan $target)
    {
        $user = auth()->user();
        
        // Check permission: Admin, creator, or dosen pengampu
        $canDelete = $user->isAdmin() 
                  || $target->created_by === $user->id
                  || ($user->isDosen() && $target->group->classRoom->dosen_id === $user->id);
        
        if (!$canDelete) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus target ini.');
        }

        // Allow dosen to delete even if submitted (removed restriction)
        // Original restriction: if ($target->isSubmitted()) return error

        \Log::warning('TargetMingguan Deleted by Dosen', [
            'target_id' => $target->id,
            'title' => $target->title,
            'deleted_by' => auth()->id(),
            'was_submitted' => $target->isSubmitted(),
            'was_reviewed' => $target->isReviewed(),
        ]);

        $target->delete();

        return redirect()->route('targets.index')
            ->with('success', 'Target berhasil dihapus!');
    }

    /**
     * Force delete target (ADMIN ONLY - even if submitted)
     * Use with caution!
     */
    public function forceDestroy(TargetMingguan $target)
    {
        // Only admin can force delete
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya admin yang dapat force delete target.');
        }

        $targetTitle = $target->title;
        $targetId = $target->id;
        
        \Log::warning('TargetMingguan FORCE DELETED', [
            'target_id' => $target->id,
            'title' => $target->title,
            'status' => $target->submission_status,
            'was_submitted' => $target->isSubmitted(),
            'was_reviewed' => $target->isReviewed(),
            'deleted_by' => auth()->id(),
        ]);

        // Delete target regardless of status
        $target->delete();

        return redirect()->route('targets.index')
            ->with('success', "Target '{$targetTitle}' berhasil dihapus (force delete)!");
    }

    // Review methods removed - use UlasanTargetMingguanController instead
    // Removed methods: review(), storeReview()
    // Date: 2025-11-18
    // Reason: Duplicate functionality with UlasanTargetMingguanController
    
    /**
     * Download evidence file
     */
    public function download(TargetMingguan $target, $filePath)
    {
        // Verify user has access to this target
        $user = auth()->user();
        $hasAccess = false;

        // Check if user is admin, dosen, koordinator, or member of the Kelompok
        if ($user->isAdmin() || $user->isDosen() || $user->isKoordinator()) {
            $hasAccess = true;
        } elseif ($user->isMahasiswa()) {
            $hasAccess = $target->Kelompok->members()->where('user_id', $user->id)->exists();
        }

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses untuk mengunduh file ini.');
        }

        // Find the file in evidence_files
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

        // Download from local storage
        $fullPath = storage_path('app/public/' . $filePath);
        
        if (!file_exists($fullPath)) {
            abort(404, 'File tidak ditemukan di storage.');
        }

        return response()->download($fullPath, $fileData['file_name'] ?? basename($filePath));
    }

    /**
     * Reopen target (membuka kembali target yang sudah ditutup)
     * Dosen bisa membuka target bahkan yang sudah direview
     */
    public function reopen(TargetMingguan $target)
    {
        // Only dosen/admin can reopen targets
        $user = auth()->user();
        if (!$user->isDosen() && !$user->isAdmin() && !$user->isKoordinator()) {
            abort(403, 'Hanya dosen yang dapat membuka kembali target.');
        }

        // Allow reopen even if reviewed (removed restriction)
        // Dosen has full control to reopen "kantong tugas"

        // Reopen the target
        $target->reopenTarget(auth()->id());

        \Log::warning('Target Reopened by Dosen', [
            'target_id' => $target->id,
            'title' => $target->title,
            'reopened_by' => auth()->id(),
            'reopener_name' => auth()->user()->name,
            'group_id' => $target->group_id,
            'was_reviewed' => $target->is_reviewed,
            'was_submitted' => $target->isSubmitted(),
        ]);

        return redirect()->back()
            ->with('success', 'Target berhasil dibuka kembali. Mahasiswa sekarang dapat mensubmit ulang target ini.');
    }

    /**
     * Close target (menutup target secara manual)
     * Hanya dosen yang bisa melakukan ini
     */
    public function close(TargetMingguan $target)
    {
        // Only dosen/admin can close targets
        $user = auth()->user();
        if (!$user->isDosen() && !$user->isAdmin() && !$user->isKoordinator()) {
            abort(403, 'Hanya dosen yang dapat menutup target.');
        }

        // Close the target
        $target->closeTarget();

        \Log::info('Target Closed', [
            'target_id' => $target->id,
            'closed_by' => auth()->id(),
            'group_id' => $target->group_id,
        ]);

        return redirect()->back()
            ->with('success', 'Target berhasil ditutup. Mahasiswa tidak dapat lagi mensubmit target ini.');
    }

    /**
     * Auto-close targets that have passed their deadline
     * This can be called by a scheduled task or manually by admin
     */
    public function autoCloseOverdueTargets()
    {
        // Only admin can trigger this
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isDosen() && !$user->isKoordinator()) {
            abort(403, 'Unauthorized.');
        }

        // Get all targets that should be closed
        // Termasuk yang sudah disubmit tapi belum direview
        $targets = TargetMingguan::where('is_open', true)
            ->where('is_reviewed', false)
            ->whereNotNull('deadline')
            ->where('deadline', '<', now())
            ->get();

        $closedCount = 0;
        foreach ($targets as $target) {
            if ($target->shouldBeClosed()) {
                $target->closeTarget();
                $closedCount++;
            }
        }

        \Log::info('Auto-closed overdue targets', [
            'count' => $closedCount,
            'triggered_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', "Berhasil menutup {$closedCount} target yang melewati deadline.");
    }
}



