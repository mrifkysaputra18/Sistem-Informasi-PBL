<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-white">Dashboard</h2>
                <p class="text-sm text-white">Selamat datang, {{ auth()->user()->name }}</p>
            </div>
            @if($myGroup)
            <div class="bg-white/20 px-4 py-2 rounded-lg">
                <p class="text-xs text-blue-100">Kelompok</p>
                <p class="text-sm font-bold text-white">{{ $myGroup->name }}</p>
            </div>
            @endif
        </div>
    </x-slot>

    <div class="py-6 bg-blue-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($myGroup)
                {{-- Statistics --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-[#003366] rounded-xl p-4 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-white/70 mb-1">Total Target</p>
                                <p class="text-3xl font-bold">{{ $stats['totalTargets'] }}</p>
                            </div>
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-bullseye text-lg"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-[#003366] rounded-xl p-4 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-white/70 mb-1">Sudah Submit</p>
                                <p class="text-3xl font-bold">{{ $stats['submittedTargets'] }}</p>
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
                                <p class="text-3xl font-bold">{{ $stats['completedTargets'] }}</p>
                            </div>
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check-circle text-lg"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-[#003366] rounded-xl p-4 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-white/70 mb-1">Belum Submit</p>
                                <p class="text-3xl font-bold">{{ $stats['pendingTargets'] }}</p>
                            </div>
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div class="bg-white rounded-xl p-5 shadow-sm mb-6">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-sm font-semibold text-gray-800">Progress Keseluruhan</span>
                        <span class="text-sm font-bold text-gray-800">{{ $stats['completionRate'] }}%</span>
                    </div>
                    <div class="w-full bg-blue-100 rounded-full h-3">
                        <div class="bg-[#003366] h-3 rounded-full transition-all duration-500" style="width: {{ $stats['completionRate'] }}%"></div>
                    </div>
                </div>

                {{-- Group Info --}}
                <div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden">
                    <div class="bg-[#003366] px-5 py-3">
                        <h3 class="font-semibold text-white flex items-center gap-2">
                            <i class="fas fa-users"></i>
                            Informasi Kelompok
                        </h3>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="bg-blue-50 rounded-lg p-3">
                                <p class="text-xs font-medium text-black/60 mb-1">Kelompok</p>
                                <p class="font-semibold text-black">{{ $myGroup->name }}</p>
                            </div>
                            <div class="bg-blue-50 rounded-lg p-3">
                                <p class="text-xs font-medium text-black/60 mb-1">Kelas</p>
                                <p class="font-semibold text-black">{{ $myGroup->classRoom->name ?? '-' }}</p>
                            </div>
                            <div class="bg-blue-50 rounded-lg p-3">
                                <p class="text-xs font-medium text-black/60 mb-1">Ketua</p>
                                <p class="font-semibold text-black">
                                    {{ $myGroup->leader->name ?? 'Belum ada' }}
                                    @if($myGroup->leader && $myGroup->leader->id === auth()->id())
                                        <span class="text-xs text-black/50">(Anda)</span>
                                    @endif
                                </p>
                            </div>
                            <div class="bg-blue-50 rounded-lg p-3">
                                <p class="text-xs font-medium text-black/60 mb-1">Anggota</p>
                                <p class="font-semibold text-black">{{ $myGroup->members->count() }} / {{ $myGroup->max_members }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Target List --}}
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="bg-[#003366] px-5 py-3 flex justify-between items-center">
                        <h3 class="font-semibold text-white flex items-center gap-2">
                            <i class="fas fa-tasks"></i>
                            Target Mingguan
                        </h3>
                        <span class="text-sm text-white/70">{{ $weeklyTargets->count() }} target</span>
                    </div>
                    
                    @if($weeklyTargets->count() > 0)
                    <div class="divide-y divide-blue-100">
                        @foreach($weeklyTargets as $target)
                        <div class="p-4 hover:bg-blue-50/50 transition-colors">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-start gap-4 flex-1">
                                    {{-- Week Number --}}
                                    <div class="w-14 h-14 bg-[#003366] rounded-xl flex flex-col items-center justify-center flex-shrink-0">
                                        <span class="text-[10px] text-white/70 uppercase">Week</span>
                                        <span class="text-xl font-bold text-white">{{ $target->week_number }}</span>
                                    </div>
                                    
                                    {{-- Content --}}
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-semibold text-gray-800 mb-1">{{ $target->title }}</h4>
                                        @if($target->description)
                                        <p class="text-sm text-slate-500 mb-2 line-clamp-1">{{ $target->description }}</p>
                                        @endif
                                        
                                        <div class="flex flex-wrap items-center gap-2">
                                            {{-- Deadline --}}
                                            @if($target->deadline)
                                            <span class="text-xs text-slate-500 bg-slate-100 px-2 py-1 rounded">
                                                <i class="far fa-calendar mr-1"></i>
                                                {{ $target->deadline->format('d M Y, H:i') }}
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
                                            @elseif($target->isOverdue() && !$target->isSubmitted())
                                            <span class="px-2 py-1 rounded text-xs font-medium bg-orange-100 text-orange-700">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>Lewat Deadline
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
                        <h4 class="font-semibold text-gray-800 mb-2">Belum Ada Target</h4>
                        <p class="text-sm text-slate-500">Dosen belum memberikan target mingguan untuk kelompok Anda.</p>
                    </div>
                    @endif
                </div>

            @else
                {{-- No Group --}}
                <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                    <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Memiliki Kelompok</h3>
                    <p class="text-sm text-slate-500 mb-6">Hubungi koordinator atau admin untuk bergabung ke kelompok.</p>
                    <div class="bg-blue-50 rounded-lg p-4 max-w-md mx-auto text-left">
                        <p class="text-sm font-medium text-gray-800 mb-2">Cara Bergabung:</p>
                        <ul class="text-sm text-slate-600 space-y-1">
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Hubungi koordinator atau admin</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Minta untuk ditambahkan ke kelompok</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Refresh halaman setelah ditambahkan</li>
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-app-layout>
