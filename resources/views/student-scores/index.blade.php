<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Nilai Mahasiswa & Ranking (SAW)') }}
            </h2>
            <div class="flex gap-2">
                @if(auth()->user()->isDosen() || auth()->user()->isKoordinator() || auth()->user()->isAdmin())
                    <!-- Input Score -->
                    <a href="{{ route('student-scores.create') }}" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                        <i class="fas fa-plus mr-2"></i>Input Nilai Mahasiswa
                    </a>
                    
                    <!-- Recalculate Ranking -->
                    <form action="{{ route('student-scores.recalc') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Hitung ulang ranking untuk semua mahasiswa?')"
                                class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                            <i class="fas fa-calculator mr-2"></i>Hitung Ulang Ranking
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Success -->
            @if(session('ok'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg shadow-md">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ session('ok') }}</span>
                    </div>
                </div>
            @endif

            <!-- SAW Method Info -->
            <div class="mb-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-600 p-6 rounded-r-xl shadow-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-graduation-cap text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-lg font-bold text-blue-900 mb-2">
                            Metode SAW (Simple Additive Weighting)
                        </h3>
                        <p class="text-sm text-blue-800 mb-3">
                            Sistem ini menggunakan metode SAW untuk menghitung ranking mahasiswa secara otomatis dan objektif berdasarkan kriteria yang telah ditentukan.
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div class="bg-white bg-opacity-50 p-3 rounded-lg">
                                <div class="font-semibold text-blue-900 mb-1">
                                    <i class="fas fa-check-circle mr-1"></i>Normalisasi Benefit
                                </div>
                                <div class="text-blue-700">r<sub>ij</sub> = x<sub>ij</sub> / max(x<sub>ij</sub>)</div>
                            </div>
                            <div class="bg-white bg-opacity-50 p-3 rounded-lg">
                                <div class="font-semibold text-blue-900 mb-1">
                                    <i class="fas fa-times-circle mr-1"></i>Normalisasi Cost
                                </div>
                                <div class="text-blue-700">r<sub>ij</sub> = min(x<sub>ij</sub>) / x<sub>ij</sub></div>
                            </div>
                            <div class="bg-white bg-opacity-50 p-3 rounded-lg">
                                <div class="font-semibold text-blue-900 mb-1">
                                    <i class="fas fa-calculator mr-1"></i>Nilai Preferensi
                                </div>
                                <div class="text-blue-700">V<sub>i</sub> = Σ(w<sub>j</sub> × r<sub>ij</sub>)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-xl shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Mahasiswa</p>
                            <p class="text-3xl font-bold">{{ $students->count() }}</p>
                        </div>
                        <div class="bg-blue-400 bg-opacity-50 p-3 rounded-full">
                            <i class="fas fa-user-graduate text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 rounded-xl shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Kriteria Mahasiswa</p>
                            <p class="text-3xl font-bold">{{ $criteria->count() }}</p>
                        </div>
                        <div class="bg-green-400 bg-opacity-50 p-3 rounded-full">
                            <i class="fas fa-list-check text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 rounded-xl shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Total Nilai</p>
                            <p class="text-3xl font-bold">{{ $scores->count() }}</p>
                        </div>
                        <div class="bg-purple-400 bg-opacity-50 p-3 rounded-full">
                            <i class="fas fa-star text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-6 rounded-xl shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm font-medium">Rata-rata Skor</p>
                            <p class="text-3xl font-bold">{{ number_format($averageScore, 1) }}</p>
                        </div>
                        <div class="bg-orange-400 bg-opacity-50 p-3 rounded-full">
                            <i class="fas fa-chart-line text-2xl"></i>
                        </div>
                    </div>
                </div>
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
                                                    <i class="fas fa-trophy mr-1"></i>Total SAW
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
                                                                <div class="text-sm text-gray-500">{{ $student->politala_id }}</div>
                                                                @if($student->classRoom)
                                                                    <div class="text-xs text-blue-600">{{ $student->classRoom->name }}</div>
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
                                                                    @elseif($skor >= 70) bg-blue-100 text-blue-800
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
                                                            @if($totalScore >= 80) text-green-600
                                                            @elseif($totalScore >= 70) text-blue-600
                                                            @elseif($totalScore >= 60) text-yellow-600
                                                            @else text-red-600
                                                            @endif">
                                                            {{ number_format($totalScore, 1) }}
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
                                            @else bg-blue-50 border border-blue-200
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
                                                        <span class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 rounded-full text-sm font-bold">
                                                            {{ $rank['rank'] }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="text-sm font-medium text-gray-900 truncate">
                                                        {{ $rank['student']->name }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">{{ $rank['student']->politala_id }}</div>
                                                    @if($rank['group'])
                                                        <div class="text-xs text-blue-600 mt-1">
                                                            <i class="fas fa-users mr-1"></i>{{ $rank['group']->name }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-right ml-3">
                                                <div class="text-lg font-bold 
                                                    @if($rank['total_score'] >= 80) text-green-600
                                                    @elseif($rank['total_score'] >= 70) text-blue-600
                                                    @elseif($rank['total_score'] >= 60) text-yellow-600
                                                    @else text-red-600
                                                    @endif">
                                                    {{ number_format($rank['total_score'], 1) }}
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
                                                            <div class="text-xs text-gray-500">{{ $studentData['student']->politala_id }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="text-right ml-2">
                                                        <div class="text-sm font-bold 
                                                            @if($studentData['total_score'] >= 80) text-green-600
                                                            @elseif($studentData['total_score'] >= 70) text-blue-600
                                                            @elseif($studentData['total_score'] >= 60) text-yellow-600
                                                            @else text-red-600
                                                            @endif">
                                                            {{ number_format($studentData['total_score'], 1) }}
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
</x-app-layout>

