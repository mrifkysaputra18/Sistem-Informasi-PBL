<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="space-y-1">
                <h2 class="font-bold text-2xl text-white leading-tight flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    {{ __('Ranking Kelompok') }}
                </h2>
                <p class="text-sm text-white/90">Monitoring dan ranking kelompok berdasarkan kriteria penilaian</p>
            </div>
            <div class="flex gap-2">
                @if(auth()->user()->isAdmin())
                    <!-- Admin Only: Recalculate Ranking -->
                    <form action="{{ route('scores.recalc') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Hitung ulang ranking untuk semua kelompok?')"
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

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-xl shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-primary-100 text-sm font-medium">Total Kelompok</p>
                            <p class="text-3xl font-bold">{{ $groups->count() }}</p>
                        </div>
                        <div class="bg-primary-400 bg-opacity-50 p-3 rounded-full">
                            <i class="fas fa-users text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 rounded-xl shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Total Kriteria</p>
                            <p class="text-3xl font-bold">{{ $criteria->count() }}</p>
                        </div>
                        <div class="bg-green-400 bg-opacity-50 p-3 rounded-full">
                            <i class="fas fa-list-check text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-secondary-500 to-secondary-600 p-6 rounded-xl shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-secondary-100 text-sm font-medium">Total Nilai</p>
                            <p class="text-3xl font-bold">{{ $scores->count() }}</p>
                        </div>
                        <div class="bg-secondary-400 bg-opacity-50 p-3 rounded-full">
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

            <!-- Best Students & Groups - Progressive Disclosure with Tabs -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 mb-6">
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-6 py-4 border-b border-indigo-200">
                    <h3 class="font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        Top Performers
                    </h3>
                    <p class="text-xs text-gray-600 mt-1">Mahasiswa & kelompok terbaik per kelas</p>
                </div>
                
                <!-- Tabs Navigation - Hick's Law: Simple choice -->
                <div class="flex border-b border-gray-200 bg-gray-50">
                    <button onclick="showTab('students')" id="tab-students" class="tab-button active flex-1 px-6 py-3 text-sm font-semibold transition-all duration-200 border-b-2">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                            </svg>
                            <span>Mahasiswa Terbaik</span>
                        </div>
                    </button>
                    <button onclick="showTab('groups')" id="tab-groups" class="tab-button flex-1 px-6 py-3 text-sm font-semibold transition-all duration-200 border-b-2">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                            </svg>
                            <span>Kelompok Terbaik</span>
                        </div>
                    </button>
                </div>
                
                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Students Tab Content -->
                    <div id="content-students" class="tab-content">
                        @if(count($bestStudentsPerClass) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($bestStudentsPerClass as $classData)
                                    <div class="border border-gray-200 rounded-xl p-4 bg-gradient-to-br from-white to-blue-50 hover:shadow-md transition-shadow">
                                        <div class="flex items-center justify-between mb-3 pb-3 border-b border-gray-200">
                                            <h4 class="font-semibold text-gray-900">{{ $classData['class_room']->name }}</h4>
                                            <span class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded-full font-semibold">{{ $classData['class_room']->code }}</span>
                                        </div>
                                        
                                        <div class="space-y-2">
                                            @foreach($classData['top_students'] as $index => $studentData)
                                                <div class="flex items-center justify-between p-3 rounded-lg 
                                                    @if($index == 0) bg-yellow-50 border border-yellow-200
                                                    @elseif($index == 1) bg-gray-50 border border-gray-200
                                                    @elseif($index == 2) bg-orange-50 border border-orange-200
                                                    @else bg-blue-50 border border-blue-100
                                                    @endif">
                                                    <div class="flex items-center flex-1 min-w-0 gap-2">
                                                        <div class="flex-shrink-0">
                                                            @if($index == 0)
                                                                <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                                </svg>
                                                            @elseif($index == 1)
                                                                <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                                </svg>
                                                            @elseif($index == 2)
                                                                <svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                                </svg>
                                                            @else
                                                                <span class="flex items-center justify-center w-5 h-5 bg-blue-100 text-blue-600 rounded-full text-xs font-bold">
                                                                    {{ $index + 1 }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="text-sm font-medium text-gray-900 truncate">{{ $studentData['student']->name }}</div>
                                                            <div class="text-xs text-gray-500">{{ $studentData['student']->politala_id }}</div>
                                                            <div class="text-xs text-primary-600 flex items-center gap-1 mt-0.5">
                                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                                                </svg>
                                                                {{ $studentData['group']->name }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-right ml-2">
                                                        <div class="text-sm font-bold 
                                                            @if($studentData['final_score'] >= 80) text-green-600
                                                            @elseif($studentData['final_score'] >= 70) text-blue-600
                                                            @elseif($studentData['final_score'] >= 60) text-yellow-600
                                                            @else text-red-600
                                                            @endif">
                                                            {{ number_format($studentData['final_score'], 1) }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">poin</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                    </svg>
                                </div>
                                <p class="text-gray-500 text-sm">Belum ada data mahasiswa tersedia</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Groups Tab Content -->
                    <div id="content-groups" class="tab-content hidden">
                        @if(count($bestGroupsPerClass) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($bestGroupsPerClass as $classData)
                                    <div class="border border-gray-200 rounded-xl p-4 bg-gradient-to-br from-white to-green-50 hover:shadow-md transition-shadow">
                                        <div class="flex items-center justify-between mb-3 pb-3 border-b border-gray-200">
                                            <h4 class="font-semibold text-gray-900">{{ $classData['class_room']->name }}</h4>
                                            <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full font-semibold">{{ $classData['class_room']->code }}</span>
                                        </div>
                                        
                                        <div class="space-y-2">
                                            @foreach($classData['top_groups'] as $index => $groupData)
                                                <div class="flex items-center justify-between p-3 rounded-lg 
                                                    @if($index == 0) bg-yellow-50 border border-yellow-200
                                                    @elseif($index == 1) bg-gray-50 border border-gray-200
                                                    @elseif($index == 2) bg-orange-50 border border-orange-200
                                                    @else bg-green-50 border border-green-100
                                                    @endif">
                                                    <div class="flex items-center flex-1 min-w-0 gap-2">
                                                        <div class="flex-shrink-0">
                                                            @if($index == 0)
                                                                <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                                </svg>
                                                            @elseif($index == 1)
                                                                <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                                </svg>
                                                            @elseif($index == 2)
                                                                <svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                                </svg>
                                                            @else
                                                                <span class="flex items-center justify-center w-5 h-5 bg-green-100 text-green-600 rounded-full text-xs font-bold">
                                                                    {{ $index + 1 }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="text-sm font-medium text-gray-900 truncate">{{ $groupData['group']->name }}</div>
                                                            @if($groupData['group']->leader)
                                                            <div class="text-xs text-gray-500 flex items-center gap-1">
                                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                                </svg>
                                                                {{ $groupData['group']->leader->name }}
                                                            </div>
                                                            @endif
                                                            @if(isset($groupData['completion_rate']))
                                                            <div class="text-xs text-primary-600 flex items-center gap-1 mt-0.5">
                                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                                                </svg>
                                                                {{ round($groupData['completion_rate'], 1) }}% progres
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="text-right ml-2">
                                                        <div class="text-sm font-bold 
                                                            @if($groupData['total_score'] >= 80) text-green-600
                                                            @elseif($groupData['total_score'] >= 70) text-blue-600
                                                            @elseif($groupData['total_score'] >= 60) text-yellow-600
                                                            @else text-red-600
                                                            @endif">
                                                            {{ number_format($groupData['total_score'], 1) }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">poin</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                    </svg>
                                </div>
                                <p class="text-gray-500 text-sm">Belum ada data kelompok tersedia</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Score Matrix & Ranking -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Score Matrix -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">
                                    <i class="fas fa-table mr-2 text-gray-600"></i>Matriks Nilai
                                </h3>
                                <div class="text-sm text-gray-600">
                                    {{ $groups->count() }} kelompok × {{ $criteria->count() }} kriteria
                                </div>
                            </div>

                            @if($groups->count() > 0 && $criteria->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50">
                                                    Kelompok
                                                </th>
                                                @foreach($criteria as $criterion)
                                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        <div class="flex flex-col items-center">
                                                            <span>{{ Str::limit($criterion->nama, 15) }}</span>
                                                            <span class="text-xs text-gray-400">({{ $criterion->bobot }})</span>
                                                        </div>
                                                    </th>
                                                @endforeach
                                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    <i class="fas fa-trophy mr-1"></i>Total
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($groups as $group)
                                                <tr class="hover:bg-gray-50 transition duration-200">
                                                    <td class="px-4 py-4 whitespace-nowrap sticky left-0 bg-white">
                                                        <div class="flex items-center">
                                                            <div class="text-sm font-medium text-gray-900">{{ $group->name }}</div>
                                                        </div>
                                                        @if($group->classRoom)
                                                        <div class="text-sm text-gray-500">{{ Str::limit($group->classRoom->name, 20) }}</div>
                                                        @endif
                                                    </td>
                                                    @foreach($criteria as $criterion)
                                                        @php
                                                            $score = $scores->where('group_id', $group->id)->where('criterion_id', $criterion->id)->first();
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
                                                            $groupScores = $scores->where('group_id', $group->id);
                                                            $totalScore = 0;
                                                            foreach($groupScores as $gs) {
                                                                $criterion = $criteria->find($gs->criterion_id);
                                                                if($criterion) {
                                                                    $totalScore += $gs->skor * $criterion->bobot;
                                                                }
                                                            }
                                                        @endphp
                                                        <span class="text-lg font-bold 
                                                            @if($totalScore >= 80) text-green-600
                                                            @elseif($totalScore >= 70) text-primary-600
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
                                    <p class="text-gray-500 mb-4">Tambahkan kelompok dan kriteria terlebih dahulu.</p>
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
                                <i class="fas fa-trophy mr-2 text-yellow-500"></i>Ranking Kelompok
                            </h3>

                            @if($ranking && count($ranking) > 0)
                                <div class="space-y-3">
                                    @foreach($ranking as $rank)
                                        <div class="flex items-center justify-between p-3 rounded-lg 
                                            @if($rank['rank'] == 1) bg-yellow-50 border border-yellow-200
                                            @elseif($rank['rank'] == 2) bg-gray-50 border border-gray-200
                                            @elseif($rank['rank'] == 3) bg-orange-50 border border-orange-200
                                            @else bg-primary-50 border border-primary-200
                                            @endif">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 mr-3">
                                                    @if($rank['rank'] == 1)
                                                        <i class="fas fa-trophy text-yellow-500 text-xl"></i>
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
                                                <div class="flex-1">
                                                    <div class="text-sm font-medium text-gray-900">{{ $rank['group']->name }}</div>
                                                    @if($rank['group']->classRoom)
                                                        <div class="text-xs text-gray-500">{{ Str::limit($rank['group']->classRoom->name, 15) }}</div>
                                                    @endif
                                                    @if(isset($rank['completion_rate']))
                                                    <div class="text-xs text-primary-600 mt-1">
                                                        <i class="fas fa-tasks mr-1"></i>{{ round($rank['completion_rate'], 1) }}% progres
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-lg font-bold 
                                                    @if($rank['total_score'] >= 80) text-green-600
                                                    @elseif($rank['total_score'] >= 70) text-primary-600
                                                    @elseif($rank['total_score'] >= 60) text-yellow-600
                                                    @else text-red-600
                                                    @endif">
                                                    {{ number_format($rank['total_score'], 1) }}
                                                </div>
                                                <div class="text-xs text-gray-500">SAW</div>
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
        </div>
    </div>

    <!-- Tab Switching Script -->
    <script>
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'text-primary-600', 'border-primary-600', 'bg-white');
                button.classList.add('text-gray-600', 'border-transparent', 'hover:text-gray-900', 'hover:border-gray-300');
            });
            
            // Show selected tab content
            document.getElementById('content-' + tabName).classList.remove('hidden');
            
            // Add active class to selected tab
            const activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.remove('text-gray-600', 'border-transparent', 'hover:text-gray-900', 'hover:border-gray-300');
            activeTab.classList.add('active', 'text-primary-600', 'border-primary-600', 'bg-white');
        }
        
        // Initialize first tab as active
        document.addEventListener('DOMContentLoaded', function() {
            showTab('students');
        });
    </script>

    <!-- Tab Styles -->
    <style>
        .tab-button {
            position: relative;
        }
        
        .tab-button.active {
            border-bottom-width: 2px;
            margin-bottom: -2px;
        }
        
        .tab-content {
            animation: fadeIn 0.3s ease-in;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .tab-button:hover {
            background-color: rgba(255, 255, 255, 0.5);
        }
        
        .tab-button.active:hover {
            background-color: white;
        }
    </style>
</x-app-layout>
