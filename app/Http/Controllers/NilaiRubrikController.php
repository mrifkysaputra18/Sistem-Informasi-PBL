<?php

namespace App\Http\Controllers;

use App\Models\{KelasMataKuliah, NilaiRubrik, Pengguna, RuangKelas, RubrikItem};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

/**
 * Controller untuk input nilai mahasiswa berdasarkan rubrik.
 * Dosen dapat menilai mahasiswa berdasarkan rubrik yang telah dipilih.
 */
class NilaiRubrikController extends Controller
{
    /**
     * Daftar kelas dengan mata kuliah yang bisa dinilai oleh dosen
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Build query berdasarkan role
        $query = KelasMataKuliah::with([
            'classRoom.academicPeriod',
            'mataKuliah',
            'rubrikPenilaian.items'
        ])->whereNotNull('rubrik_penilaian_id'); // Hanya yang sudah ada rubrik

        // Jika dosen, filter berdasarkan mata kuliah yang diampu
        if ($user->isDosen()) {
            $mataKuliahIds = $user->mataKuliahs()->pluck('mata_kuliah.id');
            $query->whereIn('mata_kuliah_id', $mataKuliahIds);
        }

        // Filter by kelas
        if ($request->filled('class_room_id')) {
            $query->where('class_room_id', $request->class_room_id);
        }

        $kelasMataKuliahs = $query->get();

        // Get daftar kelas untuk filter
        $classRooms = RuangKelas::with('academicPeriod')
            ->orderBy('name')
            ->get();

        return view('nilai-rubrik.index', compact('kelasMataKuliahs', 'classRooms'));
    }

    /**
     * Form input nilai untuk satu kelas-mata kuliah
     */
    public function input(KelasMataKuliah $kelasMataKuliah)
    {
        // Cek apakah rubrik sudah dipilih
        if (!$kelasMataKuliah->rubrikPenilaian) {
            return redirect()->back()
                ->with('error', 'Rubrik belum dipilih untuk mata kuliah ini.');
        }

        // Get mahasiswa dalam kelas ini
        $mahasiswas = $kelasMataKuliah->classRoom->students()
            ->orderBy('name')
            ->get();

        // Get rubrik items
        $rubrikItems = $kelasMataKuliah->rubrikPenilaian->items;

        // Get nilai yang sudah ada
        $nilaiExisting = NilaiRubrik::where('kelas_mata_kuliah_id', $kelasMataKuliah->id)
            ->get()
            ->groupBy('user_id');

        return view('nilai-rubrik.input', compact('kelasMataKuliah', 'mahasiswas', 'rubrikItems', 'nilaiExisting'));
    }

