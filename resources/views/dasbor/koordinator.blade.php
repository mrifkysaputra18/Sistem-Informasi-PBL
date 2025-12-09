<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">DASHBOARD KOORDINATOR</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">Selamat datang kembali, <span class="font-bold text-gray-700">{{ auth()->user()->name }}</span> 👋</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('classrooms.index') }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 border-2 border-indigo-800 rounded-lg font-bold text-white text-sm shadow-lg transition-all">
                        <i class="fa-solid fa-school mr-2"></i>
                        <span>Kelas</span>
                    </a>
                    <a href="{{ route('groups.index') }}" 
                       class="inline-flex items-center px-5 py-2.5 rounded-lg font-bold text-white text-sm shadow-lg transition-all hover:opacity-90"
                       style="background-color: #059669; border: 2px solid #047857;">
                        <i class="fa-solid fa-users mr-2"></i>
                        <span>Kelompok</span>
                    </a>
                </div>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="bg-emerald-100 border-l-8 border-emerald-600 text-emerald-800 px-6 py-4 rounded-lg shadow-md mb-8 flex items-start justify-between">
                    <div class="flex items-center">
                        <i class="fa-solid fa-check-circle text-2xl mr-4 text-emerald-600"></i>
                        <span class="font-bold text-lg">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-600 hover:text-emerald-800"><i class="fa-solid fa-times text-xl"></i></button>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <!-- Total Kelas -->
                <div class="bg-white rounded-xl p-5 border-2 border-gray-200 shadow-sm hover:border-blue-900 transition-all group">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-black text-black uppercase tracking-widest">TOTAL KELAS</span>
                        <div class="w-8 h-8 rounded-lg bg-gray-50 text-black border border-black flex items-center justify-center group-hover:bg-blue-900 group-hover:text-white group-hover:border-blue-900 transition-all">
                            <i class="fa-solid fa-school text-sm"></i>
                        </div>
                    </div>
                    <p class="text-4xl font-black text-black">{{ $stats['totalClassRooms'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Kelas aktif saat ini</p>
                </div>

                <!-- Total Kelompok -->
                <div class="bg-white rounded-xl p-5 border-2 border-gray-200 shadow-sm hover:border-blue-900 transition-all group">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-black text-black uppercase tracking-widest">TOTAL KELOMPOK</span>
                        <div class="w-8 h-8 rounded-lg bg-gray-50 text-black border border-black flex items-center justify-center group-hover:bg-blue-900 group-hover:text-white group-hover:border-blue-900 transition-all">
                            <i class="fa-solid fa-users text-sm"></i>
                        </div>
                    </div>
                    <p class="text-4xl font-black text-black">{{ $stats['totalGroups'] }}</p>
                    <p class="text-xs text-gray-500 mt-1"><span class="font-bold">{{ $stats['activeGroups'] }}</span> kelompok aktif</p>
                </div>

                <!-- Total Progress -->
                <div class="bg-white rounded-xl p-5 border-2 border-gray-200 shadow-sm hover:border-blue-900 transition-all group">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-black text-black uppercase tracking-widest">TOTAL PROGRESS</span>
                        <div class="w-8 h-8 rounded-lg bg-gray-50 text-black border border-black flex items-center justify-center group-hover:bg-blue-900 group-hover:text-white group-hover:border-blue-900 transition-all">
                            <i class="fa-solid fa-tasks text-sm"></i>
                        </div>
                    </div>
                    <p class="text-4xl font-black text-black">{{ $stats['totalProgress'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Progress terdaftar</p>
                </div>

                <!-- Perlu Review -->
                <div class="bg-white rounded-xl p-5 border-2 border-gray-200 shadow-sm hover:border-blue-900 transition-all group">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-black text-black uppercase tracking-widest">PERLU REVIEW</span>
                        <div class="w-8 h-8 rounded-lg bg-gray-50 text-black border border-black flex items-center justify-center group-hover:bg-blue-900 group-hover:text-white group-hover:border-blue-900 transition-all">
                            <i class="fa-solid fa-clock text-sm"></i>
                        </div>
                    </div>
                    <p class="text-4xl font-black text-black">{{ $stats['pendingReviews'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Menunggu persetujuan</p>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                
                <!-- Kelompok Perlu Perhatian -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="font-black text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-exclamation-triangle text-amber-500"></i>
                            Kelompok Perlu Perhatian
                        </h3>
                        <p class="text-xs text-gray-500 mt-1">Kelompok dengan anggota kurang dari 3 orang</p>
                    </div>
                    <div class="p-6 max-h-80 overflow-y-auto">
                        @if($groupsNeedingAttention->count() > 0)
                            <div class="space-y-3">
                                @foreach($groupsNeedingAttention as $group)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:border-gray-400 transition-all">
                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                        <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                                            <i class="fa-solid fa-users"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-gray-900 truncate">{{ $group->name }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5">
                                                @if($group->classRoom)
                                                {{ $group->classRoom->name }} •
                                                @endif
                                                <span class="text-amber-600 font-bold">{{ $group->members_count ?? 0 }} anggota</span>
                                            </p>
                                        </div>
                                    </div>
                                    <a href="{{ route('groups.edit', $group) }}" 
                                       class="ml-3 px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white text-xs font-bold rounded-lg transition-all">
                                        <i class="fa-solid fa-pen mr-1"></i> Kelola
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="w-16 h-16 mx-auto bg-emerald-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fa-solid fa-check-circle text-3xl text-emerald-600"></i>
                                </div>
                                <p class="font-bold text-gray-900 mb-1">Semua Kelompok Lengkap!</p>
                                <p class="text-sm text-gray-500">Semua kelompok sudah memiliki anggota lengkap</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Progress Terbaru -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="font-black text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-file-alt text-indigo-500"></i>
                            Progress Terbaru
                        </h3>
                        <p class="text-xs text-gray-500 mt-1">Progres yang baru disubmit</p>
                    </div>
                    <div class="p-6 max-h-80 overflow-y-auto">
                        @if($recentProgress->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentProgress as $progress)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:border-gray-400 transition-all">
                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                        <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                            <i class="fa-solid fa-file-lines"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-gray-900 truncate">{{ $progress->title }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5">
                                                @if($progress->group)
                                                {{ $progress->group->name }} •
                                                @endif
                                                Minggu {{ $progress->week_number }}
                                            </p>
                                        </div>
                                    </div>
                                    <span class="ml-3 px-3 py-1.5 rounded-lg text-xs font-bold
                                        @if($progress->status === 'submitted') bg-amber-100 text-amber-800 border border-amber-200
                                        @elseif($progress->status === 'reviewed') bg-emerald-100 text-emerald-800 border border-emerald-200
                                        @else bg-gray-100 text-gray-800 border border-gray-200
                                        @endif">
                                        {{ ucfirst($progress->status) }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fa-solid fa-file-circle-xmark text-3xl text-gray-400"></i>
                                </div>
                                <p class="font-bold text-gray-900 mb-1">Belum Ada Progress</p>
                                <p class="text-sm text-gray-500">Belum ada progress yang disubmit</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div>
                <h3 class="text-lg font-black text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-indigo-600"></i>
                    Aksi Cepat
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Kelola Kelompok -->
                    <a href="{{ route('groups.index') }}" 
                       class="bg-white rounded-xl p-5 border-2 border-gray-200 shadow-sm hover:border-blue-900 hover:shadow-lg transition-all group flex items-center gap-4">
                        <div class="w-12 h-12 rounded-lg bg-gray-100 text-gray-700 border border-gray-300 flex items-center justify-center group-hover:bg-blue-900 group-hover:text-white group-hover:border-blue-900 transition-all">
                            <i class="fa-solid fa-users text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-900 group-hover:text-blue-900">Kelola Kelompok</h4>
                            <p class="text-xs text-gray-500">Tambah/hapus anggota</p>
                        </div>
                        <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-blue-900 transition-all"></i>
                    </a>

                    <!-- Lihat Ranking -->
                    <a href="{{ route('scores.index') }}" 
                       class="bg-white rounded-xl p-5 border-2 border-gray-200 shadow-sm hover:border-blue-900 hover:shadow-lg transition-all group flex items-center gap-4">
                        <div class="w-12 h-12 rounded-lg bg-gray-100 text-gray-700 border border-gray-300 flex items-center justify-center group-hover:bg-blue-900 group-hover:text-white group-hover:border-blue-900 transition-all">
                            <i class="fa-solid fa-ranking-star text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-900 group-hover:text-blue-900">Lihat Ranking</h4>
                            <p class="text-xs text-gray-500">Monitor peringkat</p>
                        </div>
                        <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-blue-900 transition-all"></i>
                    </a>

                    <!-- Lihat Kelas -->
                    <a href="{{ route('classrooms.index') }}" 
                       class="bg-white rounded-xl p-5 border-2 border-gray-200 shadow-sm hover:border-blue-900 hover:shadow-lg transition-all group flex items-center gap-4">
                        <div class="w-12 h-12 rounded-lg bg-gray-100 text-gray-700 border border-gray-300 flex items-center justify-center group-hover:bg-blue-900 group-hover:text-white group-hover:border-blue-900 transition-all">
                            <i class="fa-solid fa-school text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-900 group-hover:text-blue-900">Lihat Kelas</h4>
                            <p class="text-xs text-gray-500">Monitor semua kelas</p>
                        </div>
                        <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-blue-900 transition-all"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
