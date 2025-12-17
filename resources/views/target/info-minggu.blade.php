<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Back Button & Header -->
            <div class="mb-6">
                <a href="{{ route('targets.index') }}" 
                   class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-4">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Daftar Target
                </a>
                
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                            <span class="h-12 w-12 rounded-lg bg-indigo-600 text-white flex items-center justify-center text-lg font-black">
                                {{ $weekNumber }}
                            </span>
                            {{ $firstTarget->title }}
                        </h2>
                        <p class="text-sm text-gray-500 mt-2">
                            <i class="fa-solid fa-building mr-1"></i> {{ $classRoom->name }}
                            <span class="mx-2">|</span>
                            <i class="fa-regular fa-calendar mr-1"></i> 
                            Deadline: {{ \Carbon\Carbon::parse($firstTarget->deadline)->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="bg-emerald-100 border-l-8 border-emerald-600 text-emerald-800 px-6 py-4 rounded-lg shadow-md mb-6 flex items-start justify-between">
                    <div class="flex items-center">
                        <i class="fa-solid fa-check-circle text-2xl mr-4 text-emerald-600"></i>
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-600 hover:text-emerald-800"><i class="fa-solid fa-times"></i></button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" class="bg-rose-100 border-l-8 border-rose-600 text-rose-800 px-6 py-4 rounded-lg shadow-md mb-6 flex items-start justify-between">
                    <div class="flex items-center">
                        <i class="fa-solid fa-exclamation-triangle text-2xl mr-4 text-rose-600"></i>
                        <span class="font-bold">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-rose-600 hover:text-rose-800"><i class="fa-solid fa-times"></i></button>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                    <div class="text-xs font-bold text-gray-400 uppercase">Total Kelompok</div>
                    <div class="text-2xl font-black text-gray-800">{{ $stats['total'] }}</div>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                    <div class="text-xs font-bold text-gray-400 uppercase">Sudah Submit</div>
                    <div class="text-2xl font-black text-blue-600">{{ $stats['submitted'] + $stats['approved'] }}</div>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                    <div class="text-xs font-bold text-gray-400 uppercase">Sudah Review</div>
                    <div class="text-2xl font-black text-green-600">{{ $stats['reviewed'] }}</div>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                    <div class="text-xs font-bold text-gray-400 uppercase">Belum Submit</div>
                    <div class="text-2xl font-black text-gray-500">{{ $stats['pending'] }}</div>
                </div>
            </div>

            <!-- Action Buttons -->
            @if(in_array(auth()->user()->role, ['dosen', 'admin']))
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('targets.week.edit', [$weekNumber, $classRoom->id]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg transition-colors shadow-sm">
                        <i class="fa-solid fa-edit mr-2"></i> Edit Minggu
                    </a>
                    
                    @if($bisaDitutup)
                    <form action="{{ route('targets.week.close', [$weekNumber, $classRoom->id]) }}" method="POST" class="inline" id="close-target-form">
                        @csrf
                        <button type="button" onclick="confirmAction('close-target-form', 'Tutup Target?', 'Apakah Anda yakin ingin menutup target minggu ini? Mahasiswa tidak akan bisa submit lagi.', 'warning', 'Ya, Tutup!')" 
                                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-bold rounded-lg transition-colors shadow-sm">
                            <i class="fa-solid fa-lock mr-2"></i> Tutup Target
                        </button>
                    </form>
                    @endif
                    
                    @if($bisaDibuka)
                    <form action="{{ route('targets.week.reopen', [$weekNumber, $classRoom->id]) }}" method="POST" class="inline" id="reopen-target-form">
                        @csrf
                        <button type="button" onclick="confirmAction('reopen-target-form', 'Buka Target?', 'Apakah Anda yakin ingin membuka kembali target minggu ini? Mahasiswa bisa submit kembali.', 'question', 'Ya, Buka!')"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-lg transition-colors shadow-sm">
                            <i class="fa-solid fa-unlock mr-2"></i> Buka Target
                        </button>
                    </form>
                    @endif

                    <form action="{{ route('targets.week.destroy', [$weekNumber, $classRoom->id]) }}" method="POST" class="inline delete-week-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="delete-week-btn inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-lg transition-colors shadow-sm" 
                                data-week="{{ $weekNumber }}">
                            <i class="fa-solid fa-trash mr-2"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Groups Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800">
                        <i class="fa-solid fa-users mr-2 text-indigo-600"></i>
                        Daftar Kelompok
                    </h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase text-gray-900">Kelompok</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase text-gray-900">Kelas</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase text-gray-900">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase text-gray-900">Waktu Submit</th>
                                @if(in_array(auth()->user()->role, ['dosen', 'admin']))
                                <th class="px-6 py-4 text-center text-xs font-bold uppercase text-gray-900">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($targets as $target)
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
                                    @if($target->is_reviewed)
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-green-600 text-white">
                                            <i class="fa-solid fa-check mr-1"></i> Reviewed
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($target->completed_at)
                                        <div class="text-sm text-gray-900 font-medium">{{ $target->completed_at->format('d/m/Y H:i') }}</div>
                                        <div class="text-xs text-gray-500">{{ $target->completed_at->diffForHumans() }}</div>
                                    @else
                                        <span class="text-sm text-gray-400 italic">-</span>
                                    @endif
                                </td>
                                @if(in_array(auth()->user()->role, ['dosen', 'admin']))
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($target->is_reviewed)
                                        <a href="{{ route('target-reviews.show', $target) }}" 
                                           class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 hover:bg-green-200 rounded-lg text-xs font-bold transition-colors">
                                            <i class="fa-solid fa-eye mr-1"></i> Lihat Review
                                        </a>
                                    @elseif(in_array($target->submission_status, ['submitted', 'late']))
                                        <a href="{{ route('target-reviews.show', $target) }}" 
                                           class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white hover:bg-indigo-700 rounded-lg text-xs font-bold transition-colors shadow-sm">
                                            <i class="fa-solid fa-clipboard-check mr-1"></i> Review
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Belum submit</span>
                                    @endif
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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

        // Reusable confirm function
        function confirmAction(formId, title, text, icon, confirmText) {
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: icon === 'warning' ? '#d33' : '#10b981', // Red for warning, Green for others
                cancelButtonColor: '#6b7280',
                confirmButtonText: confirmText,
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