    /**
     * Simpan nilai mahasiswa
     * Dengan validasi akses: dosen UTS hanya bisa input item UTS, dosen UAS hanya bisa input item UAS
     */
    public function store(Request $request, KelasMataKuliah $kelasMataKuliah)
    {
        $validator = Validator::make($request->all(), [
            'nilai' => 'required|array',
            'nilai.*.*' => 'nullable|numeric|min:0|max:100',
            'catatan' => 'nullable|array',
        ], [
            'nilai.required' => 'Data nilai harus diisi',
            'nilai.*.*.numeric' => 'Nilai harus berupa angka',
            'nilai.*.*.min' => 'Nilai minimal 0',
            'nilai.*.*.max' => 'Nilai maksimal 100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = auth()->user();
        $isAdmin = $user->isAdmin();
        
        // Cek akses dosen
        $canInputUts = $isAdmin || $kelasMataKuliah->canInputNilaiUts($user->id);
        $canInputUas = $isAdmin || $kelasMataKuliah->canInputNilaiUas($user->id);
        
        if (!$canInputUts && !$canInputUas) {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki akses untuk menginput nilai.');
        }
        
        // Ambil mapping rubrik item ke periode
        $rubrikItems = $kelasMataKuliah->rubrikPenilaian->items->keyBy('id');

        DB::beginTransaction();
        try {
            $nilaiDisimpan = 0;
            $nilaiDitolak = 0;
            
            foreach ($request->nilai as $userId => $items) {
                foreach ($items as $rubrikItemId => $nilai) {
                    if ($nilai !== null && $nilai !== '') {
                        // Cek akses berdasarkan periode item
                        $item = $rubrikItems->get($rubrikItemId);
                        if (!$item) continue;
                        
                        $isUts = $item->periode_ujian === 'uts';
                        $hasAccess = $isUts ? $canInputUts : $canInputUas;
                        
                        if (!$hasAccess) {
                            $nilaiDitolak++;
                            continue; // Skip jika tidak punya akses
                        }
                        
                        NilaiRubrik::updateOrCreate(
                            [
                                'user_id' => $userId,
                                'kelas_mata_kuliah_id' => $kelasMataKuliah->id,
                                'rubrik_item_id' => $rubrikItemId,
                            ],
                            [
                                'nilai' => $nilai,
                                'catatan' => $request->catatan[$userId][$rubrikItemId] ?? null,
                                'dinilai_oleh' => $user->id,
                                'dinilai_pada' => now(),
                            ]
                        );
                        $nilaiDisimpan++;
                    }
                }
            }

            DB::commit();

            $message = "Nilai berhasil disimpan ({$nilaiDisimpan} nilai tersimpan).";
            if ($nilaiDitolak > 0) {
                $message .= " {$nilaiDitolak} nilai dilewati karena tidak memiliki akses.";
            }

            return redirect()->route('nilai-rubrik.input', $kelasMataKuliah)
                ->with('ok', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan nilai: ' . $e->getMessage());
        }
    }

    /**
     * Lihat rekap nilai per kelas-mata kuliah
     */
    public function rekap(KelasMataKuliah $kelasMataKuliah)
    {
        // Get mahasiswa dalam kelas ini
        $mahasiswas = $kelasMataKuliah->classRoom->students()
            ->orderBy('name')
            ->get();

        // Get rubrik items
        $rubrikItems = $kelasMataKuliah->rubrikPenilaian->items;

        // Get semua nilai
        $nilaiRubriks = NilaiRubrik::where('kelas_mata_kuliah_id', $kelasMataKuliah->id)
            ->with(['rubrikItem', 'mahasiswa'])
            ->get()
            ->groupBy('user_id');

        // Hitung total nilai per mahasiswa
        $rekapNilai = [];
        foreach ($mahasiswas as $mahasiswa) {
            $total = NilaiRubrik::hitungTotalNilai($mahasiswa->id, $kelasMataKuliah->id);
            $rekapNilai[$mahasiswa->id] = [
                'mahasiswa' => $mahasiswa,
                'total' => $total,
                'nilai_per_item' => $nilaiRubriks[$mahasiswa->id] ?? collect(),
            ];
        }

        // Sort by total descending
        uasort($rekapNilai, fn($a, $b) => $b['total'] <=> $a['total']);

        return view('nilai-rubrik.rekap', compact('kelasMataKuliah', 'rubrikItems', 'rekapNilai'));
    }

    /**
     * Get rata-rata nilai per mata kuliah untuk mahasiswa (untuk integrasi SAW)
     * 
     * @param int $userId
     * @return array [mata_kuliah_id => rata_rata_nilai]
     */
    public static function getRataRataNilaiPerMataKuliah(int $userId): array
    {
        $hasil = [];

        $kelasMataKuliahs = KelasMataKuliah::whereHas('classRoom.students', function ($q) use ($userId) {
            $q->where('pengguna.id', $userId);
        })->get();

        foreach ($kelasMataKuliahs as $km) {
            $total = NilaiRubrik::hitungTotalNilai($userId, $km->id);
            if ($total > 0) {
                $hasil[$km->mata_kuliah_id] = $total;
            }
        }

        return $hasil;
    }
}
