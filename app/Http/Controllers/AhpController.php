<?php
// Controller AHP - Menangani perhitungan bobot kriteria dengan metode AHP

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Services\AhpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AhpController extends Controller
{
    protected $ahpService; // Layanan untuk logika AHP

    // Masukkan AhpService via konstruktor
    public function __construct(AhpService $ahpService)
    {
        $this->ahpService = $ahpService;
    }

    // Tampilkan halaman AHP dengan form perbandingan (GET /ahp)
    public function index(Request $request)
    {
        $segment = $request->get('segment', 'group'); // Bawaan segment = group
        
        if (!in_array($segment, ['group', 'student'])) {
            $segment = 'group';
        }

        $criteria = Kriteria::where('segment', $segment)->orderBy('id')->get();

        // Minimal 2 kriteria untuk AHP
        if ($criteria->count() < 2) {
            return redirect()->route('criteria.index')
                ->with('error', 'Minimal 2 kriteria diperlukan untuk perhitungan AHP.');
        }

        $comparisons = $this->ahpService->getComparisons($segment); // Ambil data perbandingan

        // Cek apakah semua perbandingan sudah diisi
        $allFilled = true;
        foreach ($comparisons as $comparison) {
            if ($comparison['value'] == 1) {
                $allFilled = false;
                break;
            }
        }

        // Hitung hasil jika semua sudah diisi
        $result = null;
        if ($allFilled) {
            try {
                $result = $this->ahpService->calculateWeights($segment);
            } catch (\Exception $e) {
            }
        }

        return view('ahp.daftar', compact('segment', 'criteria', 'comparisons', 'result'));
    }

    // Simpan perbandingan via AJAX (POST /ahp/save)
    public function saveComparison(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'segment' => 'required|in:group,student',
            'criterion_a_id' => 'required|exists:kriteria,id',
            'criterion_b_id' => 'required|exists:kriteria,id',
            'value' => 'required|numeric|min:0.111|max:9', // Skala 1/9 - 9
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $this->ahpService->saveComparison(
                $request->segment, $request->criterion_a_id, 
                $request->criterion_b_id, $request->value
            );
            return response()->json(['success' => true, 'message' => 'Perbandingan disimpan']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Hitung bobot AHP (GET /ahp/calculate)
    public function calculate(Request $request)
    {
        $segment = $request->get('segment', 'group');

        try {
            $result = $this->ahpService->calculateWeights($segment);
            return redirect()->route('ahp.index', ['segment' => $segment])
                ->with('result', $result)
                ->with('success', 'Perhitungan AHP berhasil! CR = ' . $result['cr']);
        } catch (\Exception $e) {
            return redirect()->route('ahp.index', ['segment' => $segment])
                ->with('error', 'Kesalahan: ' . $e->getMessage());
        }
    }

    // Terapkan bobot ke kriteria (POST /ahp/apply)
    public function applyWeights(Request $request)
    {
        $segment = $request->get('segment', 'group');

        try {
            $result = $this->ahpService->calculateWeights($segment);

            // CR harus <= 0.1 agar konsisten
            if (!$result['is_consistent']) {
                return redirect()->route('ahp.index', ['segment' => $segment])
                    ->with('error', 'CR > 0.1, tidak konsisten. Revisi perbandingan Anda.');
            }

            $this->ahpService->applyWeightsToCriteria($segment, $result['weights']);
            return redirect()->route('criteria.index')
                ->with('ok', 'Bobot kriteria diperbarui dengan AHP!');
        } catch (\Exception $e) {
            return redirect()->route('ahp.index', ['segment' => $segment])
                ->with('error', 'Kesalahan: ' . $e->getMessage());
        }
    }

    // Reset semua perbandingan (POST /ahp/reset)
    public function reset(Request $request)
    {
        $segment = $request->get('segment', 'group');

        try {
            $this->ahpService->resetComparisons($segment);
            return redirect()->route('ahp.index', ['segment' => $segment])
                ->with('success', 'Perbandingan direset!');
        } catch (\Exception $e) {
            return redirect()->route('ahp.index', ['segment' => $segment])
                ->with('error', 'Kesalahan: ' . $e->getMessage());
        }
    }

    // Tampilkan halaman bantuan (GET /ahp/help)
    public function help()
    {
        return view('ahp.bantuan');
    }
}
