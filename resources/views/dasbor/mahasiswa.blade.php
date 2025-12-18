<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white leading-tight">Dashboard</h2>
                <p class="text-blue-50 text-sm mt-1 font-medium">Selamat datang kembali, {{ auth()->user()->name }} 👋</p>
            </div>
            <div class="flex items-center gap-3 bg-white/10 px-4 py-2 rounded-xl backdrop-blur-sm border border-white/20">
                <div class="text-right hidden sm:block">
                    <p class="text-[10px] text-blue-100 uppercase tracking-wider font-bold opacity-80">Semester Saat Ini</p>
                    <p class="text-sm font-bold text-white">{{ $activePeriod->name ?? 'Tidak Ada Periode Aktif' }}</p>
                </div>
                <div class="h-8 w-8 rounded-lg bg-blue-500/20 flex items-center justify-center text-white border border-white/10">
                    <i class="far fa-calendar-alt"></i>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($myGroup)
                <!-- Two Column Layout using Flexbox -->
                <div class="flex flex-col lg:flex-row gap-6">
                    
                    <!-- LEFT COLUMN - Target List (40%) -->
                    <div class="w-full lg:w-2/5 space-y-4">
                        
                        <!-- Weekly Calendar Header -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold text-gray-900">Kalender</h3>
                                <span class="text-sm text-gray-500">{{ now()->format('d F Y') }}</span>
                            </div>
                            
                            <!-- Mini Week Calendar - Horizontal -->
                            <div class="flex justify-between">
                                @php
                                    $startOfWeek = now()->startOfWeek();
                                    $days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
                                @endphp
                                @foreach($days as $i => $day)
                                    @php
                                        $date = $startOfWeek->copy()->addDays($i);
                                        $isToday = $date->isToday();
                                    @endphp
                                    <div class="flex flex-col items-center">
                                        <span class="text-xs text-gray-400 font-medium">{{ $day }}</span>
                                        <span class="w-9 h-9 flex items-center justify-center rounded-full text-sm font-bold mt-1
                                            {{ $isToday ? 'bg-blue-600 text-white' : 'text-gray-700' }}">
                                            {{ $date->format('d') }}
                                        </span>
                                        @if($isToday)
                                            <span class="w-1.5 h-1.5 bg-blue-600 rounded-full mt-1"></span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Perlu Dikerjakan Header -->
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                Perlu Dikerjakan
                                <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                                    {{ $weeklyTargets->where('submission_status', 'pending')->count() }}
                                </span>
                            </h3>
                        </div>

                        <!-- Target List -->
                        <div class="space-y-3">
                            @forelse($weeklyTargets as $target)
                                @php
                                    $iconBg = match($target->submission_status) {
                                        'pending' => 'bg-orange-100 text-orange-600',
                                        'submitted' => 'bg-blue-100 text-blue-600',
                                        'approved' => 'bg-green-100 text-green-600',
                                        'late' => 'bg-red-100 text-red-600',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp
                                
                                <a href="{{ route('targets.submissions.show', $target->id) }}" 
                                   class="block bg-white rounded-xl p-4 shadow-sm border border-gray-200 hover:border-blue-400 hover:shadow-md transition-all group">
                                    <div class="flex gap-4">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg {{ $iconBg }} flex items-center justify-center">
                                            <i class="fas fa-clipboard-list"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors truncate">
                                                {{ $target->title }}
                                            </h4>
                                            <p class="text-xs text-gray-500 mt-0.5">
                                                {{ $myGroup->classRoom->name ?? 'Kelas' }}
                                            </p>
                                            <div class="flex items-center gap-2 mt-2 text-xs text-gray-500">
                                                <i class="far fa-calendar-alt"></i>
                                                <span>Batas waktu: {{ $target->deadline ? $target->deadline->format('d M Y, H:i') : '-' }}</span>
                                            </div>
                                            
                                            {{-- Countdown Timer Badge --}}
                                            @php
                                                $deadline = $target->deadline;
                                                $sudahLewat = $deadline && now()->gt($deadline);
                                            @endphp
                                            
                                            @if($deadline)
                                                <div class="mt-3">
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
                                </a>
                            @empty
                                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-200 text-center">
                                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-check text-green-600 text-2xl"></i>
                                    </div>
                                    <h4 class="font-bold text-gray-900">Semua Beres!</h4>
                                    <p class="text-sm text-gray-500 mt-1">Tidak ada tugas yang perlu dikerjakan.</p>
                                </div>
                            @endforelse
                        </div>
                        
                        @if($weeklyTargets->count() > 5)
                        <div class="text-center">
                            <a href="{{ route('targets.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-bold inline-flex items-center gap-1">
                                Tampilkan Semuanya <i class="fas fa-chevron-down"></i>
                            </a>
                        </div>
                        @endif
                    </div>

                    <!-- RIGHT COLUMN - Group & Progress Info (60%) -->
                    <div class="w-full lg:w-3/5 space-y-4">
                        
                        <!-- Group Info Card with Tabs -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                            <div class="border-b border-gray-200">
                                <div class="flex">
                                    <button class="px-6 py-3 text-sm font-bold text-blue-600 border-b-2 border-blue-600">
                                        Informasi Kelompok
                                    </button>
                                </div>
                            </div>
                            
                            <div class="p-5">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">{{ $myGroup->name }}</h3>
                                        <p class="text-sm text-gray-500">Kelas {{ $myGroup->classRoom->name ?? '-' }}</p>
                                    </div>
                                    <span class="text-xs font-bold bg-blue-100 text-blue-700 px-3 py-1 rounded-full">
                                        {{ $myGroup->members->count() }} Anggota
                                    </span>
                                </div>
                                
                                <!-- Progress Summary -->
                                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-bold text-gray-700">Progress Keseluruhan</span>
                                        <span class="text-lg font-black text-blue-600">{{ round($stats['completionRate']) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                        <div class="bg-blue-600 h-3 rounded-full transition-all" style="width: {{ $stats['completionRate'] }}%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">
                                        {{ $stats['completedTargets'] }} dari {{ $stats['totalTargets'] }} target selesai
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Group Cards Grid - Side by Side -->
                        <div class="flex gap-4">
                            <!-- Target Selesai Card -->
                            <div class="flex-1 bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium">Target Selesai</p>
                                        <p class="text-xl font-black text-gray-900">{{ $stats['completedTargets'] }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <i class="fas fa-chart-line text-green-500"></i>
                                    <span>dari {{ $stats['totalTargets'] }} total target</span>
                                </div>
                            </div>

                            <!-- Pending Card -->
                            <div class="flex-1 bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium">Menunggu Dikerjakan</p>
                                        <p class="text-xl font-black text-gray-900">{{ $stats['pendingTargets'] }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <i class="fas fa-exclamation-circle text-orange-500"></i>
                                    <span>perlu segera diselesaikan</span>
                                </div>
                            </div>
                        </div>

                        <!-- Member List Card -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-5 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                                <h4 class="font-bold text-gray-900">Anggota Kelompok</h4>
                            </div>
                            <div class="p-5 space-y-3">
                                <!-- Leader -->
                                <div class="flex items-center gap-3 p-3 rounded-lg bg-purple-50 border border-purple-200">
                                    <div class="w-10 h-10 rounded-full bg-purple-600 text-white flex items-center justify-center font-bold">
                                        {{ substr($myGroup->leader->name ?? '?', 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-gray-900 truncate">{{ $myGroup->leader->name ?? '-' }}</p>
                                        <p class="text-xs text-purple-600 font-medium">Ketua Kelompok</p>
                                    </div>
                                    <span class="px-2 py-1 bg-purple-600 text-white text-xs font-bold rounded">KETUA</span>
                                </div>
                                
                                <!-- Other Members -->
                                @foreach($myGroup->members as $member)
                                    @if($member->user_id !== $myGroup->leader_id)
                                    <div class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                        <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center font-bold">
                                            {{ substr($member->user->name, 0, 1) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-gray-900 truncate">{{ $member->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $member->user->nim ?? 'Anggota' }}</p>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            @else
                <!-- No Group State -->
                <div class="flex flex-col items-center justify-center min-h-[50vh] text-center p-8 bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-6 border border-gray-100">
                        <i class="fas fa-user-friends text-gray-300 text-3xl"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Anda Belum Memiliki Kelompok</h2>
                    <p class="text-gray-500 max-w-md mx-auto mb-8 font-medium">
                        Silakan hubungi koordinator atau admin untuk ditambahkan ke dalam kelompok proyek Anda.
                    </p>
                    <a href="#" class="px-6 py-2.5 bg-blue-700 text-white font-bold rounded-lg hover:bg-blue-800 transition shadow-sm">
                        Hubungi Admin
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Script for Countdown Timer --}}
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
    

</x-app-layout>
