{{-- View: Daftar Ranking Mahasiswa | Controller: NilaiMahasiswaController@index | Route: student-scores.index --}}
<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header: Judul dan Tombol Hitung Ulang --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">RANKING MAHASISWA</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">Analisis performa dan ranking mahasiswa berdasarkan kriteria penilaian.</p>
                </div>
                {{-- Tombol hitung ulang (admin/dosen) --}}
                @if(auth()->user()->isAdmin() || auth()->user()->isDosen())
                <div class="flex flex-wrap gap-3">
                    <form action="{{ route('student-scores.recalc') }}" method="POST" class="inline recalc-form">
                        @csrf
                        <button type="button" class="recalc-btn inline-flex items-center px-5 py-2.5 bg-orange-500 hover:bg-orange-600 border-2 border-orange-700 rounded-lg font-bold text-white text-sm shadow-lg transform hover:-translate-y-1 transition-all">
                            <i class="fa-solid fa-calculator mr-2 text-lg"></i>
                            <span>Hitung Ulang</span>
                        </button>
                    </form>
                </div>
                @endif
            </div>

            {{-- Pesan sukses dari session --}}
            @if(session('ok'))
                <div x-data="{ show: true }" x-show="show" class="bg-emerald-100 border-l-8 border-emerald-600 text-emerald-800 px-6 py-4 rounded-lg shadow-md mb-8 flex items-start justify-between">
                    <div class="flex items-center">
                        <i class="fa-solid fa-check-circle text-2xl mr-4 text-emerald-600"></i>
                        <span class="font-bold text-lg">{{ session('ok') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-600 hover:text-emerald-800"><i class="fa-solid fa-times text-xl"></i></button>
                </div>
            @endif

            {{-- Kartu Statistik --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-md border-b-4 border-indigo-600 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Total Mahasiswa</p>
                        <p class="text-3xl font-black text-gray-800">{{ $students->total() }}</p>
                    </div>
                    <div class="p-3 bg-indigo-100 rounded-full text-indigo-600"><i class="fa-solid fa-user-graduate text-2xl"></i></div>
                </div>
                <div class="bg-white rounded-xl shadow-md border-b-4 border-emerald-600 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Total Kriteria</p>
                        <p class="text-3xl font-black text-gray-800">{{ $criteria->count() }}</p>
                    </div>
                    <div class="p-3 bg-emerald-100 rounded-full text-emerald-600"><i class="fa-solid fa-list-check text-2xl"></i></div>
                </div>
                <div class="bg-white rounded-xl shadow-md border-b-4 border-purple-600 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Data Nilai</p>
                        <p class="text-3xl font-black text-gray-800">{{ $scores->count() }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-full text-purple-600"><i class="fa-solid fa-star text-2xl"></i></div>
                </div>
                <div class="bg-white rounded-xl shadow-md border-b-4 border-orange-600 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Rata-rata Skor</p>
                        <p class="text-3xl font-black text-gray-800">{{ number_format($averageScore, 1, ',', '.') }}</p>
                    </div>
                    <div class="p-3 bg-orange-100 rounded-full text-orange-600"><i class="fa-solid fa-chart-line text-2xl"></i></div>
                </div>
            </div>

            {{-- Form Filter --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-indigo-600"></div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fa-solid fa-filter mr-2 text-indigo-600"></i> Filter Ranking
                </h3>
                <form method="GET" action="{{ route('student-scores.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Angkatan (Tahun)</label>
                        <select name="academic_year" class="w-full h-10 bg-gray-50 border-2 border-gray-200 rounded-lg text-sm font-semibold focus:border-indigo-600 focus:ring-0 cursor-pointer">
                            <option value="">Semua Angkatan</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Kelas</label>
                        <select name="class_room_id" class="w-full h-10 bg-gray-50 border-2 border-gray-200 rounded-lg text-sm font-semibold focus:border-indigo-600 focus:ring-0 cursor-pointer">
                            <option value="">Semua Kelas</option>
                            @foreach($classRooms as $classRoom)
                                <option value="{{ $classRoom->id }}" {{ request('class_room_id') == $classRoom->id ? 'selected' : '' }}>{{ $classRoom->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 h-10 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-md transition-all flex items-center justify-center text-sm">Terapkan</button>
                        @if(request()->hasAny(['academic_year', 'class_room_id']))
                            <a href="{{ route('student-scores.index') }}" class="h-10 w-10 bg-white border-2 border-gray-300 hover:border-red-500 hover:bg-red-50 text-gray-600 hover:text-red-600 rounded-lg transition-all flex items-center justify-center" title="Reset"><i class="fa-solid fa-times text-lg"></i></a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Accordion Mahasiswa Terbaik Per Kelas --}}
            <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden mb-8" x-data="{ showTop: false }">
                <button @click="showTop = !showTop" class="w-full px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between hover:bg-gray-100 transition-colors">
                    <h3 class="font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-medal text-yellow-500"></i> Mahasiswa Terbaik Per Kelas
                    </h3>
                    <i class="fa-solid fa-chevron-down text-gray-500 transition-transform duration-300" :class="showTop ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="showTop" x-collapse>
                    <div class="p-6 bg-gray-50/50 border-b border-gray-200">
                        @if(count($bestStudentsPerClass) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($bestStudentsPerClass as $classData)
                                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow">
                                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                                            <h4 class="font-bold text-gray-800">{{ $classData['class_room']->name }}</h4>
                                            <span class="text-xs font-mono bg-white border border-gray-300 px-2 py-0.5 rounded text-gray-600">{{ $classData['class_room']->code }}</span>
                                        </div>
                                        <div class="p-2 space-y-2">
                                            @foreach($classData['top_students'] as $index => $studentData)
                                                <div class="flex items-center p-2 rounded-lg {{ $index == 0 ? 'bg-yellow-50 border border-yellow-100' : ($index == 1 ? 'bg-gray-50 border border-gray-100' : ($index == 2 ? 'bg-orange-50 border border-orange-100' : '')) }}">
                                                    <div class="w-8 h-8 flex items-center justify-center rounded-full mr-3 {{ $index == 0 ? 'bg-yellow-400 text-white shadow-sm' : ($index == 1 ? 'bg-gray-400 text-white shadow-sm' : ($index == 2 ? 'bg-orange-400 text-white shadow-sm' : 'bg-indigo-100 text-indigo-600 font-bold text-xs')) }}">
                                                        @if($index < 3) <i class="fa-solid fa-trophy text-xs"></i> @else {{ $index + 1 }} @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="text-sm font-medium text-gray-900 truncate">{{ $studentData['student']->name }}</div>
                                                        <div class="text-xs text-indigo-600 font-medium mt-0.5">{{ number_format($studentData['total_score'], 1) }} poin</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">Belum ada data mahasiswa.</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Grid: Matriks Nilai & Ranking --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Tabel Matriks Nilai Mahasiswa --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden flex flex-col h-full">
                        {{-- Tab Navigasi Kelas --}}
                        <div class="flex border-b border-gray-200 bg-gray-50 overflow-x-auto">
                            @php $currentClass = request('class_room_id', 'all'); @endphp
                            <a href="{{ route('student-scores.index', array_merge(request()->except('page'), ['class_room_id' => 'all'])) }}" class="flex-shrink-0 px-6 py-4 text-sm font-bold uppercase tracking-widest transition-all border-b-4 {{ $currentClass == 'all' ? 'border-indigo-600 text-indigo-700 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100' }}">
                                <i class="fa-solid fa-users mr-2"></i> Semua
                            </a>
                            @foreach($classRooms as $class)
                                <a href="{{ route('student-scores.index', array_merge(request()->except('page'), ['class_room_id' => $class->id])) }}" class="flex-shrink-0 px-6 py-4 text-sm font-bold uppercase tracking-widest transition-all border-b-4 {{ $currentClass == $class->id ? 'border-indigo-600 text-indigo-700 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100' }}">
                                    <div class="flex items-center gap-2">
                                        <span>{{ $class->name }}</span>
                                        <span class="bg-gray-200 text-gray-600 text-[10px] py-0.5 px-2 rounded-full">{{ $class->students_count }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-800"><i class="fas fa-table mr-2 text-indigo-400"></i> Matriks Nilai Mahasiswa</h3>
                                <div class="text-sm text-gray-600 font-medium">Menampilkan {{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }} dari {{ $students->total() }} mahasiswa</div>
                            </div>

                            <div class="overflow-x-auto">
                                @if($students->count() > 0)
                                <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider sticky left-0 bg-gray-100 z-20 border-r border-gray-300 shadow-sm">Mahasiswa</th>
                                            @foreach($criteria as $criterion)
                                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-900 uppercase tracking-wider border-r border-gray-200 min-w-[100px]">
                                                <div class="flex flex-col items-center">
                                                    <span>{{ Str::limit($criterion->nama, 12) }}</span>
                                                    <span class="text-[10px] text-gray-600 bg-white px-1.5 py-0.5 rounded mt-1 border border-gray-300">{{ number_format($criterion->bobot * 100, 0) }}%</span>
                                                </div>
                                            </th>
                                            @endforeach
                                            <th class="px-4 py-3 text-center text-xs font-black text-indigo-800 uppercase tracking-wider bg-indigo-50 border-l border-indigo-100">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200 text-gray-900">
                                        @foreach($students as $student)
                                        <tr class="hover:bg-indigo-50 transition-colors group">
                                            <td class="px-4 py-3 whitespace-nowrap sticky left-0 bg-white group-hover:bg-indigo-50 z-10 border-r border-gray-200 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                                                <div class="text-sm font-bold text-gray-900">{{ $student->name }}</div>
                                                <div class="text-xs text-gray-500 font-mono">{{ $student->nim ?? '-' }}</div>
                                            </td>
                                            @php 
                                                $studentTotal = 0;
                                                $studentRanking = collect($ranking)->firstWhere('student_id', $student->id);
                                                if($studentRanking) { $studentTotal = $studentRanking['total_score']; }
                                            @endphp
                                            @foreach($criteria as $criterion)
                                                @php
                                                    $score = $scores->where('user_id', $student->id)->where('criterion_id', $criterion->id)->first();
                                                    $nilai = $score ? $score->skor : 0;
                                                @endphp
                                                <td class="px-4 py-3 text-center whitespace-nowrap border-r border-gray-100">
                                                    @if($score)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold {{ $nilai >= 80 ? 'bg-green-100 text-green-800' : ($nilai >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">{{ number_format($nilai, 0) }}</span>
                                                    @else
                                                        <span class="text-gray-400 text-xs">-</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td class="px-4 py-3 text-center whitespace-nowrap bg-indigo-50/30 group-hover:bg-indigo-100 font-black text-indigo-900 border-l-2 border-indigo-100">{{ number_format($studentTotal, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @else
                                    <div class="p-12 text-center text-gray-500">
                                        <i class="fa-solid fa-user-slash text-4xl mb-3 text-gray-300"></i>
                                        <p>Tidak ada data mahasiswa di kategori ini.</p>
                                    </div>
                                @endif
                            </div>
                            {{-- Pagination --}}
                            <div class="mt-6">{{ $students->appends(request()->query())->links() }}</div>
                        </div>
                    </div>
                </div>

                {{-- Daftar Ranking Top 10 --}}
                <div class="lg:col-span-1 bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden h-fit">
                    <div class="px-6 py-4 bg-gray-100 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-trophy text-yellow-500"></i> Top 10 Global
                        </h3>
                        <span class="bg-white text-gray-600 text-[10px] font-bold px-2 py-1 rounded border border-gray-300 uppercase tracking-wide">SAW Method</span>
                    </div>
                    <div class="p-4 space-y-3 max-h-[800px] overflow-y-auto custom-scrollbar">
                        @if(count($ranking) > 0)
                            @foreach(collect($ranking)->take(10) as $rank)
                                <div class="flex items-center p-3 rounded-lg border transition-all hover:shadow-md bg-white {{ $rank['rank'] == 1 ? 'border-yellow-300 shadow-sm ring-1 ring-yellow-100' : ($rank['rank'] == 2 ? 'border-gray-300' : ($rank['rank'] == 3 ? 'border-orange-300' : 'border-gray-100')) }}">
                                    <div class="w-10 h-10 flex-shrink-0 flex items-center justify-center rounded-lg mr-3 font-black text-lg shadow-sm {{ $rank['rank'] == 1 ? 'bg-yellow-400 text-white' : ($rank['rank'] == 2 ? 'bg-gray-400 text-white' : ($rank['rank'] == 3 ? 'bg-orange-400 text-white' : 'bg-indigo-100 text-indigo-600')) }}">{{ $rank['rank'] }}</div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-bold text-gray-900 truncate">{{ $rank['student']->name }}</div>
                                        <div class="text-xs text-gray-500 flex items-center gap-1"><i class="fa-solid fa-id-card text-gray-400"></i> {{ $rank['student']->nim ?? '-' }}</div>
                                    </div>
                                    <div class="text-right pl-2">
                                        <div class="text-sm font-black text-indigo-700">{{ number_format($rank['total_score'], 2) }}</div>
                                        <div class="text-[10px] text-gray-400 uppercase font-bold">Poin</div>
                                    </div>
                                </div>
                            @endforeach
                            @if(count($ranking) > 10)
                                <div class="text-center pt-2 pb-1"><p class="text-xs text-gray-500 italic">... dan {{ count($ranking) - 10 }} mahasiswa lainnya</p></div>
                            @endif
                        @else
                            <div class="text-center py-8 text-gray-500">Belum ada ranking.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script konfirmasi hitung ulang --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const recalcButtons = document.querySelectorAll('.recalc-btn');
            recalcButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('.recalc-form');
                    Swal.fire({
                        title: 'Hitung Ulang Ranking?',
                        text: "Proses ini akan memperbarui ranking berdasarkan nilai terbaru.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#f97316',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Hitung!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            showLoading('Menghitung Ranking...', 'Mohon tunggu sebentar');
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
