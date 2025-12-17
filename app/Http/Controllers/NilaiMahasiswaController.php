<?php
// Controller NilaiMahasiswa - Mengelola nilai dan ranking mahasiswa

namespace App\Http\Controllers;

use App\Models\{Pengguna, Kriteria, NilaiMahasiswa, RuangKelas};
use App\Services\RankingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NilaiMahasiswaController extends Controller
{
    // Tampilkan halaman ranking mahasiswa (GET /student-scores)
    public function index(Request $request)
    {
        // Kueri mahasiswa dengan pemuatan relasi
        $query = Pengguna::where('role', 'mahasiswa')
            ->with(['classRoom.academicPeriod', 'groups', 'studentScores']);
        
        // Filter angkatan
        if ($request->filled('academic_year')) {
            $query->whereHas('classRoom.academicPeriod', function($q) use ($request) {
                $q->where('academic_year', $request->academic_year);
            });
        }
        
        // Filter kelas
        if ($request->filled('class_room_id') && $request->class_room_id !== 'all') {
            $query->where('class_room_id', $request->class_room_id);
        }
        
        $rankingQuery = clone $query;
        $students = $query->orderBy('name')->paginate(10)->withQueryString();
        $studentIds = $rankingQuery->pluck('id');
        
        $criteria = Kriteria::where('segment', 'student')->get();
        $scores = NilaiMahasiswa::whereIn('user_id', $studentIds)->get();
        
        // Hitung ranking dengan SAW
        $rankingService = new RankingService();
        $ranking = $rankingService->getStudentRankingWithDetails(
            $request->class_room_id === 'all' ? null : $request->class_room_id, 
            $studentIds->toArray()
        );
        
        $averageScore = $scores->count() > 0 ? $scores->avg('skor') : 0;
        $bestStudentsPerClass = $this->getBestStudentsPerClass($request->academic_year, $request->class_room_id === 'all' ? null : $request->class_room_id);
        
        $academicYears = \App\Models\PeriodeAkademik::orderBy('academic_year', 'desc')
            ->pluck('academic_year')->unique()->values();
        
        $classRoomsQuery = \App\Models\RuangKelas::with('academicPeriod')->withCount(['students']);
        if ($request->filled('academic_year')) {
            $classRoomsQuery->whereHas('academicPeriod', function($query) use ($request) {
                $query->where('academic_year', $request->academic_year);
            });
        }
        $classRooms = $classRoomsQuery->orderBy('name')->get();
        
        return view('nilai-mahasiswa.daftar', compact(
            'students', 'criteria', 'scores', 'ranking', 
            'averageScore', 'bestStudentsPerClass', 'academicYears', 'classRooms'
        ));
    }

    // Form tambah nilai (GET /student-scores/create)
    public function create()
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isDosen()) {
            abort(403, 'Hanya admin dan dosen yang dapat input nilai.');
        }

        $query = Pengguna::where('role', 'mahasiswa')->with(['classRoom'])->orderBy('name');

        if (auth()->user()->isDosen()) {
            $assignedClassIds = auth()->user()->kelasPblAktif()->pluck('id');
            $query->whereIn('class_room_id', $assignedClassIds);
        }

        return view('nilai-mahasiswa.tambah', [
            'students' => $query->get(),
            'criteria' => Kriteria::where('segment', 'student')->orderBy('id')->get(),
        ]);
    }

    // Simpan nilai (POST /student-scores)
    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isDosen()) {
            abort(403, 'Hanya admin dan dosen yang dapat input nilai.');
        }

        if (auth()->user()->isDosen()) {
            $student = Pengguna::findOrFail($request->user_id);
            if (!auth()->user()->isDosenPblDi($student->class_room_id)) {
                abort(403, 'Anda tidak ditugaskan di kelas ini.');
            }
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:pengguna,id',
            'criterion_id' => 'required|exists:kriteria,id',
            'skor' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // updateOrCreate: perbarui jika ada, buat jika belum
        NilaiMahasiswa::updateOrCreate(
            ['user_id' => $request->user_id, 'criterion_id' => $request->criterion_id],
            ['skor' => $request->skor]
        );
        
        return redirect()->route('student-scores.index')->with('ok', 'Nilai disimpan.');
    }

    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
    
    // Hitung ulang ranking (POST /student-scores/recalc)
    public function recalc()
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isDosen()) {
            abort(403, 'Hanya admin dan dosen yang dapat hitung ranking.');
        }

        $rankingService = new RankingService();
        $ranking = $rankingService->getStudentRankingWithDetails();
        
        return view('nilai-mahasiswa.peringkat', compact('ranking'));
    }
    
    // Ambil 3 mahasiswa terbaik per kelas
    private function getBestStudentsPerClass($academicYear = null, $classRoomId = null)
    {
        $bestStudents = [];
        $rankingService = new RankingService();
        
        $query = RuangKelas::with(['students', 'academicPeriod']);
        
        if ($academicYear) {
            $query->whereHas('academicPeriod', function($q) use ($academicYear) {
                $q->where('academic_year', $academicYear);
            });
        }
        
        if ($classRoomId) {
            $query->where('id', $classRoomId);
        }
        
        $classRooms = $query->get();
        
        foreach ($classRooms as $classRoom) {
            if ($classRoom->students->count() > 0) {
                $totals = $rankingService->computeStudentTotals();
                
                $classStudents = [];
                foreach ($classRoom->students as $student) {
                    if (isset($totals[$student->id])) {
                        $classStudents[] = [
                            'student' => $student,
                            'total_score' => $totals[$student->id],
                            'group' => $student->groups->first()
                        ];
                    }
                }
                
                // Urutkan menurun
                usort($classStudents, fn($a, $b) => $b['total_score'] <=> $a['total_score']);
                
                $topStudents = array_slice($classStudents, 0, 3);
                
                if (count($topStudents) > 0) {
                    $bestStudents[] = [
                        'class_room' => $classRoom,
                        'top_students' => $topStudents
                    ];
                }
            }
        }
        
        return $bestStudents;
    }
}
