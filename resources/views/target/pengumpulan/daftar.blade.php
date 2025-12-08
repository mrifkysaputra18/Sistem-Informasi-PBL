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

    <div class="py-6 bg-blue-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Alert Messages --}}
            @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                <div class="flex items-center">
                    <i class="fas fa-times-circle text-red-500 mr-3"></i>
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            {{-- Statistics Cards --}}
            @php
                $totalTargets = $targets->count();
                $completedTargets = $targets->where('submission_status', 'approved')->count();
                $progressPercentage = $totalTargets > 0 ? round(($completedTargets / $totalTargets) * 100) : 0;
            @endphp
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-[#003366] rounded-xl p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-white/70 mb-1">Belum Dikerjakan</p>
                            <p class="text-3xl font-bold">{{ $targets->where('submission_status', 'pending')->count() }}</p>
                        </div>
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-[#003366] rounded-xl p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-white/70 mb-1">Sudah Submit</p>
                            <p class="text-3xl font-bold">{{ $targets->whereIn('submission_status', ['submitted', 'late'])->count() }}</p>
                        </div>
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-paper-plane text-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-[#003366] rounded-xl p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-white/70 mb-1">Disetujui</p>
                            <p class="text-3xl font-bold">{{ $targets->where('submission_status', 'approved')->count() }}</p>
                        </div>
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-[#003366] rounded-xl p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-white/70 mb-1">Perlu Revisi</p>
                            <p class="text-3xl font-bold">{{ $targets->where('submission_status', 'revision')->count() }}</p>
                        </div>
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-edit text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Progress Bar --}}
            <div class="bg-white rounded-xl p-5 shadow-sm mb-6">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm font-semibold text-black">Progress Keseluruhan</span>
                    <span class="text-sm font-bold text-black">{{ $progressPercentage }}%</span>
                </div>
                <div class="w-full bg-blue-100 rounded-full h-3">
                    <div class="bg-[#003366] h-3 rounded-full transition-all duration-500" style="width: {{ $progressPercentage }}%"></div>
                </div>
            </div>

            {{-- Target List --}}
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="bg-[#003366] px-5 py-3 flex justify-between items-center">
                    <h3 class="font-semibold text-white flex items-center gap-2">
                        <i class="fas fa-tasks"></i>
                        Daftar Target
                    </h3>
                    <span class="text-sm text-white/70">{{ $targets->count() }} target</span>
                </div>
                
                @if($targets->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach($targets as $target)
                    @php
                        // Hitung sisa waktu deadline
                        $waktuSekarang = now();
                        $deadline = $target->deadline;
                        $sudahLewat = $deadline && $waktuSekarang->gt($deadline);
                        $jamTersisa = $deadline ? $waktuSekarang->diffInHours($deadline, false) : 999;
                        
                        // Warna merah hanya untuk 2 hari terakhir (48 jam) atau sudah lewat
                        if ($sudahLewat || $target->isClosed()) {
                            $weekBoxClass = 'bg-red-500';
                            $cardBorderClass = 'border-l-4 border-red-500 bg-red-50';
                        } elseif ($jamTersisa <= 48 && $target->isPending()) {
                            $weekBoxClass = 'bg-red-500';
                            $cardBorderClass = 'border-l-4 border-red-500 bg-red-50';
                        } else {
                            $weekBoxClass = 'bg-[#003366]';
                            $cardBorderClass = '';
                        }
                    @endphp
                    <div class="p-4 hover:bg-blue-50/50 transition-colors {{ $cardBorderClass }}">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-start gap-4 flex-1">
                                {{-- Week Number dengan warna dinamis --}}
                                <div class="w-14 h-14 {{ $weekBoxClass }} rounded-xl flex flex-col items-center justify-center flex-shrink-0">
                                    <span class="text-[10px] text-white/70 uppercase">Week</span>
                                    <span class="text-xl font-bold text-white">{{ $target->week_number }}</span>
                                </div>
                                
                                {{-- Content --}}
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-black mb-1">{{ $target->title }}</h4>
                                    @if($target->description)
                                    <p class="text-sm text-gray-500 mb-2 line-clamp-1">{{ $target->description }}</p>
                                    @endif
                                    
                                    <div class="flex flex-wrap items-center gap-2">
                                        {{-- Deadline dengan countdown realtime --}}
                                        @if($target->deadline)
                                        <span class="countdown-badge text-xs px-2 py-1 rounded font-medium bg-gray-100 text-gray-600"
                                              data-deadline="{{ $target->deadline->timestamp }}">
                                            <i class="far fa-calendar mr-1"></i>
                                            <span class="countdown-text">Memuat...</span>
                                        </span>
                                        @endif
                                        
                                        {{-- Status Badge --}}
                                        @php
                                            $statusConfig = match($target->submission_status) {
                                                'pending' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'label' => 'Belum Submit'],
                                                'submitted' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Sudah Submit'],
                                                'late' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Terlambat'],
                                                'approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Disetujui'],
                                                'revision', 'needs_revision' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'label' => 'Perlu Revisi'],
                                                default => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'label' => 'Unknown'],
                                            };
                                        @endphp
                                        <span class="px-2 py-1 rounded text-xs font-medium {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
                                            {{ $statusConfig['label'] }}
                                        </span>
                                        
                                        {{-- Closed Badge --}}
                                        @if($target->isClosed())
                                        <span class="px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-700">
                                            <i class="fas fa-lock mr-1"></i>Tertutup
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Action Button --}}
                            <a href="{{ route('targets.submissions.show', $target->id) }}" 
                               class="px-4 py-2 bg-[#003366] hover:bg-[#002244] text-white text-sm font-medium rounded-lg transition-all flex-shrink-0">
                                <i class="fas fa-arrow-right mr-1"></i>
                                Lihat
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-tasks text-gray-400 text-3xl"></i>
                    </div>
                    <h4 class="font-semibold text-black mb-2">Belum Ada Target</h4>
                    <p class="text-sm text-gray-500 mb-6">Dosen belum memberikan target mingguan untuk kelompok Anda.</p>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-[#003366] hover:bg-[#002244] text-white text-sm font-medium rounded-lg transition-all">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Dashboard
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

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
