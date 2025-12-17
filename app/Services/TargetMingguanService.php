<?php

namespace App\Services;

use App\Models\{Kelompok, TargetMingguan, RuangKelas, Pengguna};
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk mengelola business logic Target Mingguan
 */
class TargetMingguanService
{
    /**
     * Ambil daftar kelas (semua user bisa akses semua kelas)
     * 
     * @param Pengguna $user
     * @return Collection
     */
    public function getAssignedClassRoomIds(Pengguna $user): Collection
    {
        // Semua role (admin, koordinator, dosen) bisa akses semua kelas
        return RuangKelas::pluck('id');
    }

    /**
     * Ambil targets dengan filter berdasarkan role user
     * 
     * @param Pengguna $user
     * @param array $filters
     * @return Collection
     */
    public function getTargetsForUser(Pengguna $user, array $filters = []): array
    {
        $myClassRoomIds = $this->getAssignedClassRoomIds($user);
        
        $query = TargetMingguan::with(['group.classRoom', 'creator', 'completedByUser']);
        $baseStatsQuery = clone $query;

        // Filter tambahan berdasarkan kelas spesifik (jika ada filter)
        if (!empty($filters['class_room_id'])) {
            $query->whereHas('group', fn($q) => $q->where('class_room_id', $filters['class_room_id']));
            $baseStatsQuery->whereHas('group', fn($q) => $q->where('class_room_id', $filters['class_room_id']));
        }

        // Filter berdasarkan minggu
        if (!empty($filters['week_number'])) {
            $query->where('week_number', $filters['week_number']);
            $baseStatsQuery->where('week_number', $filters['week_number']);
        }

        // Filter berdasarkan status submission
        if (!empty($filters['status'])) {
            $query->where('submission_status', $filters['status']);
        }

        // Hitung statistik open/closed
        $stats = $this->calculateStats($query, $baseStatsQuery);

        // Filter berdasarkan lock_status
        $lockStatus = $filters['lock_status'] ?? 'aktif';
        $now = now();
        
        if ($lockStatus === 'aktif') {
            $query->where('is_open', true)->where('deadline', '>', $now);
        } elseif ($lockStatus === 'terkunci') {
            $query->where(fn($q) => $q->where('is_open', false)->orWhere('deadline', '<=', $now));
        }

        // Group targets by week
        $allTargets = $query->orderBy('week_number')->orderBy('deadline')->get();
        $targetsByWeek = $this->groupTargetsByWeek($allTargets);

        // Get class rooms untuk filter dropdown (semua kelas)
        $classRooms = RuangKelas::with('groups')->orderBy('name')->get();

        return compact('targetsByWeek', 'classRooms', 'stats');
    }

    /**
     * Buat target untuk kelompok-kelompok
     * 
     * @param array $data
     * @param Pengguna $creator
     * @return int Jumlah target yang dibuat
     */
    public function createTargetsForGroups(array $data, Pengguna $creator): int
    {
        $targetGroups = $this->resolveTargetGroups($data, $creator);

        if (empty($targetGroups)) {
            throw new \InvalidArgumentException('Tidak ada kelompok yang dipilih.');
        }

        // Validate todo items
        $todoItems = $this->validateTodoItems($data['todo_items'] ?? []);

        $createdCount = 0;
        foreach ($targetGroups as $groupId) {
            $target = TargetMingguan::create([
                'group_id' => $groupId,
                'created_by' => $creator->id,
                'week_number' => $data['week_number'],
                'title' => $data['title'],
                'description' => $data['description'],
                'deadline' => $data['deadline'],
                'submission_status' => 'pending',
                'is_completed' => false,
                'is_reviewed' => false,
            ]);

            // Create todo items
            foreach ($todoItems as $index => $item) {
                $target->todoItems()->create([
                    'title' => $item['title'],
                    'description' => $item['description'] ?? null,
                    'order' => $item['order'] ?? $index,
                    'is_completed_by_student' => false,
                    'is_verified_by_reviewer' => false,
                ]);
            }

            $createdCount++;
        }

        Log::info('WeeklyTargets Created with Todo Items', [
            'count' => $createdCount,
            'todo_items_count' => count($todoItems),
            'created_by' => $creator->id,
        ]);

        return $createdCount;
    }

