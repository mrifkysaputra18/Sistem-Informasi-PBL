<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('student-scores.index') }}" 
               class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Ranking Mahasiswa (SAW Method)') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- SAW Method Info -->
            <div class="mb-6 bg-gradient-to-r from-blue-50 to-primary-50 border-l-4 border-primary-600 p-6 rounded-r-xl shadow-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="bg-primary-100 p-3 rounded-full">
                            <i class="fas fa-graduation-cap text-primary-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-lg font-bold text-primary-900 mb-2">
                            Metode SAW (Simple Additive Weighting)
                        </h3>
                        <p class="text-sm text-primary-800">
                            Ranking dihitung menggunakan metode SAW dengan normalisasi otomatis berdasarkan tipe kriteria.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Ranking Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @if($ranking && count($ranking) > 0)
                    @foreach($ranking as $rank)
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg 
                            @if($rank['rank'] == 1) border-4 border-yellow-400
                            @elseif($rank['rank'] == 2) border-4 border-gray-400
                            @elseif($rank['rank'] == 3) border-4 border-orange-400
                            @else border border-gray-200
                            @endif">
                            <div class="p-6 
                                @if($rank['rank'] == 1) bg-gradient-to-r from-yellow-50 to-yellow-100
                                @elseif($rank['rank'] == 2) bg-gradient-to-r from-gray-50 to-gray-100
                                @elseif($rank['rank'] == 3) bg-gradient-to-r from-orange-50 to-orange-100
                                @else bg-white
                                @endif">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start flex-1">
                                        <!-- Rank Badge -->
                                        <div class="flex-shrink-0 mr-4">
                                            @if($rank['rank'] == 1)
                                                <div class="bg-yellow-500 text-white w-16 h-16 rounded-full flex items-center justify-center shadow-lg">
                                                    <i class="fas fa-crown text-2xl"></i>
                                                </div>
                                            @elseif($rank['rank'] == 2)
                                                <div class="bg-gray-500 text-white w-16 h-16 rounded-full flex items-center justify-center shadow-lg">
                                                    <i class="fas fa-medal text-2xl"></i>
                                                </div>
                                            @elseif($rank['rank'] == 3)
                                                <div class="bg-orange-500 text-white w-16 h-16 rounded-full flex items-center justify-center shadow-lg">
                                                    <i class="fas fa-medal text-2xl"></i>
                                                </div>
                                            @else
                                                <div class="bg-primary-500 text-white w-16 h-16 rounded-full flex items-center justify-center shadow-lg">
                                                    <span class="text-2xl font-bold">{{ $rank['rank'] }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Student Info -->
                                        <div class="flex-1">
                                            <div class="flex items-start justify-between">
                                                <div>
                                                    <h3 class="text-lg font-bold text-gray-900">
                                                        {{ $rank['student']->name }}
                                                    </h3>
                                                    <p class="text-sm text-gray-600">
                                                        <i class="fas fa-id-card mr-1"></i>{{ $rank['student']->politala_id }}
                                                    </p>
                                                    @if($rank['student']->nim)
                                                    <p class="text-sm text-blue-600 font-medium">
                                                        <i class="fas fa-graduation-cap mr-1"></i>NIM: {{ $rank['student']->nim }}
                                                    </p>
                                                    @endif
                                                </div>
                                                <div class="text-right">
                                                    <div class="text-3xl font-bold 
                                                        @if($rank['total_score'] >= 80) text-green-600
                                                        @elseif($rank['total_score'] >= 70) text-primary-600
                                                        @elseif($rank['total_score'] >= 60) text-yellow-600
                                                        @else text-red-600
                                                        @endif">
                                                        {{ number_format($rank['total_score'], 2) }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 uppercase">Skor SAW</div>
                                                </div>
                                            </div>

                                            <div class="mt-3 pt-3 border-t border-gray-200">
                                                <div class="grid grid-cols-2 gap-4">
                                                    <!-- Group Info -->
                                                    @if($rank['group'])
                                                        <div>
                                                            <div class="text-xs text-gray-500 mb-1">
                                                                <i class="fas fa-users mr-1"></i>Kelompok
                                                            </div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ $rank['group']->name }}
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <!-- Class Info -->
                                                    @if($rank['student']->classRoom)
                                                        <div>
                                                            <div class="text-xs text-gray-500 mb-1">
                                                                <i class="fas fa-school mr-1"></i>Kelas
                                                            </div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ $rank['student']->classRoom->name }}
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <!-- Program Studi -->
                                                    @if($rank['student']->program_studi)
                                                        <div>
                                                            <div class="text-xs text-gray-500 mb-1">
                                                                <i class="fas fa-graduation-cap mr-1"></i>Program Studi
                                                            </div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ $rank['student']->program_studi }}
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <!-- Email -->
                                                    <div>
                                                        <div class="text-xs text-gray-500 mb-1">
                                                            <i class="fas fa-envelope mr-1"></i>Email
                                                        </div>
                                                        <div class="text-sm font-medium text-gray-900 truncate">
                                                            {{ $rank['student']->email }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-span-2 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-12 text-center">
                            <div class="text-gray-400 mb-4">
                                <i class="fas fa-trophy text-6xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada ranking tersedia</h3>
                            <p class="text-gray-500">Tambahkan nilai mahasiswa terlebih dahulu untuk melihat ranking.</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Back Button -->
            <div class="mt-6 text-center">
                <a href="{{ route('student-scores.index') }}" 
                   class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Manajemen Nilai
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

