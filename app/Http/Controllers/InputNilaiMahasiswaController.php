<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Pengguna, Kriteria, NilaiMahasiswa, RuangKelas};
use Illuminate\Support\Facades\DB;

class InputNilaiMahasiswaController extends Controller
{
    /**
     * Tampilkan form input nilai mahasiswa
     */
    public function index()
    {
        // Get dosen yang login
        $dosen = auth()->user();
        
        // Get kelas yang diampu dosen (sesuaikan dengan relasi di sistem Anda)
        $classRooms = RuangKelas::all();
        
        // Get kriteria untuk mahasiswa
        $criteria = Kriteria::where('segment', 'student')->get();
        
        return view('nilai.input-mahasiswa', compact('dosen', 'classRooms', 'criteria'));
    }
    
    /**
     * Get mahasiswa berdasarkan kelas (AJAX)
     */
    public function getStudentsByClass(Request $request)
    {
        $classRoomId = $request->class_room_id;
        
        $students = Pengguna::where('role', 'mahasiswa')
            ->where('class_room_id', $classRoomId)
            ->with(['studentScores.criterion'])
            ->orderBy('nim')
            ->get();
        
        return response()->json([
            'success' => true,
            'students' => $students
        ]);
    }
    
    /**
     * Simpan nilai mahasiswa
     */
    public function store(Request $request)
    {
        $request->validate([
            'scores' => 'required|array',
            'scores.*.user_id' => 'required|exists:pengguna,id',
            'scores.*.criterion_id' => 'required|exists:kriteria,id',
            'scores.*.skor' => 'required|numeric|min:0|max:100',
        ]);
        
        DB::beginTransaction();
        try {
            foreach ($request->scores as $scoreData) {
                NilaiMahasiswa::updateOrCreate(
                    [
                        'user_id' => $scoreData['user_id'],
                        'criterion_id' => $scoreData['criterion_id'],
                    ],
                    [
                        'skor' => $scoreData['skor']
                    ]
                );
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Nilai berhasil disimpan!'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan nilai: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Hitung ranking mahasiswa berdasarkan nilai yang diinput
     */
    public function calculate(Request $request)
    {
        try {
            $request->validate([
                'class_room_id' => 'required|exists:ruang_kelas,id',
            ]);
            
            $classRoomId = $request->class_room_id;
            
            // Get mahasiswa dari kelas tersebut
            $students = Pengguna::where('role', 'mahasiswa')
                ->where('class_room_id', $classRoomId)
                ->with(['studentScores.criterion'])
                ->get();
            
            if ($students->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada mahasiswa di kelas ini'
                ], 404);
            }
            
            // Get kriteria
            $criteria = Kriteria::where('segment', 'student')->get();
            
            if ($criteria->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kriteria mahasiswa belum diatur'
                ], 404);
            }
            
            // Hitung ranking dengan metode SAW (Simple Additive Weighting)
            // Step 1: Kumpulkan semua nilai per kriteria untuk normalisasi
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
            
            // Step 2: Hitung nilai maksimum per kriteria untuk normalisasi SAW
            $maxScores = [];
            foreach ($criteria as $criterion) {
                $scores = array_filter($allScores[$criterion->id], function($v) { return $v > 0; });
                $maxScores[$criterion->id] = !empty($scores) ? max($scores) : 1;
            }
            
            // Step 3: Hitung ranking untuk setiap mahasiswa
            $rankings = [];
            foreach ($students as $student) {
                $totalScore = 0;
                $criteriaScores = [];
                
                foreach ($criteria as $criterion) {
                    $rawScore = $allScores[$criterion->id][$student->id];
                    
                    // Normalisasi SAW (benefit: nilai/max)
                    $maxScore = $maxScores[$criterion->id];
                    $normalized = $maxScore > 0 ? $rawScore / $maxScore : 0;
                    $weighted = $normalized * $criterion->bobot;
                    
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
            
            // Sort by total_score descending
            usort($rankings, function($a, $b) {
                return $b['total_score'] <=> $a['total_score'];
            });
            
            // Add rank
            foreach ($rankings as $index => &$ranking) {
                $ranking['rank'] = $index + 1;
            }
            
            return response()->json([
                'success' => true,
                'rankings' => $rankings
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->errors())
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error calculating student ranking: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Hapus nilai mahasiswa
     */
    public function deleteStudentScores(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:pengguna,id',
        ]);
        
        try {
            // Hapus semua nilai mahasiswa
            $deleted = NilaiMahasiswa::where('user_id', $request->user_id)->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Nilai mahasiswa berhasil dihapus!',
                'deleted_count' => $deleted
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus nilai: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Hapus nilai mahasiswa untuk kriteria tertentu
     */
    public function deleteScore(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:pengguna,id',
            'criterion_id' => 'required|exists:kriteria,id',
        ]);
        
        try {
            $deleted = NilaiMahasiswa::where('user_id', $request->user_id)
                ->where('criterion_id', $request->criterion_id)
                ->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Nilai berhasil dihapus!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus nilai: ' . $e->getMessage()
            ], 500);
        }
    }
}


