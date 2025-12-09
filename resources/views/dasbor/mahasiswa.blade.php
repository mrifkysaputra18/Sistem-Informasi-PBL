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
                    <p class="text-sm font-bold text-white">Ganjil 2024/2025</p>
                </div>
                <div class="h-8 w-8 rounded-lg bg-blue-500/20 flex items-center justify-center text-white border border-white/10">
                    <i class="far fa-calendar-alt"></i>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($myGroup)
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    
                    <!-- LEFT COLUMN (Main Content - approx 60%) -->
                    <div class="lg:col-span-7 space-y-6">
                        
                        <!-- Header for Task List -->
                        <div class="bg-blue-900 rounded-xl p-6 shadow-md text-white flex justify-between items-center relative overflow-hidden border-2 border-blue-950">
                            <div class="relative z-10">
                                <h3 class="text-xl font-black tracking-wide">DAFTAR TUGAS & TARGET</h3>
                                <p class="text-blue-100 text-sm mt-1 font-bold">Selesaikan target mingguan Anda tepat waktu.</p>
                            </div>
                            <div class="relative z-10">
                                <span class="bg-indigo-800 border-2 border-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-black shadow-sm">
                                    {{ $weeklyTargets->where('submission_status', 'pending')->count() }} MENUNGGU
                                </span>
                            </div>
                            <!-- Decor -->
                            <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-blue-500 rounded-full opacity-20 blur-2xl"></div>
                        </div>

                        <!-- Task List (High Contrast) -->
                        <div class="space-y-4">
                            @forelse($weeklyTargets as $target)
                                @php
                                    $statusConfig = match($target->submission_status) {
                                        'pending' => ['bg' => 'bg-white', 'text' => 'text-orange-700', 'border' => 'border-orange-400', 'label' => 'MENUNGGU PENGUMPULAN', 'icon' => 'fa-clock'],
                                        'submitted' => ['bg' => 'bg-white', 'text' => 'text-blue-700', 'border' => 'border-blue-400', 'label' => 'MENUNGGU REVIEW', 'icon' => 'fa-paper-plane'],
                                        'late' => ['bg' => 'bg-white', 'text' => 'text-red-700', 'border' => 'border-red-400', 'label' => 'TERLAMBAT', 'icon' => 'fa-exclamation-circle'],
                                        'approved' => ['bg' => 'bg-white', 'text' => 'text-green-700', 'border' => 'border-green-400', 'label' => 'SELESAI', 'icon' => 'fa-check-circle'],
                                        'revision' => ['bg' => 'bg-white', 'text' => 'text-yellow-700', 'border' => 'border-yellow-400', 'label' => 'PERLU REVISI', 'icon' => 'fa-tools'],
                                        default => ['bg' => 'bg-white', 'text' => 'text-gray-700', 'border' => 'border-gray-400', 'label' => 'UNKNOWN', 'icon' => 'fa-question'],
                                    };
                                    
                                    $classroomName = $target->group->classRoom->name ?? $myGroup->classRoom->name ?? 'Mata Kuliah Umum';
                                @endphp

                                <div class="bg-white rounded-xl p-6 shadow-sm border-2 border-gray-300 hover:border-blue-600 hover:shadow-md transition-all duration-200 group relative">
                                    <!-- Left Accent for Pending (Thicker & Darker) -->
                                    @if($target->submission_status == 'pending')
                                        <div class="absolute left-0 top-0 bottom-0 w-2 bg-orange-500 rounded-l-lg"></div>
                                    @endif

                                    <div class="flex flex-col sm:flex-row gap-6">
                                        <!-- Week Icon (Bold) -->
                                        <div class="flex-shrink-0">
                                            <div class="w-16 h-16 rounded-xl bg-gray-100 border-2 border-gray-300 text-gray-900 flex flex-col items-center justify-center shadow-inner">
                                                <span class="text-[10px] font-black uppercase text-gray-600 tracking-widest">WEEK</span>
                                                <span class="text-3xl font-black leading-none">{{ $target->week_number }}</span>
                                            </div>
                                        </div>

                                        <!-- Main Content -->
                                        <div class="flex-1 min-w-0 py-1">
                                            <!-- Meta Row -->
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="px-2 py-1 rounded text-[10px] font-black uppercase tracking-wide bg-gray-200 text-gray-800 border border-gray-300">
                                                    {{ $classroomName }}
                                                </span>
                                                @if($target->isClosed())
                                                    <span class="px-2 py-1 rounded text-[10px] font-black uppercase tracking-wide bg-red-100 text-red-800 border border-red-200">
                                                        CLOSED
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Title -->
                                            <h4 class="text-xl font-black text-gray-900 mb-2 leading-tight group-hover:text-blue-800 transition-colors">
                                                {{ $target->title }}
                                            </h4>

                                            <!-- Deadline & Info -->
                                            <div class="flex items-center gap-4 text-sm font-bold text-gray-700">
                                                @if($target->deadline)
                                                    <div class="flex items-center gap-2 {{ now()->gt($target->deadline) && !$target->submission ? 'text-red-700 bg-red-50 px-2 py-0.5 rounded border border-red-200' : 'bg-gray-50 px-2 py-0.5 rounded border border-gray-200' }}">
                                                        <i class="far fa-calendar-alt text-gray-600"></i>
                                                        <span>{{ $target->deadline->format('d M Y, H:i') }}</span>
                                                    </div>
                                                @endif
                                                
                                                {{-- Countdown Timer Badge --}}
                                                @if($target->deadline && $target->submission_status == 'pending' && !$target->isClosed())
                                                    @php
                                                        $sudahLewat = now()->gt($target->deadline);
                                                    @endphp
                                                    @if(!$sudahLewat)
                                                        <span class="countdown-timer inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-black border border-blue-200" 
                                                              data-deadline="{{ $target->deadline->timestamp }}">
                                                            <i class="fas fa-hourglass-half text-blue-500"></i>
                                                            <span class="timer-text font-mono">Memuat...</span>
                                                        </span>
                                                    @else
                                                        <span class="countdown-urgent inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-500 text-white text-xs font-black border-2 border-red-600 shadow-lg">
                                                            <i class="fas fa-skull-crossbones"></i>
                                                            WAKTU HABIS
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Status Badge & Action (Right Side) -->
                                        <div class="flex flex-col items-end gap-3 justify-center min-w-[160px]">
                                            <span class="px-3 py-1.5 rounded-lg text-xs font-black flex items-center gap-2 border-2 {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }} shadow-sm">
                                                <i class="fas {{ $statusConfig['icon'] }}"></i>
                                                {{ $statusConfig['label'] }}
                                            </span>
                                            
                                            @if($target->submission_status == 'pending')
                                            <a href="{{ route('targets.submissions.show', $target->id) }}" class="w-full text-center px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white font-bold rounded-lg shadow-sm border-2 border-blue-900 transition-colors text-sm">
                                                KERJAKAN <i class="fas fa-arrow-right ml-1"></i>
                                            </a>
                                            @else
                                            <a href="{{ route('targets.submissions.show', $target->id) }}" class="w-full text-center px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 hover:text-gray-900 font-bold rounded-lg shadow-sm border-2 border-gray-300 transition-colors text-sm">
                                                LIHAT DETAIL
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="bg-white rounded-xl p-10 shadow-sm border-2 border-gray-200 text-center">
                                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-5 text-green-600 border-2 border-green-200">
                                        <i class="fas fa-check text-3xl"></i>
                                    </div>
                                    <h3 class="text-xl font-black text-gray-900">SEMUA BERES!</h3>
                                    <p class="text-gray-600 font-bold mt-2">Tidak ada tugas yang perlu dikerjakan saat ini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- RIGHT COLUMN (Sidebar - approx 40%) -->
                    <div class="lg:col-span-5 space-y-6">
                        
                        <!-- 1. Stats Summary Widget (Compact & Contrast) -->
                        <div class="bg-white rounded-xl shadow-sm border-2 border-gray-300 p-6">
                            <h3 class="font-black text-gray-900 mb-5 text-sm uppercase tracking-widest border-b-2 border-gray-100 pb-2">RINGKASAN TARGET</h3>
                            <div class="grid grid-cols-3 gap-3">
                                <!-- Completed -->
                                <div class="text-center p-3 rounded-xl bg-white border-2 border-green-400 shadow-sm">
                                    <span class="block text-3xl font-black text-green-700">{{ $stats['completedTargets'] }}</span>
                                    <span class="text-[10px] font-black text-green-800 uppercase tracking-wide">SELESAI</span>
                                </div>
                                <!-- Pending -->
                                <div class="text-center p-3 rounded-xl bg-white border-2 border-orange-400 shadow-sm">
                                    <span class="block text-3xl font-black text-orange-600">{{ $stats['pendingTargets'] }}</span>
                                    <span class="text-[10px] font-black text-orange-800 uppercase tracking-wide">PENDING</span>
                                </div>
                                <!-- Total -->
                                <div class="text-center p-3 rounded-xl bg-white border-2 border-gray-300 shadow-sm">
                                    <span class="block text-3xl font-black text-gray-800">{{ $stats['totalTargets'] }}</span>
                                    <span class="text-[10px] font-black text-gray-600 uppercase tracking-wide">TOTAL</span>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Semester Progress (Contrast) -->
                        <div class="bg-white rounded-xl shadow-sm border-2 border-gray-300 p-6">
                            <div class="flex justify-between items-end mb-3">
                                <div>
                                    <h3 class="font-black text-gray-900 uppercase text-sm tracking-wide">PROGRESS SEMESTER</h3>
                                    <p class="text-xs text-gray-600 font-bold mt-1">Pencapaian target keseluruhan</p>
                                </div>
                                <span class="text-4xl font-black text-blue-800">{{ round($stats['completionRate']) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-5 mb-2 border border-gray-300">
                                <div class="bg-blue-700 h-5 rounded-full shadow-sm transition-all duration-500 relative overflow-hidden" 
                                     style="width: {{ $stats['completionRate'] }}%">
                                     <div class="absolute inset-0 bg-white/10 w-full h-full"></div>
                                </div>
                            </div>
                            <p class="text-xs text-right text-gray-600 font-bold mt-2">
                                <span class="text-green-700">{{ $stats['completedTargets'] }} Selesai</span> dari <span class="text-gray-900">{{ $stats['totalTargets'] }} Total Target</span>
                            </p>
                        </div>

                        <!-- 3. Group Information (Contrast) -->
                        <div class="bg-white rounded-xl shadow-sm border-2 border-gray-300 overflow-hidden">
                            <div class="p-5 border-b-2 border-gray-200 bg-gray-50 flex justify-between items-center">
                                <h3 class="font-black text-gray-900 uppercase text-sm tracking-wide">INFORMASI KELOMPOK</h3>
                                <span class="text-xs font-black bg-white border-2 border-gray-300 px-3 py-1 rounded text-gray-700">
                                    {{ $myGroup->members->count() }} ANGGOTA
                                </span>
                            </div>
                            <div class="p-6 space-y-6">
                                <!-- Nama & Kelas -->
                                <div>
                                    <p class="text-xs font-black text-gray-500 uppercase tracking-widest mb-1">KELOMPOK & KELAS</p>
                                    <div class="flex items-center justify-between">
                                        <p class="font-black text-gray-900 text-xl">{{ $myGroup->name }}</p>
                                        <span class="text-xs font-black bg-blue-100 text-blue-800 px-3 py-1.5 rounded border border-blue-300">
                                            {{ $myGroup->classRoom->name ?? '-' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Ketua -->
                                <div class="flex items-center gap-4 bg-white p-4 border-2 border-gray-200 rounded-xl hover:border-gray-400 transition-colors">
                                    <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-800 flex items-center justify-center font-black text-lg border-2 border-purple-200">
                                        {{ substr($myGroup->leader->name ?? '?', 0, 2) }}
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-wider">KETUA KELOMPOK</p>
                                        <p class="text-base font-bold text-gray-900 truncate">{{ $myGroup->leader->name ?? '-' }}</p>
                                    </div>
                                </div>
                                
                                <!-- Members List (Vertical Full) -->
                                <div>
                                    <p class="text-xs font-black text-gray-500 uppercase tracking-widest mb-3">ANGGOTA LAINNYA</p>
                                    <div class="space-y-3">
                                        @foreach($myGroup->members as $member)
                                            @if($member->user_id !== $myGroup->leader_id)
                                                <div class="flex items-center gap-3 bg-white p-3 rounded-xl border-2 border-gray-200 hover:border-blue-300 transition-colors shadow-sm text-left">
                                                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gray-100 text-gray-700 flex items-center justify-center text-xs font-black border-2 border-gray-300">
                                                        {{ substr($member->user->name, 0, 1) }}
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-black text-gray-900 truncate">{{ $member->user->name }}</p>
                                                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">{{ $member->user->nim ?? 'ANGGOTA' }}</p>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
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
            const URGENT_THRESHOLD = 172800; // 48 jam dalam detik (2 hari)
            
            function updateCountdowns() {
                const now = Math.floor(Date.now() / 1000);
                
                document.querySelectorAll('.countdown-timer').forEach(timer => {
                    const deadline = parseInt(timer.getAttribute('data-deadline'));
                    const diff = deadline - now;
                    const textElement = timer.querySelector('.timer-text');
                    const iconElement = timer.querySelector('i');
                    
                    if (diff <= 0) {
                        // Waktu habis
                        textElement.textContent = "⏰ WAKTU HABIS!";
                        timer.className = 'countdown-timer countdown-urgent inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-600 text-white text-xs font-black border-2 border-red-700 shadow-lg';
                        if (iconElement) iconElement.className = 'fas fa-skull-crossbones text-white';
                        return;
                    }
                    
                    // Hitung komponen waktu
                    const days = Math.floor(diff / 86400);
                    const hours = Math.floor((diff % 86400) / 3600);
                    const minutes = Math.floor((diff % 3600) / 60);
                    const seconds = diff % 60;
                    
                    // Format teks countdown
                    let text = '';
                    if (days > 0) text += `${days}h `;
                    if (hours > 0 || days > 0) text += `${hours}j `;
                    text += `${minutes}m ${seconds}d`;
                    
                    textElement.textContent = text;
                    
                    // Jika sisa waktu ≤ 48 jam (2 hari), ubah ke merah/urgent dengan animasi
                    if (diff <= URGENT_THRESHOLD) {
                        timer.className = 'countdown-timer countdown-urgent inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-500 text-white text-xs font-black border-2 border-red-600 shadow-lg';
                        if (iconElement) iconElement.className = 'fas fa-bell text-yellow-300';
                    } else {
                        // Normal (biru) - tanpa animasi
                        timer.className = 'countdown-timer inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-black border border-blue-200';
                        if (iconElement) iconElement.className = 'fas fa-hourglass-half text-blue-500';
                    }
                });
            }
            
            // Update immediately then every second
            updateCountdowns();
            setInterval(updateCountdowns, 1000);
        });
    </script>
    
    {{-- CSS Animation for Urgent Countdown --}}
    <style>
        /* Animasi membesar-mengecil untuk countdown urgent */
        @keyframes pulse-scale {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.08);
            }
        }
        
        .countdown-urgent {
            animation: pulse-scale 1s ease-in-out infinite, pulse-glow 1.5s ease-in-out infinite;
        }
        
        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 5px 0 rgba(239, 68, 68, 0.4);
            }
            50% {
                box-shadow: 0 0 20px 5px rgba(239, 68, 68, 0.6);
            }
        }
    </style>
</x-app-layout>
