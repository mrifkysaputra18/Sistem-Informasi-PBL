<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-white">Target Mingguan</h2>
                <p class="text-sm text-white">Daftar target mingguan kelompok Anda</p>
            </div>
            @if($group)
            <div class="bg-white/20 px-4 py-2 rounded-lg">
                <p class="text-xs text-white/70">Kelompok</p>
                <p class="text-sm font-bold text-white">{{ $group->name }}</p>
            </div>
            @endif
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Alert Messages --}}
            @if(session('success'))
            <div class="mb-6 bg-white border-l-4 border-emerald-500 p-4 rounded shadow-sm flex items-center gap-3">
                <i class="fas fa-check-circle text-emerald-500 text-xl"></i>
                <p class="text-sm font-bold text-gray-800">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-white border-l-4 border-rose-500 p-4 rounded shadow-sm flex items-center gap-3">
                <i class="fas fa-times-circle text-rose-500 text-xl"></i>
                <p class="text-sm font-bold text-gray-800">{{ session('error') }}</p>
            </div>
            @endif

            {{-- Statistics Cards (White High Contrast) --}}
            @php
                $totalTargets = $targets->count();
                $completedTargets = $targets->where('submission_status', 'approved')->count();
                $progressPercentage = $totalTargets > 0 ? round(($completedTargets / $totalTargets) * 100) : 0;
            @endphp
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <!-- Pending -->
                <div class="bg-white rounded-xl p-5 border-2 border-gray-200 shadow-sm hover:border-blue-400 transition-all group">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-700 border-2 border-blue-100 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white group-hover:border-blue-600 transition-all">
                            <i class="fas fa-clock text-lg"></i>
                        </div>
                        <span class="text-xs font-black text-gray-500 uppercase tracking-wider">Pending</span>
                    </div>
                    <p class="text-4xl font-black text-gray-900">{{ $targets->where('submission_status', 'pending')->count() }}</p>
                </div>

                <!-- Submitted -->
                <div class="bg-white rounded-xl p-5 border-2 border-gray-200 shadow-sm hover:border-indigo-400 transition-all group">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-700 border-2 border-indigo-100 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white group-hover:border-indigo-600 transition-all">
                            <i class="fas fa-paper-plane text-lg"></i>
                        </div>
                        <span class="text-xs font-black text-gray-500 uppercase tracking-wider">Submit</span>
                    </div>
                    <p class="text-4xl font-black text-gray-900">{{ $targets->whereIn('submission_status', ['submitted', 'late'])->count() }}</p>
                </div>

                <!-- Approved -->
                <div class="bg-white rounded-xl p-5 border-2 border-gray-200 shadow-sm hover:border-emerald-400 transition-all group">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 border-2 border-emerald-100 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white group-hover:border-emerald-600 transition-all">
                            <i class="fas fa-check-circle text-lg"></i>
                        </div>
                        <span class="text-xs font-black text-gray-500 uppercase tracking-wider">Selesai</span>
                    </div>
                    <p class="text-4xl font-black text-gray-900">{{ $targets->where('submission_status', 'approved')->count() }}</p>
                </div>

                <!-- Revision -->
                <div class="bg-white rounded-xl p-5 border-2 border-gray-200 shadow-sm hover:border-amber-400 transition-all group">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-700 border-2 border-amber-100 flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white group-hover:border-amber-600 transition-all">
                            <i class="fas fa-edit text-lg"></i>
                        </div>
                        <span class="text-xs font-black text-gray-500 uppercase tracking-wider">Revisi</span>
                    </div>
                    <p class="text-4xl font-black text-gray-900">{{ $targets->where('submission_status', 'revision')->count() }}</p>
                </div>
            </div>

            {{-- Progress Bar --}}
            <div class="bg-white rounded-xl p-6 border-2 border-gray-200 shadow-sm mb-8">
                <div class="flex justify-between items-end mb-3">
                    <div>
                        <h3 class="text-lg font-black text-gray-900">Progress Semester</h3>
                        <p class="text-xs font-bold text-gray-500 mt-1 uppercase tracking-wide">Pencapaian Kelompok</p>
                    </div>
                    <span class="text-3xl font-black text-blue-900">{{ $progressPercentage }}%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-4 border border-gray-200 overflow-hidden">
                    <div class="bg-blue-800 h-4 rounded-full transition-all duration-1000 ease-out" style="width: {{ $progressPercentage }}%"></div>
                </div>
            </div>

            {{-- Target List --}}
            <div class="bg-white rounded-xl border-2 border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b-2 border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="font-black text-gray-800 flex items-center gap-2 text-lg">
                        <i class="fas fa-list-ul text-gray-400"></i>
                        Daftar Target
                    </h3>
                    <span class="px-3 py-1 bg-gray-200 rounded-full text-xs font-bold text-gray-700">{{ $targets->count() }} Target</span>
                </div>
                
                @if($targets->count() > 0)
                <div class="divide-y-2 divide-gray-100">
                    @foreach($targets as $target)
                    @php
                        $waktuSekarang = now();
                        $deadline = $target->deadline;
                        $sudahLewat = $deadline && $waktuSekarang->gt($deadline);
                        
                        // Status Logic
                        $statusColor = 'gray';
                        $statusLabel = 'Unknown';
                        $rowBorderClass = 'border-l-4 border-l-gray-300';
                        
                        if ($target->submission_status == 'approved') {
                            $statusColor = 'emerald';
                            $statusLabel = 'Disetujui';
                            $rowBorderClass = 'border-l-4 border-l-emerald-500 bg-emerald-50/10';
                        } elseif ($target->submission_status == 'submitted') {
                            $statusColor = 'blue';
                            $statusLabel = 'Menunggu Review';
                            $rowBorderClass = 'border-l-4 border-l-blue-500 bg-blue-50/10';
                        } elseif ($target->submission_status == 'revision') {
                            $statusColor = 'amber';
                            $statusLabel = 'Perlu Revisi';
                            $rowBorderClass = 'border-l-4 border-l-amber-500 bg-amber-50/10';
                        } elseif ($sudahLewat && $target->submission_status == 'pending') {
                            $statusColor = 'rose';
                            $statusLabel = 'Terlambat';
                            $rowBorderClass = 'border-l-4 border-l-rose-500 bg-rose-50/30';
                        } else {
                            $statusColor = 'gray';
                            $statusLabel = 'Belum Dikerjakan';
                            $rowBorderClass = 'border-l-4 border-l-gray-300';
                        }
                    @endphp
                    <div class="p-6 hover:bg-gray-50 transition-colors {{ $rowBorderClass }}">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div class="flex items-start gap-6 flex-1">
                                {{-- Week Box --}}
                                <div class="flex flex-col items-center justify-center w-20 h-20 rounded-xl bg-white border-2 border-gray-200 text-gray-800 flex-shrink-0 shadow-sm">
                                    <span class="text-[9px] font-black uppercase text-gray-400 tracking-widest">MINGGU</span>
                                    <span class="text-3xl font-black leading-tight">{{ $target->week_number }}</span>
                                </div>
                                
                                {{-- Content Area with margin --}}
                                <div class="flex-1 min-w-0 pl-2">
                                    <div class="flex flex-wrap items-center gap-3 mb-2">
                                        <h4 class="text-lg font-black text-gray-900">{{ $target->title }}</h4>
                                        <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wide bg-{{ $statusColor }}-100 text-{{ $statusColor }}-700 border border-{{ $statusColor }}-200">
                                            {{ $statusLabel }}
                                        </span>
                                    </div>
                                    <p class="text-sm font-medium text-gray-600 mb-3 leading-relaxed">{{ Str::limit($target->description, 120) }}</p>
                                    
                                    {{-- Deadline Info --}}
                                    @if($target->deadline)
                                    <div class="flex flex-wrap items-center gap-4 mt-4">
                                        <div class="flex items-center gap-2 text-sm font-bold {{ $sudahLewat ? 'text-gray-400' : 'text-gray-500' }}">
                                            <i class="far fa-calendar-alt"></i>
                                            <span>{{ $target->deadline->format('d M Y, H:i') }}</span>
                                        </div>

                                        {{-- Separator --}}
                                        <span class="text-gray-300 hidden sm:inline">|</span>

                                        {{-- Countdown Badge --}}
                                        @if(!$sudahLewat && $target->submission_status == 'pending')
                                            <span class="countdown-timer inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50 text-blue-700 text-xs font-black border border-blue-200" 
                                                  data-deadline="{{ $target->deadline->timestamp }}">
                                                <i class="fas fa-hourglass-half text-blue-500"></i>
                                                <span class="timer-text font-mono">Memuat...</span>
                                            </span>
                                        @elseif($sudahLewat && $target->submission_status == 'pending')
                                            <span class="countdown-urgent inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-red-500 text-white text-xs font-black border-2 border-red-600 shadow-lg">
                                                <i class="fas fa-exclamation-circle"></i>
                                                TERLAMBAT
                                            </span>
                                        @elseif($target->submission_status == 'submitted' || $target->submission_status == 'approved')
                                             <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-black border border-emerald-200">
                                                <i class="fas fa-check-circle"></i>
                                                Tepat Waktu
                                            </span>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            {{-- Action --}}
                            <div class="flex-shrink-0">
                                <a href="{{ route('targets.submissions.show', $target->id) }}" 
                                   class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-md border-2 border-blue-700">
                                    <span>Buka Target</span>
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="p-16 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6 border-2 border-gray-200">
                        <i class="fas fa-clipboard-list text-gray-400 text-4xl"></i>
                    </div>
                    <h4 class="text-xl font-black text-gray-900 mb-2">Belum Ada Target</h4>
                    <p class="text-gray-500 font-medium mb-8">Dosen belum memberikan target mingguan untuk kelompok Anda.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Script for Countdown --}}
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
            animation: pulse-scale 1s ease-in-out infinite;
        }
        
        /* Tambahan: glow effect saat urgent */
        .countdown-urgent {
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
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

    <style>
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        @keyframes pulse-urgent {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.08); }
        }
        
        .animate-pulse-urgent {
            animation: pulse-urgent 1s ease-in-out infinite;
        }
    </style>

    <script>
        function updateCountdown() {
            const sekarang = Math.floor(Date.now() / 1000);
            
            document.querySelectorAll('.countdown-badge').forEach(badge => {
                const deadline = parseInt(badge.dataset.deadline);
                const selisih = deadline - sekarang;
                const sudahLewat = selisih < 0;
                
                const totalDetik = Math.abs(selisih);
                const hari = Math.floor(totalDetik / 86400);
                const jam = Math.floor((totalDetik % 86400) / 3600);
                const menit = Math.floor((totalDetik % 3600) / 60);
                const detik = totalDetik % 60;

                let teks, icon, kelas;
                
                if (sudahLewat) {
                    if (hari > 0) teks = `Lewat ${hari} hari ${jam} jam`;
                    else if (jam > 0) teks = `Lewat ${jam} jam ${menit} menit`;
                    else teks = `Lewat ${menit} menit`;
                    kelas = 'countdown-badge text-xs px-2 py-1 rounded font-medium bg-red-600 text-white animate-pulse-urgent';
                    icon = 'fas fa-times-circle';
                } else {
                    if (hari > 0) teks = `${hari} hari ${jam} jam lagi`;
                    else if (jam > 0) teks = `${jam} jam ${menit} menit lagi`;
                    else if (menit > 0) teks = `${menit} menit ${detik} detik lagi`;
                    else teks = `${detik} detik lagi`;
                    
                    // 2 hari = 172800 detik
                    if (totalDetik <= 172800) {
                        kelas = 'countdown-badge text-xs px-2 py-1 rounded font-medium bg-red-500 text-white animate-pulse-urgent';
                        icon = 'fas fa-exclamation-circle';
                    } else {
                        kelas = 'countdown-badge text-xs px-2 py-1 rounded font-medium bg-gray-100 text-gray-600';
                        icon = 'far fa-calendar';
                    }
                }

                badge.className = kelas;
                badge.querySelector('i').className = icon + ' mr-1';
                badge.querySelector('.countdown-text').textContent = teks;
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateCountdown();
            setInterval(updateCountdown, 1000);
        });
    </script>
</x-app-layout>
