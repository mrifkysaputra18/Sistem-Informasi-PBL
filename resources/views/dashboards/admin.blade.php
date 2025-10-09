<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-white leading-tight">
                    Dashboard Admin
                </h2>
                <p class="text-sm text-white">Selamat datang, {{ auth()->user()->name }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('academic-periods.index') }}" class="bg-primary-500 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-calendar-alt mr-2"></i>Periode Akademik
                </a>
                <a href="{{ route('scores.index') }}" class="bg-secondary-500 hover:bg-secondary-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-trophy mr-2"></i>Nilai & Ranking
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total Users -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-primary-100 text-sm font-medium">Total Users</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['totalUsers'] }}</p>
                            <p class="text-primary-100 text-xs mt-2">
                                <span class="font-semibold">{{ $stats['totalMahasiswa'] }}</span> Mahasiswa • 
                                <span class="font-semibold">{{ $stats['totalDosen'] }}</span> Dosen
                            </p>
                        </div>
                        <div class="bg-primary-400 bg-opacity-50 p-3 rounded-full">
                            <i class="fas fa-users text-3xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Subjects -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Mata Kuliah</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['totalSubjects'] }}</p>
                            <p class="text-green-100 text-xs mt-2">
                                <span class="font-semibold">{{ $stats['totalClassRooms'] }}</span> Kelas Aktif
                            </p>
                        </div>
                        <div class="bg-green-400 bg-opacity-50 p-3 rounded-full">
                            <i class="fas fa-book text-3xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Groups -->
                <div class="bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-secondary-100 text-sm font-medium">Total Kelompok</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['totalGroups'] }}</p>
                            <p class="text-secondary-100 text-xs mt-2">
                                <span class="font-semibold">{{ $stats['totalCriteria'] }}</span> Kriteria Penilaian
                            </p>
                        </div>
                        <div class="bg-secondary-400 bg-opacity-50 p-3 rounded-full">
                            <i class="fas fa-user-friends text-3xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Scores -->
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm font-medium">Input Nilai</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['totalScores'] }}</p>
                            <p class="text-orange-100 text-xs mt-2">
                                Total penilaian tersimpan
                            </p>
                        </div>
                        <div class="bg-orange-400 bg-opacity-50 p-3 rounded-full">
                            <i class="fas fa-star text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <a href="{{ route('academic-periods.create') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center">
                        <div class="bg-primary-100 p-3 rounded-full mr-4">
                            <i class="fas fa-plus text-primary-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Tambah Periode Akademik</h3>
                            <p class="text-sm text-gray-600">Buat periode akademik baru</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('classrooms.create') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-full mr-4">
                            <i class="fas fa-plus text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Tambah Kelas</h3>
                            <p class="text-sm text-gray-600">Buat kelas baru</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('groups.create') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center">
                        <div class="bg-secondary-100 p-3 rounded-full mr-4">
                            <i class="fas fa-plus text-secondary-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Tambah Kelompok</h3>
                            <p class="text-sm text-gray-600">Buat kelompok baru</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Recent Groups -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-800">
                            <i class="fas fa-user-friends mr-2 text-secondary-600"></i>
                            Kelompok Terbaru
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($recentGroups->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentGroups as $group)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    <div class="flex items-center">
                                        <div class="bg-secondary-100 p-2 rounded-full mr-3">
                                            <i class="fas fa-users text-secondary-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $group->name }}</p>
                                            <p class="text-xs text-gray-600">
                                                @if($group->classRoom)
                                                {{ $group->classRoom->name }}
                                                @endif
                                                @if($group->leader)
                                                • Ketua: {{ $group->leader->name }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <a href="{{ route('groups.show', $group) }}" class="text-primary-600 hover:text-primary-800">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">Belum ada kelompok</p>
                        @endif
                        <div class="mt-4 text-center">
                            <a href="{{ route('groups.index') }}" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                Lihat Semua Kelompok →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Users -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-800">
                            <i class="fas fa-user-plus mr-2 text-primary-600"></i>
                            User Terbaru
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($recentUsers->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentUsers as $user)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    <div class="flex items-center">
                                        <div class="bg-primary-100 p-2 rounded-full mr-3">
                                            <i class="fas fa-user text-primary-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-600">
                                                {{ $user->email }} • 
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                    @if($user->role === 'admin') bg-red-100 text-red-800
                                                    @elseif($user->role === 'koordinator') bg-secondary-100 text-secondary-800
                                                    @elseif($user->role === 'dosen') bg-primary-100 text-primary-800
                                                    @else bg-green-100 text-green-800
                                                    @endif">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">Belum ada user</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


