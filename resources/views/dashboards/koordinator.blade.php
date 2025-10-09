<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-white leading-tight">
                    Dashboard Koordinator
                </h2>
                <p class="text-sm text-white">Selamat datang, {{ auth()->user()->name }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('classrooms.index') }}" class="bg-primary-500 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-school mr-2"></i>Kelas
                </a>
                <a href="{{ route('groups.index') }}" class="bg-secondary-500 hover:bg-secondary-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-users mr-2"></i>Kelompok
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total Class Rooms -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-primary-100 text-sm font-medium">Total Kelas</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['totalClassRooms'] }}</p>
                        </div>
                        <div class="bg-primary-400 bg-opacity-50 p-3 rounded-full">
                            <i class="fas fa-school text-3xl"></i>
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
                                <span class="font-semibold">{{ $stats['activeGroups'] }}</span> Aktif
                            </p>
                        </div>
                        <div class="bg-secondary-400 bg-opacity-50 p-3 rounded-full">
                            <i class="fas fa-users text-3xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Progress -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Total Progress</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['totalProgress'] }}</p>
                        </div>
                        <div class="bg-green-400 bg-opacity-50 p-3 rounded-full">
                            <i class="fas fa-tasks text-3xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Pending Reviews -->
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm font-medium">Perlu Review</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['pendingReviews'] }}</p>
                        </div>
                        <div class="bg-orange-400 bg-opacity-50 p-3 rounded-full">
                            <i class="fas fa-clock text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Groups Needing Attention -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-yellow-50 px-6 py-4 border-b border-yellow-200">
                        <h3 class="font-semibold text-gray-800">
                            <i class="fas fa-exclamation-triangle mr-2 text-yellow-600"></i>
                            Kelompok Perlu Perhatian
                        </h3>
                        <p class="text-xs text-gray-600 mt-1">Kelompok dengan anggota kurang dari 3 orang</p>
                    </div>
                    <div class="p-6">
                        @if($groupsNeedingAttention->count() > 0)
                            <div class="space-y-3">
                                @foreach($groupsNeedingAttention as $group)
                                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                    <div class="flex items-center">
                                        <div class="bg-yellow-100 p-2 rounded-full mr-3">
                                            <i class="fas fa-users text-yellow-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $group->name }}</p>
                                            <p class="text-xs text-gray-600">
                                                @if($group->classRoom)
                                                {{ $group->classRoom->name }} •
                                                @endif
                                                <span class="text-yellow-600 font-semibold">
                                                    {{ $group->members_count ?? 0 }} anggota
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    <a href="{{ route('groups.edit', $group) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white text-xs px-3 py-1 rounded">
                                        Kelola
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-check-circle text-green-500 text-4xl mb-3"></i>
                                <p class="text-gray-600">Semua kelompok sudah memiliki anggota lengkap!</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Progress Submissions -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-800">
                            <i class="fas fa-file-alt mr-2 text-primary-600"></i>
                            Progress Terbaru
                        </h3>
                        <p class="text-xs text-gray-600 mt-1">Progres yang baru disubmit</p>
                    </div>
                    <div class="p-6">
                        @if($recentProgress->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentProgress as $progress)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    <div class="flex items-center flex-1">
                                        <div class="bg-primary-100 p-2 rounded-full mr-3">
                                            <i class="fas fa-file text-primary-600 text-sm"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-800 truncate">{{ $progress->title }}</p>
                                            <p class="text-xs text-gray-600">
                                                @if($progress->group)
                                                {{ $progress->group->name }} • 
                                                @endif
                                                Minggu {{ $progress->week_number }}
                                            </p>
                                        </div>
                                    </div>
                                    <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        @if($progress->status === 'submitted') bg-orange-100 text-orange-800
                                        @elseif($progress->status === 'reviewed') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($progress->status) }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">Belum ada progress yang disubmit</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <a href="{{ route('groups.index') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center">
                        <div class="bg-secondary-100 p-3 rounded-full mr-4">
                            <i class="fas fa-users text-secondary-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Kelola Kelompok</h3>
                            <p class="text-sm text-gray-600">Tambah/hapus anggota</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('scores.index') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center">
                        <div class="bg-primary-100 p-3 rounded-full mr-4">
                            <i class="fas fa-trophy text-primary-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Lihat Ranking</h3>
                            <p class="text-sm text-gray-600">Monitor peringkat</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('classrooms.index') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-full mr-4">
                            <i class="fas fa-school text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Lihat Kelas</h3>
                            <p class="text-sm text-gray-600">Monitor semua kelas</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>


