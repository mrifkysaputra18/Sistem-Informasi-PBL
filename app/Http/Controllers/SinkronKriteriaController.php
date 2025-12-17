<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Kelompok;
use App\Models\NilaiKelompok;
use App\Models\RuangKelas;
use App\Models\SyncKriteriaLog;
use App\Models\TargetMingguan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SinkronKriteriaController extends Controller
{
    /**
     * Tampilan form sinkronisasi kriteria
     */
    public function index()
    {
        $user = Auth::user();
        
        // Dosen dan admin bisa akses semua kelas
        $classRooms = RuangKelas::all();

        // Get group criteria (segment = group)
        $criteria = Kriteria::where('segment', 'group')->get();

        // Get sync history
        $syncLogs = SyncKriteriaLog::with(['classRoom', 'syncedByUser'])
            ->orderBy('synced_at', 'desc')
            ->limit(20)
            ->get();

        return view('sync-kriteria.index', compact('classRooms', 'criteria', 'syncLogs'));
    }

    /**
     * Preview hasil sinkronisasi sebelum disimpan
     */
    public function preview(Request $request)
    {
        $request->validate([
            'class_room_id' => 'required|exists:ruang_kelas,id',
            'criteria_ids' => 'required|array|min:1',
            'criteria_ids.*' => 'exists:kriteria,id',
        ]);

        $classRoom = RuangKelas::findOrFail($request->class_room_id);
        $groups = Kelompok::where('class_room_id', $classRoom->id)->with('targets')->get();
        $criteria = Kriteria::whereIn('id', $request->criteria_ids)->get();

        $previewData = [];

        foreach ($groups as $group) {
            $groupData = [
                'group_id' => $group->id,
                'group_name' => $group->name,
                'criteria_values' => [],
            ];

            // Get reviewed targets for this group
            $reviewedTargets = TargetMingguan::where('group_id', $group->id)
                ->where('is_reviewed', true)
                ->get();

            foreach ($criteria as $criterion) {
                $calculatedValue = $this->calculateCriteriaValue($criterion, $reviewedTargets, $group);
                $currentValue = NilaiKelompok::where('group_id', $group->id)
                    ->where('criterion_id', $criterion->id)
                    ->value('score');

                $groupData['criteria_values'][] = [
                    'criterion_id' => $criterion->id,
                    'criterion_name' => $criterion->nama,
                    'current_value' => $currentValue,
                    'new_value' => $calculatedValue,
                ];
            }

            $previewData[] = $groupData;
        }

        return response()->json([
            'success' => true,
            'class_room' => $classRoom->name,
            'preview' => $previewData,
        ]);
    }

    /**
     * Execute sinkronisasi untuk semua kelas user
     */
    public function sync(Request $request)
    {
        $request->validate([
            'criteria_ids' => 'required|array|min:1',
            'criteria_ids.*' => 'exists:kriteria,id',
        ]);

        $user = Auth::user();
        
        // Dosen dan admin bisa sync semua kelas
        $classRooms = RuangKelas::all();

        if ($classRooms->isEmpty()) {
            return back()->with('error', 'Tidak ada kelas yang dapat disinkronisasi.');
        }

        $criteria = Kriteria::whereIn('id', $request->criteria_ids)->get();

        $totalGroups = 0;
        $allPreviousValues = [];
        $allNewValues = [];

        DB::beginTransaction();
        try {
            foreach ($classRooms as $classRoom) {
                $groups = Kelompok::where('class_room_id', $classRoom->id)->get();
                $totalGroups += $groups->count();

                foreach ($groups as $group) {
                    $reviewedTargets = TargetMingguan::where('group_id', $group->id)
                        ->where('is_reviewed', true)
                        ->get();

                    foreach ($criteria as $criterion) {
                        // Store previous value
                        $existingScore = NilaiKelompok::where('group_id', $group->id)
                            ->where('criterion_id', $criterion->id)
                            ->first();

                        $allPreviousValues[$group->id][$criterion->id] = $existingScore ? $existingScore->score : null;

                        // Calculate new value
                        $newValue = $this->calculateCriteriaValue($criterion, $reviewedTargets, $group);
                        $allNewValues[$group->id][$criterion->id] = $newValue;

                        // Update or create nilai kelompok
                        NilaiKelompok::updateOrCreate(
                            [
                                'group_id' => $group->id,
                                'criterion_id' => $criterion->id,
                            ],
                            [
                                'score' => $newValue,
                            ]
                        );
                    }
                }
            }

            // Create sync log (use null for class_room_id to indicate all classes)
            $syncLog = SyncKriteriaLog::create([
                'class_room_id' => null,
                'synced_by' => $user->id,
                'criteria_synced' => $request->criteria_ids,
                'previous_values' => $allPreviousValues,
                'new_values' => $allNewValues,
                'synced_at' => now(),
            ]);

            DB::commit();

            \Log::info('Criteria sync completed for all classes', [
                'sync_log_id' => $syncLog->id,
                'class_count' => $classRooms->count(),
                'criteria_count' => count($request->criteria_ids),
                'groups_count' => $totalGroups,
            ]);

            return redirect()
                ->route('sync-kriteria.index')
                ->with('success', "Sinkronisasi berhasil! {$totalGroups} kelompok di {$classRooms->count()} kelas telah diupdate untuk " . count($request->criteria_ids) . " kriteria.");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Criteria sync failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal melakukan sinkronisasi: ' . $e->getMessage());
        }
    }

    /**
     * Batalkan sinkronisasi (unsync)
     */
    public function unsync($syncLogId)
    {
        $user = Auth::user();
        $syncLog = SyncKriteriaLog::findOrFail($syncLogId);

        if (!$syncLog->canBeReverted()) {
            return back()->with('error', 'Sinkronisasi ini sudah dibatalkan sebelumnya.');
        }

        DB::beginTransaction();
        try {
            $previousValues = $syncLog->previous_values;

            foreach ($previousValues as $groupId => $criteriaValues) {
                foreach ($criteriaValues as $criterionId => $previousValue) {
                    if ($previousValue === null) {
                        // Hapus nilai jika sebelumnya tidak ada
                        NilaiKelompok::where('group_id', $groupId)
                            ->where('criterion_id', $criterionId)
                            ->delete();
                    } else {
                        // Kembalikan ke nilai sebelumnya
                        NilaiKelompok::where('group_id', $groupId)
                            ->where('criterion_id', $criterionId)
                            ->update(['score' => $previousValue]);
                    }
                }
            }

            $syncLog->markReverted($user->id);

            DB::commit();

            \Log::info('Criteria sync reverted', [
                'sync_log_id' => $syncLog->id,
                'reverted_by' => $user->id,
            ]);

            return back()->with('success', 'Sinkronisasi berhasil dibatalkan. Nilai dikembalikan ke sebelumnya.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Criteria sync revert failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal membatalkan sinkronisasi: ' . $e->getMessage());
        }
    }

    /**
     * Calculate criteria value based on criteria name/type
     */
    private function calculateCriteriaValue($criterion, $reviewedTargets, $group)
    {
        if ($reviewedTargets->isEmpty()) {
            return 0;
        }

        $criterionName = strtolower($criterion->nama);

        // Kecepatan Progres = Average verified percentage
        if (str_contains($criterionName, 'kecepatan') || str_contains($criterionName, 'progres')) {
            $totalPercentage = 0;
            foreach ($reviewedTargets as $target) {
                $totalPercentage += $target->getVerifiedPercentage();
            }
            return $reviewedTargets->count() > 0 ? round($totalPercentage / $reviewedTargets->count(), 2) : 0;
        }

        // Nilai Akhir PBL = Average quality_score
        if (str_contains($criterionName, 'nilai') && (str_contains($criterionName, 'akhir') || str_contains($criterionName, 'pbl'))) {
            $totalScore = $reviewedTargets->sum('quality_score');
            return $reviewedTargets->count() > 0 ? round($totalScore / $reviewedTargets->count(), 2) : 0;
        }

        // Ketepatan Waktu = Percentage submitted on time
        if (str_contains($criterionName, 'ketepatan') || str_contains($criterionName, 'waktu')) {
            $onTimeCount = $reviewedTargets->filter(function ($target) {
                return !in_array($target->submission_status, ['late']);
            })->count();
            return $reviewedTargets->count() > 0 ? round(($onTimeCount / $reviewedTargets->count()) * 100, 2) : 0;
        }

        // Default: Average final_score
        $totalFinalScore = $reviewedTargets->sum('final_score');
        return $reviewedTargets->count() > 0 ? round($totalFinalScore / $reviewedTargets->count(), 2) : 0;
    }
}
