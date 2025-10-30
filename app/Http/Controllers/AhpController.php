<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Services\AhpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AhpController extends Controller
{
    protected $ahpService;

    public function __construct(AhpService $ahpService)
    {
        $this->ahpService = $ahpService;
    }

    /**
     * Show AHP comparison form
     */
    public function index(Request $request)
    {
        $segment = $request->get('segment', 'group');
        
        // Validate segment
        if (!in_array($segment, ['group', 'student'])) {
            $segment = 'group';
        }

        $criteria = Kriteria::where('segment', $segment)->orderBy('id')->get();

        if ($criteria->count() < 2) {
            return redirect()->route('criteria.index')
                ->with('error', 'Minimal 2 kriteria diperlukan untuk perhitungan AHP. Silakan tambahkan kriteria terlebih dahulu.');
        }

        // Get existing comparisons
        $comparisons = $this->ahpService->getComparisons($segment);

        // Check if all comparisons are filled
        $allFilled = true;
        foreach ($comparisons as $comparison) {
            if ($comparison['value'] == 1) {
                $allFilled = false;
                break;
            }
        }

        $result = null;
        if ($allFilled) {
            try {
                $result = $this->ahpService->calculateWeights($segment);
            } catch (\Exception $e) {
                // Jika error, tetap tampilkan form
            }
        }

        return view('ahp.daftar', compact('segment', 'criteria', 'comparisons', 'result'));
    }

    /**
     * Save pairwise comparison
     */
    public function saveComparison(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'segment' => 'required|in:group,student',
            'criterion_a_id' => 'required|exists:kriteria,id',
            'criterion_b_id' => 'required|exists:kriteria,id',
            'value' => 'required|numeric|min:0.111|max:9', // 1/9 sampai 9
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . $validator->errors()->first()
            ], 422);
        }

        try {
            $this->ahpService->saveComparison(
                $request->segment,
                $request->criterion_a_id,
                $request->criterion_b_id,
                $request->value
            );

            return response()->json([
                'success' => true,
                'message' => 'Perbandingan berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate AHP weights
     */
    public function calculate(Request $request)
    {
        $segment = $request->get('segment', 'group');

        try {
            $result = $this->ahpService->calculateWeights($segment);

            return redirect()->route('ahp.index', ['segment' => $segment])
                ->with('result', $result)
                ->with('success', 'Perhitungan AHP berhasil! CR = ' . $result['cr'] . 
                    ($result['is_consistent'] ? ' (Konsisten ✓)' : ' (Tidak Konsisten ✗)'));
        } catch (\Exception $e) {
            return redirect()->route('ahp.index', ['segment' => $segment])
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Apply calculated weights to criteria
     */
    public function applyWeights(Request $request)
    {
        $segment = $request->get('segment', 'group');

        try {
            $result = $this->ahpService->calculateWeights($segment);

            if (!$result['is_consistent']) {
                return redirect()->route('ahp.index', ['segment' => $segment])
                    ->with('error', 'Bobot tidak dapat diterapkan karena Consistency Ratio > 0.1. Silakan revisi perbandingan Anda.');
            }

            $this->ahpService->applyWeightsToCriteria($segment, $result['weights']);

            return redirect()->route('criteria.index')
                ->with('ok', 'Bobot kriteria berhasil diperbarui menggunakan metode AHP!');
        } catch (\Exception $e) {
            return redirect()->route('ahp.index', ['segment' => $segment])
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Reset all comparisons
     */
    public function reset(Request $request)
    {
        $segment = $request->get('segment', 'group');

        try {
            $this->ahpService->resetComparisons($segment);

            return redirect()->route('ahp.index', ['segment' => $segment])
                ->with('success', 'Semua perbandingan berhasil direset!');
        } catch (\Exception $e) {
            return redirect()->route('ahp.index', ['segment' => $segment])
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Show AHP tutorial/help
     */
    public function help()
    {
        return view('ahp.bantuan');
    }
}