    /**
     * Update semua target dalam satu minggu
     * 
     * @param int $weekNumber
     * @param int $classRoomId
     * @param array $data
     * @param Pengguna $user
     * @return int Jumlah target yang diupdate
     */
    public function updateWeekTargets(int $weekNumber, int $classRoomId, array $data, Pengguna $user): int
    {
        $this->authorizeClassRoomAccess($classRoomId, $user);

        // Jika deadline baru di masa depan, otomatis buka target
        $deadline = \Carbon\Carbon::parse($data['deadline']);
        if ($deadline->isFuture()) {
            $data['is_open'] = true;
        }

        $updatedCount = TargetMingguan::whereHas('group', fn($q) => $q->where('class_room_id', $classRoomId))
            ->where('week_number', $weekNumber)
            ->update($data);

        Log::info('WeeklyTargets Bulk Updated', [
            'week_number' => $weekNumber,
            'class_room_id' => $classRoomId,
            'updated_count' => $updatedCount,
            'updated_by' => $user->id,
            'auto_reopened' => $deadline->isFuture(),
        ]);

        return $updatedCount;
    }

    /**
     * Hapus semua target dalam satu minggu
     * 
     * @param int $weekNumber
     * @param int $classRoomId
     * @param Pengguna $user
     * @return int Jumlah target yang dihapus
     */
    public function destroyWeekTargets(int $weekNumber, int $classRoomId, Pengguna $user): int
    {
        $this->authorizeClassRoomAccess($classRoomId, $user);

        $targets = TargetMingguan::whereHas('group', fn($q) => $q->where('class_room_id', $classRoomId))
            ->where('week_number', $weekNumber)
            ->get();

        $deletedCount = $targets->count();

        foreach ($targets as $target) {
            $target->delete();
        }

        Log::warning('WeeklyTargets Bulk Deleted', [
            'week_number' => $weekNumber,
            'class_room_id' => $classRoomId,
            'deleted_count' => $deletedCount,
            'deleted_by' => $user->id,
        ]);

        return $deletedCount;
    }

    /**
     * Buka kembali semua target dalam satu minggu
     * 
     * @param int $weekNumber
     * @param int $classRoomId
     * @param Pengguna $user
     * @return int Jumlah target yang dibuka
     */
    public function reopenWeekTargets(int $weekNumber, int $classRoomId, Pengguna $user): int
    {
        $this->authorizeClassRoomAccess($classRoomId, $user);

        $targets = TargetMingguan::whereHas('group', fn($q) => $q->where('class_room_id', $classRoomId))
            ->where('week_number', $weekNumber)
            ->get();

        $reopenedCount = 0;
        foreach ($targets as $target) {
            $target->reopenTarget($user->id);
            $reopenedCount++;
        }

        Log::info('WeeklyTargets Bulk Reopened', [
            'week_number' => $weekNumber,
            'class_room_id' => $classRoomId,
            'reopened_count' => $reopenedCount,
            'reopened_by' => $user->id,
        ]);

        return $reopenedCount;
    }

    /**
     * Tutup semua target dalam satu minggu
     * 
     * @param int $weekNumber
     * @param int $classRoomId
     * @param Pengguna $user
     * @return int Jumlah target yang ditutup
     */
    public function closeWeekTargets(int $weekNumber, int $classRoomId, Pengguna $user): int
    {
        $this->authorizeClassRoomAccess($classRoomId, $user);

        $targets = TargetMingguan::whereHas('group', fn($q) => $q->where('class_room_id', $classRoomId))
            ->where('week_number', $weekNumber)
            ->get();

        $closedCount = 0;
        foreach ($targets as $target) {
            $target->closeTarget();
            $closedCount++;
        }

        Log::info('WeeklyTargets Bulk Closed', [
            'week_number' => $weekNumber,
            'class_room_id' => $classRoomId,
            'closed_count' => $closedCount,
            'closed_by' => $user->id,
        ]);

        return $closedCount;
    }

