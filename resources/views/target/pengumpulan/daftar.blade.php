<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-white">Target Mingguan</h2>
                <p class="text-sm text-white/80">Daftar target mingguan kelompok Anda</p>
            </div>
            @if($group)
            <div class="bg-white/20 px-4 py-2 rounded-lg">
                <p class="text-xs text-white/70">Kelompok</p>
                <p class="text-sm font-bold text-white">{{ $group->name }}</p>
            </div>
            @endif
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Alert Messages --}}
            @if(session('success'))
            <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg flex items-center gap-3">
                <i class="fas fa-check-circle text-emerald-500 text-xl"></i>
                <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-lg flex items-center gap-3">
                <i class="fas fa-times-circle text-rose-500 text-xl"></i>
                <p class="text-sm font-medium text-rose-800">{{ session('error') }}</p>
            </div>
            @endif

            {{-- Stats Cards - Simple & Clean --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
                {{-- Pending - Orange --}}
                <div class="bg-white rounded-xl p-5 shadow-sm" style="border: 1px solid #e2e8f0; border-bottom: 4px solid #f59e0b;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">PENDING</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $targets->where('submission_status', 'pending')->count() }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #fef3c7;">
                            <i class="fas fa-clock text-xl" style="color: #f59e0b;"></i>
                        </div>
                    </div>
                </div>

                {{-- Disubmit - Blue --}}
                <div class="bg-white rounded-xl p-5 shadow-sm" style="border: 1px solid #e2e8f0; border-bottom: 4px solid #2563eb;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">DISUBMIT</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $targets->whereIn('submission_status', ['submitted', 'late'])->count() }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #dbeafe;">
                            <i class="fas fa-paper-plane text-xl" style="color: #2563eb;"></i>
                        </div>
                    </div>
                </div>

                {{-- Selesai - Green --}}
                <div class="bg-white rounded-xl p-5 shadow-sm" style="border: 1px solid #e2e8f0; border-bottom: 4px solid #10b981;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">SELESAI</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $targets->where('submission_status', 'approved')->count() }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #d1fae5;">
                            <i class="fas fa-check-circle text-xl" style="color: #10b981;"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Progress Section - Clean --}}
            <div class="bg-white rounded-xl p-6 shadow-sm mb-8" style="border: 2px solid #cbd5e1;">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-500 flex items-center justify-center">
                            <i class="fas fa-chart-line text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800">Progress Semester</h3>
                            <p class="text-sm text-slate-500">Pencapaian penyelesaian target kelompok</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-4xl font-bold text-blue-600">{{ $progressPercentage }}</span>
                        <span class="text-xl text-slate-400">%</span>
                    </div>
                </div>
                
                {{-- Progress Bar --}}
                <div class="w-full bg-slate-100 rounded-full h-3">
                    <div class="bg-blue-500 h-3 rounded-full transition-all duration-700" 
                         style="width: {{ $progressPercentage }}%"></div>
                </div>
            </div>

            {{-- Target List --}}
            <div class="bg-white rounded-xl overflow-hidden" style="border: 2px solid #cbd5e1;">
                {{-- Header --}}
                <div class="px-6 py-4 bg-slate-100 flex justify-between items-center" style="border-bottom: 2px solid #94a3b8;">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-list-ul text-slate-400"></i>
                        Daftar Target
                    </h3>
                    <span class="px-3 py-1 bg-slate-100 rounded-full text-xs font-semibold text-slate-600">
                        {{ $targets->count() }} Target
                    </span>
                </div>
                
                @if($targets->count() > 0)
                <div class="divide-y" style="--tw-divide-opacity: 1; --tw-divide-color: #cbd5e1;">
                    @foreach($targets as $target)
                    @php
                        $waktuSekarang = now();
                        $deadline = $target->deadline;
                        $sudahLewat = $deadline && $waktuSekarang->gt($deadline);
                        
                        // Status mapping
                        $statusConfig = match($target->submission_status) {
                            'approved' => ['color' => 'emerald', 'label' => 'Disetujui', 'border' => 'border-l-emerald-500', 'bg' => 'bg-emerald-50/50'],
                            'submitted' => ['color' => 'blue', 'label' => 'Menunggu Review', 'border' => 'border-l-blue-500', 'bg' => 'bg-blue-50/50'],
                            'revision' => ['color' => 'amber', 'label' => 'Perlu Revisi', 'border' => 'border-l-amber-500', 'bg' => 'bg-amber-50/50'],
                            'late' => ['color' => 'rose', 'label' => 'Terlambat', 'border' => 'border-l-rose-500', 'bg' => 'bg-rose-50/50'],
                            default => $sudahLewat 
                                ? ['color' => 'rose', 'label' => 'Overdue', 'border' => 'border-l-rose-500', 'bg' => 'bg-rose-50/50']
                                : ['color' => 'slate', 'label' => 'Belum Dikerjakan', 'border' => 'border-l-slate-300', 'bg' => '']
                        };
                    @endphp
                    
                    <div class="p-5 {{ $statusConfig['bg'] }} border-l-4 {{ $statusConfig['border'] }} hover:bg-slate-50 transition-colors">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            {{-- Left: Week & Info --}}
                            <div class="flex items-start gap-4 flex-1">
                                {{-- Week Badge --}}
                                <div class="w-14 h-14 rounded-lg bg-slate-800 text-white flex flex-col items-center justify-center flex-shrink-0">
                                    <span class="text-[10px] font-medium uppercase">Minggu</span>
                                    <span class="text-xl font-bold leading-none">{{ $target->week_number }}</span>
                                </div>
                                
                                {{-- Content --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        <h4 class="font-bold text-slate-800">{{ $target->title }}</h4>
                                        <span class="px-2 py-0.5 rounded text-xs font-semibold bg-{{ $statusConfig['color'] }}-100 text-{{ $statusConfig['color'] }}-700">
                                            {{ $statusConfig['label'] }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-slate-500 mb-2 line-clamp-2">{{ Str::limit($target->description, 100) }}</p>
                                    
                                    {{-- Deadline --}}
                                    @if($target->deadline)
                                    <div class="flex items-center gap-3 text-sm">
                                        <span class="text-slate-400">
                                            <i class="far fa-calendar-alt mr-1"></i>
                                            {{ $target->deadline->format('d M Y, H:i') }}
                                        </span>
                                        
                                        @if(!$sudahLewat && $target->submission_status == 'pending')
                                        <span class="countdown-timer px-2 py-0.5 rounded bg-blue-100 text-blue-700 text-xs font-semibold" 
                                              data-deadline="{{ $target->deadline->timestamp }}">
                                            <i class="fas fa-hourglass-half mr-1"></i>
                                            <span class="timer-text">Memuat...</span>
                                        </span>
                                        @elseif($sudahLewat && $target->submission_status == 'pending')
                                        <span class="px-2 py-0.5 rounded bg-rose-500 text-white text-xs font-semibold animate-pulse">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            Terlambat
                                        </span>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            {{-- Right: Action Button --}}
                            <a href="{{ route('targets.submissions.show', $target->id) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                Buka Target
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                {{-- Empty State - Simple --}}
                <div class="p-12 text-center">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-slate-100 flex items-center justify-center">
                        <i class="fas fa-clipboard-list text-slate-400 text-3xl"></i>
                    </div>
                    <h4 class="text-lg font-bold text-slate-800 mb-2">Belum Ada Target</h4>
                    <p class="text-slate-500 text-sm max-w-sm mx-auto">
                        Dosen belum memberikan target mingguan untuk kelompok Anda.
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Countdown Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function updateCountdowns() {
                const now = Math.floor(Date.now() / 1000);
                
                document.querySelectorAll('.countdown-timer').forEach(timer => {
                    const deadline = parseInt(timer.getAttribute('data-deadline'));
                    const diff = deadline - now;
                    const textElement = timer.querySelector('.timer-text');
                    
                    if (diff <= 0) {
                        location.reload();
                        return;
                    }
                    
                    const days = Math.floor(diff / 86400);
                    const hours = Math.floor((diff % 86400) / 3600);
                    const minutes = Math.floor((diff % 3600) / 60);
                    const seconds = diff % 60;
                    
                    let text = '';
                    if (days > 0) text += `${days}h `;
                    if (hours > 0 || days > 0) text += `${hours}j `;
                    text += `${minutes}m ${seconds}d`;
                    
                    textElement.textContent = text;
                    
                    // Urgent styling if less than 48 hours
                    if (diff <= 172800) {
                        timer.className = 'countdown-timer px-2 py-0.5 rounded bg-rose-500 text-white text-xs font-semibold animate-pulse';
                    }
                });
            }
            
            updateCountdowns();
            setInterval(updateCountdowns, 1000);
        });
    </script>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-app-layout>
