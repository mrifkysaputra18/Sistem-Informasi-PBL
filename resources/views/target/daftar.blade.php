<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- 1. HEADER SECTION -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">TARGET MINGGUAN</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">Monitoring dan kelola target mingguan kelompok.</p>
                </div>
                <!-- Action Buttons - Hanya untuk admin dan dosen (koordinator hanya monitoring) -->
                @if(in_array(auth()->user()->role, ['dosen', 'admin']))
                <div class="flex flex-wrap gap-3">
                    {{-- Export Laporan --}}
                    <a href="{{ route('targets.export-pdf', ['class_room_id' => request('class_room_id'), 'week_number' => request('week_number')]) }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-green-600 hover:bg-green-700 border-2 border-green-800 rounded-lg font-bold text-white text-sm shadow-lg transform hover:-translate-y-1 transition-all">
                        <i class="fa-solid fa-file-excel mr-2 text-lg"></i>
                        <span>Export Laporan</span>
                    </a>
                    
                    {{-- Sync Kriteria --}}
                    <a href="{{ route('sync-kriteria.index') }}" 
                       style="background-color: #800000 !important; border-color: #600000 !important;"
                       class="inline-flex items-center px-5 py-2.5 bg-red-900 hover:bg-red-800 border-2 border-red-950 rounded-lg font-bold text-white text-sm shadow-lg transform hover:-translate-y-1 transition-all">
                        <i class="fa-solid fa-arrows-rotate mr-2 text-lg"></i>
                        <span>Sync Kriteria</span>
                    </a>
                    
                    {{-- Buat Target --}}
                    <a href="{{ route('targets.create') }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 border-2 border-indigo-800 rounded-lg font-bold text-white text-sm shadow-lg transform hover:-translate-y-1 transition-all">
                        <i class="fa-solid fa-plus mr-2 text-lg"></i>
                        <span>Buat Target</span>
                    </a>
                </div>
                @endif
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

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" class="bg-rose-100 border-l-8 border-rose-600 text-rose-800 px-6 py-4 rounded-lg shadow-md mb-8 flex items-start justify-between">
                    <div class="flex items-center">
                        <i class="fa-solid fa-exclamation-triangle text-2xl mr-4 text-rose-600"></i>
                        <span class="font-bold text-lg">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-rose-600 hover:text-rose-800"><i class="fa-solid fa-times text-xl"></i></button>
                </div>
            @endif

            <!-- 2. STATS CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                <!-- Total Target - Purple -->
                <div class="bg-white rounded-xl p-5 shadow-sm" style="border: 1px solid #e2e8f0; border-bottom: 4px solid #7c3aed;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">TOTAL TARGET</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #ede9fe;">
                            <i class="fa-solid fa-list-check text-lg" style="color: #7c3aed;"></i>
                        </div>
                    </div>
                </div>

                <!-- Sudah Submit - Blue -->
                <div class="bg-white rounded-xl p-5 shadow-sm" style="border: 1px solid #e2e8f0; border-bottom: 4px solid #2563eb;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">SUDAH SUBMIT</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['submitted'] }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #dbeafe;">
                            <i class="fa-solid fa-paper-plane text-lg" style="color: #2563eb;"></i>
                        </div>
                    </div>
                </div>

                <!-- Disetujui - Green -->
                <div class="bg-white rounded-xl p-5 shadow-sm" style="border: 1px solid #e2e8f0; border-bottom: 4px solid #10b981;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">DISETUJUI</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['approved'] }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #d1fae5;">
                            <i class="fa-solid fa-check-double text-lg" style="color: #10b981;"></i>
                        </div>
                    </div>
                </div>

                <!-- Perlu Revisi - Orange -->
                <div class="bg-white rounded-xl p-5 shadow-sm" style="border: 1px solid #e2e8f0; border-bottom: 4px solid #f97316;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">PERLU REVISI</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['revision'] }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #ffedd5;">
                            <i class="fa-solid fa-rotate-right text-lg" style="color: #f97316;"></i>
                        </div>
                    </div>
                </div>

                <!-- Pending/Telat - Red -->
                <div class="bg-white rounded-xl p-5 shadow-sm" style="border: 1px solid #e2e8f0; border-bottom: 4px solid #ef4444;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">PENDING/TELAT</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['pending'] + $stats['late'] }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #fee2e2;">
                            <i class="fa-solid fa-clock text-lg" style="color: #ef4444;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-8">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-bold text-gray-800 flex items-center">
                        <i class="fa-solid fa-chart-line mr-2 text-indigo-600"></i> Progress Keseluruhan
                    </h3>
                    <span class="text-2xl font-black text-indigo-600">{{ $stats['submitted_percentage'] }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                    <div class="bg-indigo-600 h-4 rounded-full transition-all duration-1000 ease-out" 
                         style="width: {{ $stats['submitted_percentage'] }}%"></div>
                </div>
            </div>

            <!-- 3. FILTER CONTROL -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-indigo-600"></div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fa-solid fa-filter mr-2 text-indigo-600"></i> Filter Data
                </h3>
                
                <form method="GET" action="{{ route('targets.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <!-- Class Filter -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Kelas</label>
                        <select name="class_room_id" class="w-full h-10 bg-gray-50 border-2 border-gray-200 rounded-lg text-sm font-semibold focus:border-indigo-600 focus:ring-0 cursor-pointer">
                            <option value="">Semua Kelas</option>
                            @foreach($classRooms as $classRoom)
                                <option value="{{ $classRoom->id }}" {{ request('class_room_id') == $classRoom->id ? 'selected' : '' }}>
                                    {{ $classRoom->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Week Filter -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Minggu</label>
                        <input type="number" name="week_number" min="1" placeholder="Semua Minggu"
                               value="{{ request('week_number') }}"
                               class="w-full h-10 bg-gray-50 border-2 border-gray-200 rounded-lg text-sm font-semibold focus:border-indigo-600 focus:ring-0">
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Status</label>
                        <select name="status" class="w-full h-10 bg-gray-50 border-2 border-gray-200 rounded-lg text-sm font-semibold focus:border-indigo-600 focus:ring-0 cursor-pointer">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Belum Dikerjakan</option>
                            <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Sudah Submit</option>
                            <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Terlambat</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                            <option value="revision" {{ request('status') == 'revision' ? 'selected' : '' }}>Perlu Revisi</option>
                        </select>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 h-10 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-md transition-all flex items-center justify-center text-sm">
                            Terapkan
                        </button>
                    </div>
                    <!-- Hidden input untuk preserve lock_status -->
                    <input type="hidden" name="lock_status" value="{{ request('lock_status', 'aktif') }}">
                </form>
            </div>

            <!-- TAB NAVIGATION -->
            @php
                $currentLockStatus = request('lock_status', 'aktif');
                $preserveParams = request()->except(['lock_status']);
            @endphp
            <div class="bg-white rounded-xl shadow-md border border-gray-200 mb-8">
                <div class="flex border-b border-gray-200">
                    <!-- Tab Target Aktif -->
                    <a href="{{ route('targets.index', array_merge($preserveParams, ['lock_status' => 'aktif'])) }}" 
                       class="flex-1 flex items-center justify-center gap-3 px-6 py-4 text-sm font-bold transition-all relative
                              {{ $currentLockStatus === 'aktif' 
                                 ? 'text-indigo-600 border-b-2 border-indigo-600 bg-indigo-50' 
                                 : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                        <i class="fa-solid fa-unlock text-lg"></i>
                        <span>Target Aktif</span>
                        <span class="px-2.5 py-1 rounded-full text-xs font-black 
                                     {{ $currentLockStatus === 'aktif' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                            {{ $stats['open'] ?? 0 }}
                        </span>
                    </a>
                    
                    <!-- Tab Target Terkunci -->
                    <a href="{{ route('targets.index', array_merge($preserveParams, ['lock_status' => 'terkunci'])) }}" 
                       class="flex-1 flex items-center justify-center gap-3 px-6 py-4 text-sm font-bold transition-all relative
                              {{ $currentLockStatus === 'terkunci' 
                                 ? 'text-gray-700 border-b-2 border-gray-700 bg-gray-100' 
                                 : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                        <i class="fa-solid fa-lock text-lg"></i>
                        <span>Target Terkunci</span>
                        <span class="px-2.5 py-1 rounded-full text-xs font-black 
                                     {{ $currentLockStatus === 'terkunci' ? 'bg-gray-700 text-white' : 'bg-gray-200 text-gray-700' }}">
                            {{ $stats['closed'] ?? 0 }}
                        </span>
                    </a>
                </div>
            </div>

            <!-- 4. TARGET LIST (Cards with Info Button) -->
            <div class="space-y-4">
                @if($targetsByWeek->count() > 0)
                    @foreach($targetsByWeek as $weekIndex => $week)
                    @php
                        $submittedCount = $week['stats']['submitted'] + $week['stats']['approved'] + $week['stats']['revision'];
                        $totalCount = $week['stats']['total'];
                        $progressPercent = $totalCount > 0 ? round(($submittedCount / $totalCount) * 100) : 0;
                        $isPastDeadline = \Carbon\Carbon::parse($week['deadline'])->isPast();
                        $firstTarget = $week['targets']->first();
                        $classRoomId = $firstTarget->group->class_room_id ?? null;
                        $reviewedCount = $week['targets']->where('is_reviewed', true)->count();
                    @endphp

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-300">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-6">
                            <!-- Left: Week Info -->
                            <div class="flex items-center gap-4">
                                <div class="h-14 w-14 rounded-lg bg-indigo-600 text-white flex items-center justify-center text-xl font-black shadow-md">
                                    {{ $week['week_number'] }}
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">{{ $week['title'] }}</h3>
                                    <p class="text-sm text-gray-500 flex items-center gap-2 mt-1">
                                        <i class="fa-regular fa-calendar"></i> 
                                        Deadline: {{ \Carbon\Carbon::parse($week['deadline'])->format('d M Y, H:i') }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        <i class="fa-solid fa-building mr-1"></i> {{ $firstTarget->group->classRoom->name ?? '-' }}
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Right: Stats & Info Button -->
                            <div class="flex items-center gap-6 mt-4 sm:mt-0">
                                <!-- Stats Pills -->
                                <div class="flex items-center gap-3">
                                    <div class="text-center px-3 py-1 bg-gray-100 rounded-lg">
                                        <div class="text-xs text-gray-500">Submit</div>
                                        <div class="text-sm font-bold text-gray-800">{{ $submittedCount }}/{{ $totalCount }}</div>
                                    </div>
                                    <div class="text-center px-3 py-1 bg-green-100 rounded-lg">
                                        <div class="text-xs text-green-600">Reviewed</div>
                                        <div class="text-sm font-bold text-green-700">{{ $reviewedCount }}</div>
                                    </div>
                                </div>
                                
                                <!-- Info Button -->
                                @if($classRoomId)
                                <a href="{{ route('targets.week.info', [$week['week_number'], $classRoomId]) }}" 
                                   class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg transition-all shadow-md hover:shadow-lg">
                                    <i class="fa-solid fa-circle-info mr-2"></i> Info
                                </a>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="h-1.5 bg-gray-200">
                            <div class="h-full bg-indigo-600 transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="py-24 text-center bg-white rounded-xl border border-gray-200">
                        <div class="inline-block p-6 rounded-full bg-gray-100 mb-4 border border-gray-200">
                            <i class="fa-solid fa-clipboard-list text-5xl text-gray-300"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Belum Ada Target</h3>
                        <p class="text-gray-500 mt-2">Silakan buat target mingguan baru untuk memulai.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.querySelectorAll('.delete-week-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const week = this.dataset.week;
                const form = this.closest('.delete-week-form');
                
                Swal.fire({
                    title: 'Hapus Target Minggu Ini?',
                    text: `Anda akan menghapus SEMUA target di Minggu ${week}. Data yang dihapus tidak bisa dikembalikan!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus Semua!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    </script>
    @endpush
</x-app-layout>