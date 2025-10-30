<x-app-layout>
    <x-slot name="header">
        <!-- Law of Proximity & Visual Hierarchy -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="space-y-1">
                <h2 class="font-bold text-2xl text-white leading-tight flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                    </svg>
                    @if(auth()->user()->isKoordinator())
                        Ranking Mahasiswa
                    @else
                        Ranking Mahasiswa
                    @endif
                </h2>
                <p class="text-sm text-white/90">Monitoring dan ranking mahasiswa berdasarkan kriteria penilaian</p>
            </div>
            
            <!-- Fitts's Law: Larger, accessible action buttons -->
            <div class="flex flex-wrap gap-2">
                @if(auth()->user()->isDosen() || auth()->user()->isAdmin())
                    <a href="{{ route('student-scores.create') }}" 
                       class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105 inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i>Input Nilai Mahasiswa
                    </a>
                    <form action="{{ route('student-scores.recalc') }}" method="POST" class="inline recalc-form">
                        @csrf
                        <button type="button" 
                                class="recalc-btn bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                            <i class="fas fa-calculator mr-2"></i>Hitung Ulang Ranking
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <!-- Law of Symmetry: Balanced spacing -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Feedback Visibility - Success Message -->
            @if(session('ok'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl animate-slide-in shadow-md">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-green-800 font-medium">{{ session('ok') }}</p>
                </div>
            </div>
            @endif



            <!-- Statistics Cards - Von Restorff Effect & Miller's Law (4 items) -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Statistik Nilai Mahasiswa
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    <!-- Card 1: Total Mahasiswa -->
                    <div class="group relative bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg hover:shadow-2xl p-6 text-white transition-all duration-300 hover:scale-105 cursor-pointer overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="relative flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-blue-100 text-xs font-medium uppercase tracking-wider">Total Mahasiswa</p>
                                <p class="text-4xl font-black mt-3 mb-1 group-hover:scale-110 transition-transform duration-300">{{ $students->count() }}</p>
                                <p class="text-xs text-blue-100 mt-2">Mahasiswa terdaftar</p>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl group-hover:rotate-12 transition-transform duration-300">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Kriteria Mahasiswa -->
                    <div class="group relative bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-lg hover:shadow-2xl p-6 text-white transition-all duration-300 hover:scale-105 cursor-pointer overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="relative flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-green-100 text-xs font-medium uppercase tracking-wider">Kriteria Mahasiswa</p>
                                <p class="text-4xl font-black mt-3 mb-1 group-hover:scale-110 transition-transform duration-300">{{ $criteria->count() }}</p>
                                <p class="text-xs text-green-100 mt-2">Kriteria penilaian</p>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl group-hover:rotate-12 transition-transform duration-300">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Total Nilai -->
                    <div class="group relative bg-gradient-to-br from-red-500 to-red-600 rounded-2xl shadow-lg hover:shadow-2xl p-6 text-white transition-all duration-300 hover:scale-105 cursor-pointer overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="relative flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-red-100 text-xs font-medium uppercase tracking-wider">Total Nilai</p>
                                <p class="text-4xl font-black mt-3 mb-1 group-hover:scale-110 transition-transform duration-300">{{ $scores->count() }}</p>
                                <p class="text-xs text-red-100 mt-2">Nilai tersimpan</p>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl group-hover:rotate-12 transition-transform duration-300">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4: Rata-rata Skor -->
                    <div class="group relative bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl shadow-lg hover:shadow-2xl p-6 text-white transition-all duration-300 hover:scale-105 cursor-pointer overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="relative flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-orange-100 text-xs font-medium uppercase tracking-wider">Rata-rata Skor</p>
                                <p class="text-4xl font-black mt-3 mb-1 group-hover:scale-110 transition-transform duration-300">{{ number_format($averageScore, 1) }}</p>
                                <p class="text-xs text-orange-100 mt-2">Skor keseluruhan</p>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl group-hover:rotate-12 transition-transform duration-300">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filter Ranking
                </h3>
                <form method="GET" action="{{ route('student-scores.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Angkatan (Tahun Ajaran)</label>
                        <select name="academic_year" id="academic_year" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Semua Angkatan</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                        <select name="class_room_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Semua Kelas</option>
                            @foreach($classRooms as $classRoom)
                                <option value="{{ $classRoom->id }}" {{ request('class_room_id') == $classRoom->id ? 'selected' : '' }}>
                                    {{ $classRoom->name }} @if($classRoom->academicPeriod) ({{ $classRoom->academicPeriod->academic_year }})@endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 bg-primary-500 hover:bg-primary-600 text-white font-semibold py-2 px-6 rounded-md transition duration-200">
                            <i class="fa-solid fa-filter mr-2"></i>Terapkan Filter
                        </button>
                        @if(request()->hasAny(['academic_year', 'class_room_id']))
                            <a href="{{ route('student-scores.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-md transition duration-200">
                                <i class="fa-solid fa-times mr-1"></i>Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Score Matrix -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">
                                    <i class="fas fa-table mr-2 text-gray-600"></i>Matriks Nilai Mahasiswa
                                </h3>
                                <div class="text-sm text-gray-600">
                                    {{ $students->count() }} mahasiswa × {{ $criteria->count() }} kriteria
                                </div>
                            </div>

                            @if($students->count() > 0 && $criteria->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50">
                                                    Mahasiswa
                                                </th>
                                                @foreach($criteria as $criterion)
                                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        <div class="flex flex-col items-center">
                                                            <span>{{ Str::limit($criterion->nama, 15) }}</span>
                                                            <span class="text-xs text-gray-400">({{ number_format($criterion->bobot * 100, 0) }}%)</span>
                                                            <span class="text-xs {{ $criterion->tipe == 'benefit' ? 'text-green-600' : 'text-red-600' }}">
                                                                {{ ucfirst($criterion->tipe) }}
                                                            </span>
                                                        </div>
                                                    </th>
                                                @endforeach
                                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    <i class="fas fa-trophy mr-1"></i>Total
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($students as $student)
                                                <tr class="hover:bg-gray-50 transition duration-200">
                                                    <td class="px-4 py-4 whitespace-nowrap sticky left-0 bg-white">
                                                        <div class="flex items-center">
                                                            <div>
                                                                <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                                                @if($student->nim)
                                                                    <div class="text-sm text-blue-600 font-medium">NIM: {{ $student->nim }}</div>
                                                                @endif
                                                                @if($student->classRoom)
                                                                    <div class="text-xs text-primary-600">{{ $student->classRoom->name }}</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @foreach($criteria as $criterion)
                                                        @php
                                                            $score = $scores->where('user_id', $student->id)->where('criterion_id', $criterion->id)->first();
                                                            $skor = $score ? $score->skor : null;
                                                        @endphp
                                                        <td class="px-4 py-4 whitespace-nowrap text-center">
                                                            @if($skor !== null)
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                                    @if($skor >= 80) bg-green-100 text-green-800
                                                                    @elseif($skor >= 70) bg-primary-100 text-primary-800
                                                                    @elseif($skor >= 60) bg-yellow-100 text-yellow-800
                                                                    @else bg-red-100 text-red-800
                                                                    @endif">
                                                                    {{ number_format($skor, 1) }}
                                                                </span>
                                                            @else
                                                                <span class="text-gray-400 text-sm">-</span>
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                    <td class="px-4 py-4 whitespace-nowrap text-center">
                                                        @php
                                                            $studentRanking = collect($ranking)->firstWhere('student_id', $student->id);
                                                            $totalScore = $studentRanking ? $studentRanking['total_score'] : 0;
                                                        @endphp
                                                        <span class="text-lg font-bold 
                                                            @if($totalScore >= 0.8) text-green-600
                                                            @elseif($totalScore >= 0.7) text-primary-600
                                                            @elseif($totalScore >= 0.6) text-yellow-600
                                                            @else text-red-600
                                                            @endif">
                                                            {{ number_format($totalScore, 4) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div class="text-gray-400 mb-4">
                                        <i class="fas fa-table text-6xl"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data</h3>
                                    <p class="text-gray-500 mb-4">Tambahkan mahasiswa dan kriteria terlebih dahulu.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Ranking -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                <i class="fas fa-trophy mr-2 text-yellow-500"></i>Ranking Mahasiswa
                            </h3>

                            @if($ranking && count($ranking) > 0)
                                <div class="space-y-3 max-h-[600px] overflow-y-auto">
                                    @foreach($ranking as $rank)
                                        <div class="flex items-center justify-between p-3 rounded-lg 
                                            @if($rank['rank'] == 1) bg-yellow-50 border border-yellow-200
                                            @elseif($rank['rank'] == 2) bg-gray-50 border border-gray-200
                                            @elseif($rank['rank'] == 3) bg-orange-50 border border-orange-200
                                            @else bg-primary-50 border border-primary-200
                                            @endif">
                                            <div class="flex items-center flex-1">
                                                <div class="flex-shrink-0 mr-3">
                                                    @if($rank['rank'] == 1)
                                                        <i class="fas fa-crown text-yellow-500 text-xl"></i>
                                                    @elseif($rank['rank'] == 2)
                                                        <i class="fas fa-medal text-gray-500 text-xl"></i>
                                                    @elseif($rank['rank'] == 3)
                                                        <i class="fas fa-medal text-orange-500 text-xl"></i>
                                                    @else
                                                        <span class="flex items-center justify-center w-8 h-8 bg-primary-100 text-primary-600 rounded-full text-sm font-bold">
                                                            {{ $rank['rank'] }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="text-sm font-medium text-gray-900 truncate">
                                                        {{ $rank['student']->name }}
                                                    </div>
                                                    @if($rank['student']->nim)
                                                        <div class="text-xs text-blue-600 font-medium">NIM: {{ $rank['student']->nim }}</div>
                                                    @endif
                                                    @if($rank['group'])
                                                        <div class="text-xs text-primary-600 mt-1">
                                                            <i class="fas fa-users mr-1"></i>{{ $rank['group']->name }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-right ml-3">
                                                <div class="text-lg font-bold 
                                                    @if($rank['total_score'] >= 0.8) text-green-600
                                                    @elseif($rank['total_score'] >= 0.7) text-primary-600
                                                    @elseif($rank['total_score'] >= 0.6) text-yellow-600
                                                    @else text-red-600
                                                    @endif">
                                                    {{ number_format($rank['total_score'], 4) }}
                                                </div>
                                                <div class="text-xs text-gray-500">poin</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="text-gray-400 mb-4">
                                        <i class="fas fa-trophy text-4xl"></i>
                                    </div>
                                    <p class="text-gray-500 text-sm">Belum ada ranking tersedia</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Best Students Per Class -->
            @if(count($bestStudentsPerClass) > 0)
                <div class="mt-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                <i class="fas fa-star mr-2 text-yellow-500"></i>Mahasiswa Terbaik Per Kelas
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($bestStudentsPerClass as $classData)
                                    <div class="border border-gray-200 rounded-lg p-4 bg-gradient-to-br from-white to-gray-50">
                                        <div class="flex items-center justify-between mb-3 pb-3 border-b border-gray-200">
                                            <h4 class="font-semibold text-gray-800">{{ $classData['class_room']->name }}</h4>
                                            <span class="text-sm text-gray-500">{{ $classData['class_room']->code }}</span>
                                        </div>
                                        
                                        <div class="space-y-2">
                                            @foreach($classData['top_students'] as $index => $studentData)
                                                <div class="flex items-center justify-between p-2 rounded-lg 
                                                    @if($index == 0) bg-yellow-50 border border-yellow-200
                                                    @elseif($index == 1) bg-gray-50 border border-gray-200
                                                    @elseif($index == 2) bg-orange-50 border border-orange-200
                                                    @endif">
                                                    <div class="flex items-center flex-1 min-w-0">
                                                        <div class="flex-shrink-0 mr-2">
                                                            @if($index == 0)
                                                                <i class="fas fa-crown text-yellow-500"></i>
                                                            @elseif($index == 1)
                                                                <i class="fas fa-medal text-gray-500"></i>
                                                            @elseif($index == 2)
                                                                <i class="fas fa-medal text-orange-500"></i>
                                                            @endif
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="text-sm font-medium text-gray-900 truncate">
                                                                {{ $studentData['student']->name }}
                                                            </div>
                                                            @if($studentData['student']->nim)
                                                            <div class="text-xs text-blue-600 font-medium">NIM: {{ $studentData['student']->nim }}</div>
                                                            @endif
                                                            @if($studentData['student']->nim)
                                                                <div class="text-xs text-blue-600 font-medium">NIM: {{ $studentData['student']->nim }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="text-right ml-2">
                                                        <div class="text-sm font-bold 
                                                            @if($studentData['total_score'] >= 0.8) text-green-600
                                                            @elseif($studentData['total_score'] >= 0.7) text-primary-600
                                                            @elseif($studentData['total_score'] >= 0.6) text-yellow-600
                                                            @else text-red-600
                                                            @endif">
                                                            {{ number_format($studentData['total_score'], 4) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle recalculate button clicks
            const recalcButtons = document.querySelectorAll('.recalc-btn');
            
            recalcButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const form = this.closest('.recalc-form');
                    
                    confirmAction(
                        'Hitung Ulang Ranking?',
                        'Apakah Anda yakin ingin menghitung ulang ranking untuk semua mahasiswa?<br><small class="text-gray-500">Proses ini akan memperbarui ranking berdasarkan nilai terbaru.</small>',
                        '<i class="fas fa-calculator mr-2"></i>Ya, Hitung Ulang!',
                        '#f97316'
                    ).then((result) => {
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

