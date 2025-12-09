<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- 1. HEADER SECTION -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">TARGET MINGGUAN</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">Monitoring dan kelola target mingguan kelompok.</p>
                </div>
                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('targets.export-pdf', ['class_room_id' => request('class_room_id'), 'week_number' => request('week_number')]) }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-red-600 hover:bg-red-700 border-2 border-red-800 rounded-lg font-bold text-white text-sm shadow-lg transform hover:-translate-y-1 transition-all">
                        <i class="fa-solid fa-file-pdf mr-2 text-lg"></i>
                        <span>Export PDF</span>
                    </a>
                    
                    @if(in_array(auth()->user()->role, ['dosen', 'admin']))
                    <a href="{{ route('targets.create') }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 border-2 border-indigo-800 rounded-lg font-bold text-white text-sm shadow-lg transform hover:-translate-y-1 transition-all">
                        <i class="fa-solid fa-plus mr-2 text-lg"></i>
                        <span>Buat Target</span>
                    </a>
                    @endif
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
                <!-- Total -->
                <div class="bg-white rounded-xl p-5 border-2 border-gray-200 shadow-sm hover:border-blue-900 transition-all group">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-black text-black uppercase tracking-widest">TOTAL TARGET</span>
                        <div class="w-8 h-8 rounded-lg bg-gray-50 text-black border border-black flex items-center justify-center group-hover:bg-blue-900 group-hover:text-white group-hover:border-blue-900 transition-all">
                            <i class="fa-solid fa-list-check text-sm"></i>
                        </div>
                    </div>
                    <p class="text-4xl font-black text-black">{{ $stats['total'] }}</p>
                </div>

                <!-- Submitted -->
                <div class="bg-white rounded-xl p-5 border-2 border-gray-200 shadow-sm hover:border-blue-900 transition-all group">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-black text-black uppercase tracking-widest">SUDAH SUBMIT</span>
                        <div class="w-8 h-8 rounded-lg bg-gray-50 text-black border border-black flex items-center justify-center group-hover:bg-blue-900 group-hover:text-white group-hover:border-blue-900 transition-all">
                            <i class="fa-solid fa-paper-plane text-sm"></i>
                        </div>
                    </div>
                    <p class="text-4xl font-black text-black">{{ $stats['submitted'] }}</p>
                </div>

                <!-- Approved -->
                <div class="bg-white rounded-xl p-5 border-2 border-gray-200 shadow-sm hover:border-blue-900 transition-all group">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-black text-black uppercase tracking-widest">DISETUJUI</span>
                        <div class="w-8 h-8 rounded-lg bg-gray-50 text-black border border-black flex items-center justify-center group-hover:bg-blue-900 group-hover:text-white group-hover:border-blue-900 transition-all">
                            <i class="fa-solid fa-check-double text-sm"></i>
                        </div>
                    </div>
                    <p class="text-4xl font-black text-black">{{ $stats['approved'] }}</p>
                </div>

                <!-- Revision -->
                <div class="bg-white rounded-xl p-5 border-2 border-gray-200 shadow-sm hover:border-blue-900 transition-all group">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-black text-black uppercase tracking-widest">PERLU REVISI</span>
                        <div class="w-8 h-8 rounded-lg bg-gray-50 text-black border border-black flex items-center justify-center group-hover:bg-blue-900 group-hover:text-white group-hover:border-blue-900 transition-all">
                            <i class="fa-solid fa-rotate-right text-sm"></i>
                        </div>
                    </div>
                    <p class="text-4xl font-black text-black">{{ $stats['revision'] }}</p>
                </div>

                <!-- Pending -->
                <div class="bg-white rounded-xl p-5 border-2 border-gray-200 shadow-sm hover:border-blue-900 transition-all group">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-black text-black uppercase tracking-widest">PENDING/TELAT</span>
                        <div class="w-8 h-8 rounded-lg bg-gray-50 text-black border border-black flex items-center justify-center group-hover:bg-blue-900 group-hover:text-white group-hover:border-blue-900 transition-all">
                            <i class="fa-solid fa-clock text-sm"></i>
                        </div>
                    </div>
                    <p class="text-4xl font-black text-black">{{ $stats['pending'] + $stats['late'] }}</p>
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
                </form>
            </div>

            <!-- 4. TARGET LIST (Accordions) -->
            <div class="space-y-6">
                @if($targetsByWeek->count() > 0)
                    @foreach($targetsByWeek as $weekIndex => $week)
                    @php
                        $submittedCount = $week['stats']['submitted'] + $week['stats']['approved'] + $week['stats']['revision'];
                        $totalCount = $week['stats']['total'];
                        $progressPercent = $totalCount > 0 ? round(($submittedCount / $totalCount) * 100) : 0;
                        $isPastDeadline = \Carbon\Carbon::parse($week['deadline'])->isPast();
                        $firstTarget = $week['targets']->first();
                        $classRoomId = $firstTarget->group->class_room_id ?? null;
                        $adaTargetTerbuka = $week['targets']->contains(fn($t) => $t->is_open);
                        $bisaDitutup = !$isPastDeadline && $adaTargetTerbuka;
                        $bisaDibuka = $isPastDeadline || !$adaTargetTerbuka;
                    @endphp

                    <div x-data="{ open: false }" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-md">
                        <!-- Accordion Header -->
                        <button @click="open = !open" class="w-full flex flex-col sm:flex-row sm:items-center justify-between p-6 bg-white hover:bg-gray-50 transition-colors border-b border-gray-100">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-lg font-black">
                                    {{ $week['week_number'] }}
                                </div>
                                <div class="text-left">
                                    <h3 class="text-lg font-bold text-gray-900">{{ $week['title'] }}</h3>
                                    <p class="text-sm text-gray-500 flex items-center gap-2">
                                        <i class="fa-regular fa-calendar"></i> 
                                        Deadline: {{ \Carbon\Carbon::parse($week['deadline'])->format('d M Y, H:i') }}
                                        @if($isPastDeadline)
                                            <span class="text-rose-600 font-bold text-xs bg-rose-100 px-2 py-0.5 rounded-full">EXPIRED</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-6 mt-4 sm:mt-0">
                                <div class="text-right">
                                    <div class="text-xs font-bold text-gray-400 uppercase">Progress</div>
                                    <div class="text-lg font-black text-gray-800">{{ $submittedCount }}/{{ $totalCount }}</div>
                                </div>
                                <div class="h-10 w-10 rounded-full bg-indigo-50 flex items-center justify-center">
                                    <i class="fa-solid fa-chevron-down text-indigo-600 transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                                </div>
                            </div>
                        </button>

                        <!-- Accordion Content -->
                        <div x-show="open" x-collapse>
                            <!-- Week Actions -->
                            @if(in_array(auth()->user()->role, ['dosen', 'admin']) && $classRoomId)
                            <div class="bg-gray-50 border-b border-gray-200 px-6 py-3 flex flex-wrap items-center justify-end gap-3">
                                <a href="{{ route('targets.week.edit', [$week['week_number'], $classRoomId]) }}" 
                                   class="text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-lg transition-colors shadow-sm border border-indigo-700">
                                    <i class="fa-solid fa-edit mr-1"></i> Edit Minggu
                                </a>
                                
                                @if($bisaDitutup)
                                <form action="{{ route('targets.week.close', [$week['week_number'], $classRoomId]) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs font-bold text-white bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded-lg transition-colors shadow-sm border border-gray-700">
                                        <i class="fa-solid fa-lock mr-1"></i> Tutup Target
                                    </button>
                                </form>
                                @endif
                                
                                @if($bisaDibuka)
                                <form action="{{ route('targets.week.reopen', [$week['week_number'], $classRoomId]) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs font-bold text-white bg-cyan-600 hover:bg-cyan-700 px-4 py-2 rounded-lg transition-colors shadow-sm border border-cyan-700">
                                        <i class="fa-solid fa-unlock mr-1"></i> Buka Target
                                    </button>
                                </form>
                                @endif

                                <form action="{{ route('targets.week.destroy', [$week['week_number'], $classRoomId]) }}" method="POST" class="inline delete-week-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="delete-week-btn text-xs font-bold text-white bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg transition-colors shadow-sm border border-red-700" 
                                            data-week="{{ $week['week_number'] }}">
                                        <i class="fa-solid fa-trash mr-1"></i> Hapus Minggu
                                    </button>
                                </form>
                            </div>
                            @endif

                            <!-- Table -->
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-900 text-white">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-bold uppercase w-48">Kelompok</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold uppercase">Kelas</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold uppercase">Status</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold uppercase">Waktu Submit</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($week['targets'] as $target)
                                        <tr class="hover:bg-indigo-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold text-gray-900">{{ $target->group->name }}</div>
                                                @if($target->completedByUser)
                                                    <div class="text-xs text-indigo-600 mt-1">
                                                        <i class="fa-solid fa-user mr-1"></i> {{ $target->completedByUser->name }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {{ $target->group->classRoom->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusClass = match($target->submission_status) {
                                                        'submitted' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                        'approved' => 'bg-green-100 text-green-800 border-green-200',
                                                        'revision' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                        'late' => 'bg-rose-100 text-rose-800 border-rose-200',
                                                        default => 'bg-gray-100 text-gray-600 border-gray-200',
                                                    };
                                                    $label = match($target->submission_status) {
                                                        'pending' => 'Belum Submit',
                                                        'submitted' => 'Menunggu Review',
                                                        'approved' => 'Disetujui',
                                                        'revision' => 'Perlu Revisi',
                                                        'late' => 'Terlambat',
                                                        default => ucfirst($target->submission_status),
                                                    };
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold border uppercase tracking-wide {{ $statusClass }}">
                                                    {{ $label }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($target->completed_at)
                                                    <div class="text-sm text-gray-900 font-medium">{{ $target->completed_at->format('d/m/Y H:i') }}</div>
                                                    <div class="text-xs text-gray-500">{{ $target->completed_at->diffForHumans() }}</div>
                                                @else
                                                    <span class="text-sm text-gray-400 italic">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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