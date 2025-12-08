<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-white">Dashboard</h2>
                <p class="text-sm text-white/80">Selamat datang, {{ auth()->user()->name }}</p>
            </div>
            @if($myGroup)
            <div class="text-right">
                <p class="text-xs text-white/70">Kelompok</p>
                <p class="text-sm font-semibold text-white">{{ $myGroup->name }}</p>
            </div>
            @endif
        </div>
    </x-slot>

    <div class="py-6 bg-gray-200 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">
            @if($myGroup)
                <!-- Stats -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white rounded-xl shadow-md border-l-4 border-gray-600 p-5">
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Target</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['totalTargets'] }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md border-l-4 border-blue-500 p-5">
                        <p class="text-sm font-medium text-gray-600 mb-1">Sudah Submit</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $stats['submittedTargets'] }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md border-l-4 border-green-500 p-5">
                        <p class="text-sm font-medium text-gray-600 mb-1">Disetujui</p>
                        <p class="text-3xl font-bold text-green-600">{{ $stats['completedTargets'] }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md border-l-4 border-orange-500 p-5">
                        <p class="text-sm font-medium text-gray-600 mb-1">Belum Submit</p>
                        <p class="text-3xl font-bold text-orange-500">{{ $stats['pendingTargets'] }}</p>
                    </div>
                </div>

                <!-- Progress -->
                <div class="bg-white rounded-xl shadow-md p-5">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-sm font-semibold text-gray-700">Progress Keseluruhan</span>
                        <span class="text-lg font-bold text-[#003366]">{{ $stats['completionRate'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-[#003366] h-3 rounded-full transition-all" style="width: {{ $stats['completionRate'] }}%"></div>
                    </div>
                </div>

                <!-- Group Info -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-[#003366] px-5 py-4">
                        <h3 class="font-semibold text-white text-lg">Informasi Kelompok</h3>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <p class="text-xs font-medium text-blue-600 mb-1">Kelompok</p>
                                <p class="font-bold text-gray-900">{{ $myGroup->name }}</p>
                            </div>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <p class="text-xs font-medium text-green-600 mb-1">Kelas</p>
                                <p class="font-bold text-gray-900">{{ $myGroup->classRoom->name ?? '-' }}</p>
                            </div>
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                <p class="text-xs font-medium text-purple-600 mb-1">Ketua</p>
                                <p class="font-bold text-gray-900">
                                    {{ $myGroup->leader->name ?? 'Belum ada' }}
                                    @if($myGroup->leader && $myGroup->leader->id === auth()->id())
                                        <span class="text-xs text-purple-500">(Anda)</span>
                                    @endif
                                </p>
                            </div>
                            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                                <p class="text-xs font-medium text-orange-600 mb-1">Anggota</p>
                                <p class="font-bold text-gray-900">{{ $myGroup->members->count() }} / {{ $myGroup->max_members }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Target List -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-[#003366] px-5 py-4 flex justify-between items-center">
                        <h3 class="font-semibold text-white text-lg">Target Mingguan</h3>
                        <span class="bg-white/20 px-3 py-1 rounded-full text-sm text-white">{{ $weeklyTargets->count() }} target</span>
                    </div>
                    
                    @if($weeklyTargets->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($weeklyTargets as $target)
                        <div class="p-5 hover:bg-blue-50 transition-colors">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-4 flex-1 min-w-0">
                                    <div class="w-14 h-14 bg-[#003366] rounded-xl flex flex-col items-center justify-center flex-shrink-0 shadow">
                                        <span class="text-[10px] text-white/70 uppercase">Week</span>
                                        <span class="text-xl font-bold text-white">{{ $target->week_number }}</span>
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-semibold text-gray-900 text-lg">{{ $target->title }}</h4>
                                        <div class="flex flex-wrap items-center gap-2 mt-2">
                                            @if($target->deadline)
                                            <span class="text-sm text-gray-600 bg-gray-100 px-2 py-1 rounded">
                                                <i class="far fa-calendar-alt mr-1"></i>
                                                {{ $target->deadline->format('d M Y, H:i') }}
                                            </span>
                                            @endif
                                            
                                            @php
                                                $statusConfig = match($target->submission_status) {
                                                    'pending' => ['bg' => 'bg-gray-200', 'text' => 'text-gray-700', 'label' => 'Belum Submit'],
                                                    'submitted' => ['bg' => 'bg-blue-500', 'text' => 'text-white', 'label' => 'Sudah Submit'],
                                                    'late' => ['bg' => 'bg-red-500', 'text' => 'text-white', 'label' => 'Terlambat'],
                                                    'approved' => ['bg' => 'bg-green-500', 'text' => 'text-white', 'label' => 'Disetujui'],
                                                    'revision', 'needs_revision' => ['bg' => 'bg-amber-500', 'text' => 'text-white', 'label' => 'Revisi'],
                                                    default => ['bg' => 'bg-gray-200', 'text' => 'text-gray-700', 'label' => '-'],
                                                };
                                            @endphp
                                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
                                                {{ $statusConfig['label'] }}
                                            </span>
                                            
                                            @if($target->isClosed())
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-600 text-white">
                                                <i class="fas fa-lock mr-1"></i>Tertutup
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <a href="{{ route('targets.submissions.show', $target->id) }}" 
                                   class="px-5 py-2.5 bg-[#003366] hover:bg-[#002244] text-white font-semibold rounded-lg shadow transition-all flex-shrink-0">
                                    Lihat
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-tasks text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500 font-medium">Belum ada target mingguan</p>
                    </div>
                    @endif
                </div>

            @else
                <!-- No Group -->
                <div class="bg-white rounded-xl shadow-md p-12 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Memiliki Kelompok</h3>
                    <p class="text-gray-500">Hubungi koordinator atau admin untuk bergabung ke kelompok.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
