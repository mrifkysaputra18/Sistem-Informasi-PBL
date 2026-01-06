<?php
// Controller InputNilaiMahasiswa - Form input nilai massal dengan AJAX

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Pengguna, Kriteria, NilaiMahasiswa, RuangKelas};
use Illuminate\Support\Facades\DB;

class InputNilaiMahasiswaController extends Controller
{
    // Tampilkan form input nilai (GET /scores/student-input)
    public function index()
    {
        $dosen = auth()->user();
        $classRooms = RuangKelas::all();
        $criteria = Kriteria::where('segment', 'student')->get();
        
        return view('nilai.input-mahasiswa', compact('dosen', 'classRooms', 'criteria'));
    }
    
    // Ambil mahasiswa berdasarkan kelas (AJAX)
    public function getStudentsByClass(Request $request)
    {
        $classRoomId = $request->class_room_id;
        
        $students = Pengguna::where('role', 'mahasiswa')
            ->where('class_room_id', $classRoomId)
            ->with(['studentScores.criterion'])
            ->orderBy('nim')
            ->get();
        
        return response()->json(['success' => true, 'students' => $students]);
    }
    
    // Simpan nilai massal (POST /scores/student-input/store)
    public function store(Request $request)
    {
        $dosen = auth()->user();
        
        // Validate: HANYA Dosen PBL yang bisa input nilai mahasiswa
        if ($request->filled('class_room_id') && $dosen->isDosen()) {
            if (!$dosen->isDosenPblFor($request->class_room_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fitur ini hanya untuk Dosen PBL.'
                ], 403);
            }
        }
        
        $request->validate([
            'scores' => 'required|array',
            'scores.*.user_id' => 'required|exists:pengguna,id',
            'scores.*.criterion_id' => 'required|exists:kriteria,id',
            'scores.*.skor' => 'nullable|numeric|min:0|max:100',
        ]);
        
        DB::beginTransaction(); // Mulai transaksi
        try {
            foreach ($request->scores as $scoreData) {
                if (!isset($scoreData['skor']) || $scoreData['skor'] === null || $scoreData['skor'] === '') {
                    continue;
                }
                
                NilaiMahasiswa::updateOrCreate(
                    ['user_id' => $scoreData['user_id'], 'criterion_id' => $scoreData['criterion_id']],
                    ['skor' => $scoreData['skor']]
                );
            }
            
            DB::commit(); // Simpan jika sukses
            return response()->json(['success' => true, 'message' => 'Nilai disimpan!']);
            
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan jika gagal
            return response()->json(['success' => false, 'message' => 'Gagal: ' . $e->getMessage()], 500);
        }
    }
    
    // Hitung ranking dengan metode SAW (POST /scores/student-input/calculate)
    public function calculate(Request $request)
    {
        try {
            $request->validate(['class_room_id' => 'required|exists:ruang_kelas,id']);
            $classRoomId = $request->class_room_id;
            
            $students = Pengguna::where('role', 'mahasiswa')
                ->where('class_room_id', $classRoomId)
                ->with(['studentScores.criterion'])
                ->get();
            
            if ($students->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Tidak ada mahasiswa'], 404);
            }
            
            $criteria = Kriteria::where('segment', 'student')->get();
            
            if ($criteria->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Kriteria belum diatur'], 404);
            }
            
            // Kumpulkan nilai per kriteria
            $allScores = [];
            foreach ($criteria as $criterion) {
                $allScores[$criterion->id] = [];
                foreach ($students as $student) {
                    $score = NilaiMahasiswa::where('user_id', $student->id)
                        ->where('criterion_id', $criterion->id)
                        ->value('skor') ?? 0;
                    $allScores[$criterion->id][$student->id] = (float) $score;
                }
            }
            
            // Hitung maks per kriteria untuk normalisasi
            $maxScores = [];
            foreach ($criteria as $criterion) {
                $scores = array_filter($allScores[$criterion->id], fn($v) => $v > 0);
                $maxScores[$criterion->id] = !empty($scores) ? max($scores) : 1;
            }
            
            // Hitung ranking SAW
            $rankings = [];
            foreach ($students as $student) {
                $totalScore = 0;
                $criteriaScores = [];
                
                foreach ($criteria as $criterion) {
                    $rawScore = $allScores[$criterion->id][$student->id];
                    $maxScore = $maxScores[$criterion->id];
                    $normalized = $maxScore > 0 ? $rawScore / $maxScore : 0; // Normalisasi
                    $weighted = $normalized * $criterion->bobot; // Kalikan bobot
                    $totalScore += $weighted;
                    
                    $criteriaScores[] = [
                        'criterion_name' => $criterion->nama,
                        'raw_score' => $rawScore,
                        'weight' => (float) $criterion->bobot,
                        'weighted_score' => (float) $weighted,
                    ];
                }
                
                $rankings[] = [
                    'student_id' => $student->id,
                    'student_name' => $student->name,
                    'student_nim' => $student->nim,
                    'total_score' => (float) $totalScore,
                    'criteria_scores' => $criteriaScores,
                ];
            }
            
            // Urutkan menurun
            usort($rankings, fn($a, $b) => $b['total_score'] <=> $a['total_score']);
            
            // Tambah nomor peringkat
            foreach ($rankings as $index => &$ranking) {
                $ranking['rank'] = $index + 1;
            }
            
            return response()->json(['success' => true, 'rankings' => $rankings]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    // Hapus semua nilai mahasiswa
    public function deleteStudentScores(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:pengguna,id']);
        
        try {
            $deleted = NilaiMahasiswa::where('user_id', $request->user_id)->delete();
            return response()->json(['success' => true, 'message' => 'Nilai dihapus!', 'deleted_count' => $deleted]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    // Hapus nilai untuk satu kriteria
    public function deleteScore(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:pengguna,id',
            'criterion_id' => 'required|exists:kriteria,id',
        ]);
        
        try {
            NilaiMahasiswa::where('user_id', $request->user_id)
                ->where('criterion_id', $request->criterion_id)
                ->delete();
            return response()->json(['success' => true, 'message' => 'Nilai dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
