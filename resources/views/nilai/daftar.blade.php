<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- 1. HEADER SECTION -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">RANKING & PENILAIAN</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">Monitoring kinerja dan perankingan kelompok berdasarkan metode SAW.</p>
                </div>
                <!-- Action Buttons -->
                @if(auth()->user()->isAdmin())
                <div class="flex flex-wrap gap-3">
                    <form action="{{ route('scores.recalc') }}" method="POST" class="inline recalc-form">
                        @csrf
                        <button type="button" 
                                class="recalc-btn inline-flex items-center px-5 py-2.5 bg-orange-500 hover:bg-orange-600 border-2 border-orange-700 rounded-lg font-bold text-white text-sm shadow-lg transform hover:-translate-y-1 transition-all">
                            <i class="fa-solid fa-calculator mr-2 text-lg"></i>
                            <span>Hitung Ulang Ranking</span>
                        </button>
                    </form>
                </div>
                @endif
            </div>

            <!-- Alert Messages -->
            @if(session('ok'))
                <div x-data="{ show: true }" x-show="show" class="bg-emerald-100 border-l-8 border-emerald-600 text-emerald-800 px-6 py-4 rounded-lg shadow-md mb-8 flex items-start justify-between">
                    <div class="flex items-center">
                        <i class="fa-solid fa-check-circle text-2xl mr-4 text-emerald-600"></i>
                        <span class="font-bold text-lg">{{ session('ok') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-600 hover:text-emerald-800"><i class="fa-solid fa-times text-xl"></i></button>
                </div>
            @endif

            <!-- 2. STATS CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Kelompok -->
                <div class="bg-white rounded-xl shadow-md border-b-4 border-indigo-600 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Total Kelompok</p>
                        <p class="text-3xl font-black text-gray-800">{{ $groups->count() }}</p>
                    </div>
                    <div class="p-3 bg-indigo-100 rounded-full text-indigo-600">
                        <i class="fa-solid fa-users text-2xl"></i>
                    </div>
                </div>

                <!-- Total Kriteria -->
                <div class="bg-white rounded-xl shadow-md border-b-4 border-emerald-600 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Total Kriteria</p>
                        <p class="text-3xl font-black text-gray-800">{{ $criteria->count() }}</p>
                    </div>
                    <div class="p-3 bg-emerald-100 rounded-full text-emerald-600">
                        <i class="fa-solid fa-list-check text-2xl"></i>
                    </div>
                </div>

                <!-- Total Nilai -->
                <div class="bg-white rounded-xl shadow-md border-b-4 border-purple-600 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Total Data Nilai</p>
                        <p class="text-3xl font-black text-gray-800">{{ $scores->count() }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-full text-purple-600">
                        <i class="fa-solid fa-star text-2xl"></i>
                    </div>
                </div>

                <!-- Rata-rata Skor -->
                <div class="bg-white rounded-xl shadow-md border-b-4 border-orange-600 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Rata-rata Skor</p>
                        <p class="text-3xl font-black text-gray-800">{{ number_format($averageScore, 1, ',', '.') }}</p>
                    </div>
                    <div class="p-3 bg-orange-100 rounded-full text-orange-600">
                        <i class="fa-solid fa-chart-line text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- 3. FILTER CONTROL -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-indigo-600"></div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fa-solid fa-filter mr-2 text-indigo-600"></i> Filter Ranking
                </h3>
                
                <form method="GET" action="{{ route('scores.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <!-- Angkatan -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Angkatan (Tahun)</label>
                        <select name="academic_year" class="w-full h-10 bg-gray-50 border-2 border-gray-200 rounded-lg text-sm font-semibold focus:border-indigo-600 focus:ring-0 cursor-pointer">
                            <option value="">Semua Angkatan</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kelas -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Kelas</label>
                        <select name="class_room_id" class="w-full h-10 bg-gray-50 border-2 border-gray-200 rounded-lg text-sm font-semibold focus:border-indigo-600 focus:ring-0 cursor-pointer">
                            <option value="">Semua Kelas</option>
                            @foreach($classRooms as $classRoom)
                                <option value="{{ $classRoom->id }}" {{ request('class_room_id') == $classRoom->id ? 'selected' : '' }}>
                                    {{ $classRoom->name }} @if($classRoom->academicPeriod) ({{ $classRoom->academicPeriod->academic_year }})@endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 h-10 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-md transition-all flex items-center justify-center text-sm">
                            Terapkan
                        </button>
                        @if(request()->hasAny(['academic_year', 'class_room_id']))
                            <a href="{{ route('scores.index') }}" class="h-10 w-10 bg-white border-2 border-gray-300 hover:border-red-500 hover:bg-red-50 text-gray-600 hover:text-red-600 rounded-lg transition-all flex items-center justify-center" title="Reset">
                                <i class="fa-solid fa-times text-lg"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- 4. TOP PERFORMERS (Tabs) -->
            <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden mb-8" x-data="{ activeTab: 'students' }">
                <!-- Tab Headers -->
                <div class="flex border-b border-gray-200 bg-gray-50">
                    <button @click="activeTab = 'students'" 
                            :class="activeTab === 'students' ? 'border-indigo-600 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100'"
                            class="flex-1 py-4 text-sm font-bold uppercase tracking-widest transition-all border-b-4">
                        <i class="fa-solid fa-user-graduate mr-2"></i> Mahasiswa Terbaik
                    </button>
                    <button @click="activeTab = 'groups'" 
                            :class="activeTab === 'groups' ? 'border-indigo-600 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100'"
                            class="flex-1 py-4 text-sm font-bold uppercase tracking-widest transition-all border-b-4">
                        <i class="fa-solid fa-users mr-2"></i> Kelompok Terbaik
                    </button>
                </div>

                <!-- Tab Contents -->
                <div class="p-6">
                    <!-- Students Tab -->
                    <div x-show="activeTab === 'students'" x-transition.opacity>
                        @if(count($bestStudentsPerClass) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($bestStudentsPerClass as $classData)
                                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow">
                                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                                            <h4 class="font-bold text-gray-800">{{ $classData['class_room']->name }}</h4>
                                            <span class="text-xs font-mono bg-white border border-gray-300 px-2 py-0.5 rounded text-gray-600">{{ $classData['class_room']->code }}</span>
                                        </div>
                                        <div class="p-2 space-y-2">
                                            @foreach($classData['top_students'] as $index => $studentData)
                                                <div class="flex items-center p-2 rounded-lg {{ $index == 0 ? 'bg-yellow-50 border border-yellow-100' : ($index == 1 ? 'bg-gray-50 border border-gray-100' : ($index == 2 ? 'bg-orange-50 border border-orange-100' : '')) }}">
                                                    <div class="w-8 h-8 flex items-center justify-center rounded-full mr-3 
                                                        {{ $index == 0 ? 'bg-yellow-400 text-white shadow-sm' : ($index == 1 ? 'bg-gray-400 text-white shadow-sm' : ($index == 2 ? 'bg-orange-400 text-white shadow-sm' : 'bg-indigo-100 text-indigo-600 font-bold text-xs')) }}">
                                                        @if($index < 3) <i class="fa-solid fa-trophy text-xs"></i> @else {{ $index + 1 }} @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-bold text-gray-900 truncate">{{ $studentData['student']->name }}</p>
                                                        <p class="text-xs text-gray-500">{{ $studentData['group']->name }}</p>
                                                    </div>
                                                    <div class="text-right pl-2">
                                                        <span class="block text-sm font-black {{ $index == 0 ? 'text-yellow-600' : 'text-indigo-600' }}">
                                                            {{ number_format($studentData['final_score'], 1) }}
                                                        </span>
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

                    <!-- Groups Tab -->
                    <div x-show="activeTab === 'groups'" x-transition.opacity style="display: none;">
                        @if(count($bestGroupsPerClass) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($bestGroupsPerClass as $classData)
                                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow">
                                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                                            <h4 class="font-bold text-gray-800">{{ $classData['class_room']->name }}</h4>
                                            <span class="text-xs font-mono bg-white border border-gray-300 px-2 py-0.5 rounded text-gray-600">{{ $classData['class_room']->code }}</span>
                                        </div>
                                        <div class="p-2 space-y-2">
                                            @foreach($classData['top_groups'] as $index => $groupData)
                                                <div class="flex items-center p-2 rounded-lg {{ $index == 0 ? 'bg-yellow-50 border border-yellow-100' : '' }}">
                                                    <div class="w-8 h-8 flex items-center justify-center rounded-full mr-3 {{ $index == 0 ? 'bg-yellow-400 text-white' : 'bg-gray-200 text-gray-600 font-bold text-xs' }}">
                                                        @if($index == 0) <i class="fa-solid fa-crown text-xs"></i> @else {{ $index + 1 }} @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-bold text-gray-900 truncate">{{ $groupData['group']->name }}</p>
                                                        @if($groupData['group']->leader)
                                                            <p class="text-xs text-gray-500">Ketua: {{ Str::limit($groupData['group']->leader->name, 10) }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="text-right pl-2">
                                                        <span class="block text-sm font-black text-indigo-600">
                                                            {{ number_format($groupData['total_score'], 2) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">Belum ada data kelompok.</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 5. SCORE MATRIX & RANKING -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Score Matrix -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-900 border-b border-gray-800 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white">
                            <i class="fa-solid fa-table mr-2 text-indigo-400"></i> Matriks Penilaian
                        </h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        @if($groups->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10 border-r border-gray-200 shadow-sm">
                                        Kelompok
                                    </th>
                                    @foreach($criteria as $criterion)
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        <div class="flex flex-col items-center">
                                            <span>{{ Str::limit($criterion->nama, 10) }}</span>
                                            <span class="text-[10px] text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded mt-1">{{ $criterion->bobot }}</span>
                                        </div>
                                    </th>
                                    @endforeach
                                    <th class="px-4 py-3 text-center text-xs font-black text-indigo-600 uppercase tracking-wider bg-indigo-50">
                                        Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($groups as $group)
                                <tr class="hover:bg-indigo-50 transition-colors group">
                                    <td class="px-4 py-3 whitespace-nowrap sticky left-0 bg-white group-hover:bg-indigo-50 z-10 border-r border-gray-200">
                                        <div class="text-sm font-bold text-gray-900">{{ $group->name }}</div>
                                        @if($group->classRoom)
                                            <div class="text-xs text-gray-500">{{ $group->classRoom->name }}</div>
                                        @endif
                                    </td>
                                    @php $totalScore = 0; @endphp
                                    @foreach($criteria as $criterion)
                                        @php
                                            $score = $scores->where('group_id', $group->id)->where('criterion_id', $criterion->id)->first();
                                            $nilai = $score ? $score->skor : 0;
                                            $totalScore += $nilai * $criterion->bobot;
                                        @endphp
                                        <td class="px-4 py-3 text-center whitespace-nowrap">
                                            @if($score)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                    {{ $nilai >= 80 ? 'bg-green-100 text-green-800' : ($nilai >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ number_format($nilai, 0) }}
                                                </span>
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="px-4 py-3 text-center whitespace-nowrap bg-indigo-50/50 font-black text-indigo-700">
                                        {{ number_format($totalScore, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            <div class="p-12 text-center text-gray-500">Belum ada data kelompok.</div>
                        @endif
                    </div>
                </div>

                <!-- Global Ranking List -->
                <div class="lg:col-span-1 bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden h-fit">
                    <div class="px-6 py-4 bg-gray-900 border-b border-gray-800">
                        <h3 class="text-lg font-bold text-white">
                            <i class="fa-solid fa-trophy mr-2 text-yellow-400"></i> Ranking Global
                        </h3>
                    </div>
                    <div class="p-4 space-y-3 max-h-[600px] overflow-y-auto custom-scrollbar">
                        @if(count($ranking) > 0)
                            @foreach($ranking as $rank)
                                <div class="flex items-center p-3 rounded-lg border transition-all hover:shadow-md
                                    {{ $rank['rank'] == 1 ? 'bg-yellow-50 border-yellow-200' : ($rank['rank'] == 2 ? 'bg-gray-50 border-gray-200' : ($rank['rank'] == 3 ? 'bg-orange-50 border-orange-200' : 'bg-white border-gray-100')) }}">
                                    
                                    <div class="w-10 h-10 flex-shrink-0 flex items-center justify-center rounded-lg mr-3 font-black text-lg shadow-sm
                                        {{ $rank['rank'] == 1 ? 'bg-yellow-400 text-white' : ($rank['rank'] == 2 ? 'bg-gray-400 text-white' : ($rank['rank'] == 3 ? 'bg-orange-400 text-white' : 'bg-indigo-100 text-indigo-600')) }}">
                                        {{ $rank['rank'] }}
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-bold text-gray-900 truncate">{{ $rank['group']->name }}</div>
                                        <div class="text-xs text-gray-500 flex items-center gap-1">
                                            <i class="fa-solid fa-school"></i> {{ Str::limit($rank['group']->classRoom->name ?? '-', 15) }}
                                        </div>
                                    </div>
                                    
                                    <div class="text-right pl-2">
                                        <div class="text-sm font-black text-indigo-600">{{ number_format($rank['total_score'], 2) }}</div>
                                        <div class="text-[10px] text-gray-400 uppercase font-bold">Total</div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8 text-gray-500">Belum ada ranking.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
    @endpush
</x-app-layout>