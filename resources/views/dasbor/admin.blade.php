<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="py-6 bg-gray-200 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Users -->
                <div class="bg-white rounded-xl shadow-md border-l-4 border-blue-500 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Total Users</p>
                            <p class="text-3xl font-bold text-blue-600">{{ $stats['totalUsers'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $stats['totalMahasiswa'] }} Mahasiswa • {{ $stats['totalDosen'] }} Dosen</p>
                        </div>
                        <div class="bg-blue-500 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Kelas -->
                <div class="bg-white rounded-xl shadow-md border-l-4 border-green-500 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Ruang Kelas</p>
                            <p class="text-3xl font-bold text-green-600">{{ $stats['totalClassRooms'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">Kelas aktif</p>
                        </div>
                        <div class="bg-green-500 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Kelompok -->
                <div class="bg-white rounded-xl shadow-md border-l-4 border-purple-500 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Total Kelompok</p>
                            <p class="text-3xl font-bold text-purple-600">{{ $stats['totalGroups'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $stats['totalCriteria'] }} Kriteria</p>
                        </div>
                        <div class="bg-purple-500 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Nilai -->
                <div class="bg-white rounded-xl shadow-md border-l-4 border-orange-500 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Input Nilai</p>
                            <p class="text-3xl font-bold text-orange-500">{{ $stats['totalScores'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">Total penilaian</p>
                        </div>
                        <div class="bg-orange-500 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-md p-5">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi Cepat</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <a href="{{ route('academic-periods.create') }}" 
                       class="flex items-center gap-4 p-4 bg-blue-50 border border-blue-200 hover:bg-blue-100 rounded-xl transition-colors">
                        <div class="bg-blue-500 p-3 rounded-xl shadow flex items-center justify-center">
                            <svg class="w-6 h-6" fill="white" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">Periode Akademik</p>
                            <p class="text-sm text-gray-600">Tambah periode baru</p>
                        </div>
                    </a>

                    <a href="{{ route('classrooms.create') }}" 
                       class="flex items-center gap-4 p-4 bg-green-50 border border-green-200 hover:bg-green-100 rounded-xl transition-colors">
                        <div class="bg-green-500 p-3 rounded-xl shadow flex items-center justify-center">
                            <svg class="w-6 h-6" fill="white" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">Kelas Baru</p>
                            <p class="text-sm text-gray-600">Tambah kelas baru</p>
                        </div>
                    </a>

                    <a href="{{ route('groups.create') }}" 
                       class="flex items-center gap-4 p-4 bg-purple-50 border border-purple-200 hover:bg-purple-100 rounded-xl transition-colors">
                        <div class="p-3 rounded-xl shadow flex items-center justify-center" style="background-color: #9333ea;">
                            <svg class="w-6 h-6" viewBox="0 0 20 20">
                                <path fill="#ffffff" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">Kelompok Baru</p>
                            <p class="text-sm text-gray-600">Tambah kelompok baru</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                <!-- Kelompok Terbaru -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-[#003366] px-5 py-4">
                        <h3 class="font-semibold text-white text-lg">Kelompok Terbaru</h3>
                    </div>
                    <div class="p-5">
                        @if($recentGroups->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentGroups as $group)
                                <a href="{{ route('groups.show', $group) }}" 
                                   class="flex items-center justify-between p-4 bg-gray-50 hover:bg-blue-50 border border-gray-200 hover:border-blue-300 rounded-xl transition-all">
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $group->name }}</p>
                                        <p class="text-sm text-gray-600">
                                            @if($group->classRoom) {{ $group->classRoom->name }} @endif
                                            @if($group->leader) • {{ $group->leader->name }} @endif
                                        </p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                                @endforeach
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <a href="{{ route('groups.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-bold">
                                    Lihat Semua →
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-7 h-7 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                    </svg>
                                </div>
                                <p class="text-gray-500 font-medium">Belum ada kelompok</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- User Terbaru -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-[#003366] px-5 py-4">
                        <h3 class="font-semibold text-white text-lg">User Terbaru</h3>
                    </div>
                    <div class="p-5">
                        @if($recentUsers->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentUsers as $user)
                                <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100 transition-colors">
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                    </div>
                                    <span class="px-3 py-1 text-xs font-bold rounded-full
                                        @if($user->role === 'admin') bg-red-500 text-white
                                        @elseif($user->role === 'koordinator') bg-purple-500 text-white
                                        @elseif($user->role === 'dosen') bg-blue-500 text-white
                                        @else bg-green-500 text-white @endif">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <a href="{{ route('admin.users.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-bold">
                                    Lihat Semua →
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-7 h-7 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <p class="text-gray-500 font-medium">Belum ada user</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