    /**
     * Auto-close targets yang melewati deadline
     * 
     * @param Pengguna $user
     * @return int Jumlah target yang ditutup
     */
    public function autoCloseOverdueTargets(Pengguna $user): int
    {
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

        Log::info('Auto-closed overdue targets', [
            'count' => $closedCount,
            'triggered_by' => $user->id,
        ]);

        return $closedCount;
    }

    /**
     * Resolve target groups berdasarkan tipe target
     */
    private function resolveTargetGroups(array $data, Pengguna $creator): array
    {
        $targetType = $data['target_type'];
        $targetGroups = [];

        if ($targetType === 'single') {
            $this->authorizeGroupAccess($data['group_id'], $creator);
            $targetGroups = [$data['group_id']];
        } elseif ($targetType === 'multiple') {
            foreach ($data['group_ids'] as $groupId) {
                $this->authorizeGroupAccess($groupId, $creator);
            }
            $targetGroups = $data['group_ids'];
        } elseif ($targetType === 'all_class') {
            $this->authorizeClassRoomAccess($data['class_room_id'], $creator);
            $targetGroups = Kelompok::where('class_room_id', $data['class_room_id'])
                ->pluck('id')
                ->toArray();
        }

        return $targetGroups;
    }

    /**
     * Validasi akses ke kelas (tidak ada pembatasan untuk dosen)
     */
    private function authorizeClassRoomAccess(int $classRoomId, Pengguna $user): void
    {
        // Dosen bisa akses semua kelas, hanya validasi kelas exists
        RuangKelas::findOrFail($classRoomId);
    }

    /**
     * Validasi akses ke kelompok (tidak ada pembatasan untuk dosen)
     */
    private function authorizeGroupAccess(int $groupId, Pengguna $user): void
    {
        // Dosen bisa akses semua kelompok, hanya validasi kelompok exists
        Kelompok::findOrFail($groupId);
    }

    /**
     * Validasi todo items
     */
    private function validateTodoItems(array $todoItems): array
    {
        $filtered = array_filter($todoItems, fn($item) => !empty($item['title']));

        if (empty($filtered)) {
            throw new \InvalidArgumentException('Minimal 1 todo item dengan judul harus diisi.');
        }

        return $filtered;
    }

    /**
     * Hitung statistik targets
     */
    private function calculateStats($query, $baseStatsQuery): array
    {
        $now = now();
        $statsQueryForCounts = clone $baseStatsQuery;

        $openCount = (clone $statsQueryForCounts)
            ->where('is_open', true)
            ->where('deadline', '>', $now)
            ->distinct('week_number')
            ->count('week_number');

        $closedCount = (clone $statsQueryForCounts)
            ->where(fn($q) => $q->where('is_open', false)->orWhere('deadline', '<=', $now))
            ->distinct('week_number')
            ->count('week_number');

        $statsQuery = clone $query;
        $total = $statsQuery->count();

        return [
            'total' => $total,
            'submitted' => (clone $statsQuery)->where('submission_status', 'submitted')->count(),
            'approved' => (clone $statsQuery)->where('submission_status', 'approved')->count(),
            'revision' => (clone $statsQuery)->where('submission_status', 'revision')->count(),
            'pending' => (clone $statsQuery)->where('submission_status', 'pending')->count(),
            'late' => (clone $statsQuery)->where('submission_status', 'late')->count(),
            'open' => $openCount,
            'closed' => $closedCount,
            'submitted_percentage' => $total > 0 
                ? round(((clone $statsQuery)->whereIn('submission_status', ['submitted', 'approved', 'revision'])->count() / $total) * 100)
                : 0,
        ];
    }

    /**
     * Empty stats untuk case tidak ada kelas
     */
    private function getEmptyStats(): array
    {
        return [
            'total' => 0, 
            'submitted' => 0, 
            'approved' => 0, 
            'revision' => 0, 
            'pending' => 0, 
            'late' => 0,
            'submitted_percentage' => 0, 
            'open' => 0, 
            'closed' => 0,
        ];
    }

    /**
     * Group targets berdasarkan week number
     */
    private function groupTargetsByWeek(Collection $targets): Collection
    {
        return $targets->groupBy('week_number')->map(function($weekTargets) {
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
    }
}
