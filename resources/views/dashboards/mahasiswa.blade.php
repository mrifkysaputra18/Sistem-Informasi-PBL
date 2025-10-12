<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div class="space-y-1">
                <h2 class="font-bold text-2xl text-white leading-tight flex items-center gap-2">
                    <i class="fas fa-th-large"></i>
                    Dashboard Mahasiswa
                </h2>
                <p class="text-sm text-white opacity-90 flex items-center gap-2">
                    <i class="fas fa-user-circle text-xs"></i>
                    Selamat datang, <span class="font-semibold">{{ auth()->user()->name }}</span>
                </p>
            </div>
            @if($myGroup)
            <div class="flex items-center gap-3">
                <div class="bg-white/20 backdrop-blur-sm px-4 py-2.5 rounded-xl border border-white/30 flex items-center gap-2 shadow-lg">
                    <div class="w-8 h-8 rounded-lg bg-white/30 flex items-center justify-center">
                        <i class="fas fa-users text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs text-white/80 font-medium">Kelompok</p>
                        <p class="text-sm text-white font-bold">{{ $myGroup->name }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($myGroup)
                <!-- Statistics Cards - Laws of UX: Visual Hierarchy, Aesthetic-Usability Effect -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
                    <!-- Total Targets -->
                    <div class="group bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-200 hover:-translate-y-1">
                        <div class="p-5">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Total Target</p>
                                    <p class="text-4xl font-black text-gray-900 tracking-tight">{{ $stats['totalTargets'] }}</p>
                                </div>
                                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-bullseye text-white text-2xl"></i>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 text-sm">
                                <span class="text-gray-600">Semua target yang diberikan</span>
                            </div>
                        </div>
                        <div class="h-1 bg-gradient-to-r from-blue-500 to-blue-600"></div>
                    </div>

                    <!-- Submitted Targets -->
                    <div class="group bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-green-200 hover:-translate-y-1">
                        <div class="p-5">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Sudah Submit</p>
                                    <p class="text-4xl font-black text-gray-900 tracking-tight">{{ $stats['submittedTargets'] }}</p>
                                </div>
                                <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg shadow-green-200 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-check-circle text-white text-2xl"></i>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 text-sm">
                                <i class="fas fa-arrow-up text-green-600 text-xs"></i>
                                <span class="text-green-600 font-semibold">{{ $stats['completedTargets'] }} approved</span>
                            </div>
                        </div>
                        <div class="h-1 bg-gradient-to-r from-green-500 to-green-600"></div>
                    </div>

                    <!-- Completion Rate -->
                    <div class="group bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-purple-200 hover:-translate-y-1">
                        <div class="p-5">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Progress</p>
                                    <p class="text-4xl font-black text-gray-900 tracking-tight">{{ $stats['completionRate'] }}<span class="text-2xl">%</span></p>
                                </div>
                                <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-200 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-chart-line text-white text-2xl"></i>
                                </div>
                            </div>
                            <!-- Progress Bar -->
                            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-purple-500 to-purple-600 rounded-full transition-all duration-500" style="width: {{ $stats['completionRate'] }}%"></div>
                            </div>
                        </div>
                        <div class="h-1 bg-gradient-to-r from-purple-500 to-purple-600"></div>
                    </div>

                    <!-- Pending Targets -->
                    <div class="group bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-orange-200 hover:-translate-y-1">
                        <div class="p-5">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Belum Submit</p>
                                    <p class="text-4xl font-black text-gray-900 tracking-tight">{{ $stats['pendingTargets'] }}</p>
                                </div>
                                <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-orange-200 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-clock text-white text-2xl"></i>
                                </div>
                            </div>
                            @if($stats['pendingTargets'] > 0)
                            <div class="flex items-center gap-2 text-sm">
                                <div class="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></div>
                                <span class="text-orange-600 font-semibold">Perlu segera disubmit</span>
                            </div>
                            @else
                            <div class="flex items-center gap-2 text-sm">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                                <span class="text-green-600 font-semibold">Semua sudah submit!</span>
                            </div>
                            @endif
                        </div>
                        <div class="h-1 bg-gradient-to-r from-orange-500 to-orange-600"></div>
                    </div>
                </div>

                <!-- Group Info Card - Laws of UX: Progressive Disclosure, Law of Common Region -->
                <div class="bg-white rounded-2xl shadow-md overflow-hidden mb-6 border border-gray-100">
                    <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 px-6 py-5">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-white text-lg flex items-center gap-2">
                                <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                    <i class="fas fa-users"></i>
                                </div>
                                Informasi Kelompok
                            </h3>
                            <button onclick="toggleGroupDetails()" class="text-white hover:bg-white/20 px-3 py-2 rounded-lg transition-all duration-200 flex items-center gap-2 text-sm font-semibold">
                                <span id="toggleText">Lihat Detail</span>
                                <i id="toggleIcon" class="fas fa-chevron-down transition-transform duration-300"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Quick Info (Always Visible) -->
                    <div class="p-6 bg-gradient-to-br from-gray-50 to-white">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="flex items-center gap-3 p-3 bg-white rounded-xl shadow-sm border border-gray-100">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-tag text-white"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-gray-500 font-medium uppercase">Kelompok</p>
                                    <p class="font-bold text-gray-900 truncate">{{ $myGroup->name }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3 p-3 bg-white rounded-xl shadow-sm border border-gray-100">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-chalkboard text-white"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-gray-500 font-medium uppercase">Kelas</p>
                                    <p class="font-bold text-gray-900 truncate">
                                        @if($myGroup->classRoom)
                                        {{ $myGroup->classRoom->name }}
                                        @else
                                        -
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3 p-3 bg-white rounded-xl shadow-sm border border-gray-100">
                                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-crown text-white"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-gray-500 font-medium uppercase">Ketua</p>
                                    <p class="font-bold text-gray-900 truncate">
                                        @if($myGroup->leader)
                                        {{ $myGroup->leader->name }}
                                        @if($myGroup->leader->id === auth()->id())
                                        <span class="text-xs text-yellow-600">(Anda)</span>
                                        @endif
                                        @else
                                        Belum ada
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3 p-3 bg-white rounded-xl shadow-sm border border-gray-100">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-users text-white"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-gray-500 font-medium uppercase">Anggota</p>
                                    <p class="font-bold text-gray-900">
                                        <span class="text-purple-600">{{ $myGroup->members->count() }}</span> / {{ $myGroup->max_members }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Members List (Progressive Disclosure) -->
                    <div id="groupDetails" class="hidden border-t border-gray-200">
                        <div class="p-6 bg-white">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-bold text-gray-900 flex items-center gap-2">
                                    <i class="fas fa-user-friends text-indigo-600"></i>
                                    Daftar Anggota Kelompok
                                </h4>
                                <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs font-semibold">
                                    {{ $myGroup->members->count() }} Orang
                                </span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($myGroup->members as $index => $member)
                                <div class="flex items-center gap-3 p-4 bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-200 hover:border-indigo-300 hover:shadow-md transition-all duration-200">
                                    <div class="relative">
                                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                            <span class="text-white font-bold text-lg">{{ substr($member->user->name, 0, 1) }}</span>
                                        </div>
                                        @if($member->is_leader)
                                        <div class="absolute -top-1 -right-1 w-5 h-5 bg-yellow-400 rounded-full flex items-center justify-center shadow-md">
                                            <i class="fas fa-crown text-yellow-900 text-xs"></i>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-gray-900 flex items-center gap-2">
                                            {{ $member->user->name }}
                                            @if($member->user->id === auth()->id())
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
                                                Anda
                                            </span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-600 truncate">
                                            <i class="fas fa-envelope mr-1"></i>{{ $member->user->email }}
                                        </p>
                                        @if($member->user->nim)
                                        <p class="text-xs text-indigo-600 font-semibold mt-0.5">
                                            <i class="fas fa-id-card mr-1"></i>{{ $member->user->nim }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Weekly Targets Section - Laws of UX: Visual Hierarchy, Fitts's Law, Feedback -->
                <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 px-6 py-5">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                    <i class="fas fa-tasks text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-white text-lg">Target Mingguan</h3>
                                    <p class="text-sm text-white/80">Diberikan oleh Dosen</p>
                                </div>
                            </div>
                            @if($weeklyTargets->count() > 0)
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1.5 bg-white/20 backdrop-blur-sm rounded-lg text-white text-sm font-semibold">
                                    {{ $weeklyTargets->count() }} Target
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="p-6 bg-gradient-to-br from-gray-50 to-white">
                        @if($weeklyTargets->count() > 0)
                            <div class="space-y-4">
                                @foreach($weeklyTargets as $target)
                                @php
                                    $statusConfig = [
                                        'pending' => [
                                            'bg' => 'bg-gray-100',
                                            'text' => 'text-gray-800',
                                            'icon' => 'fa-clock',
                                            'border' => 'border-gray-200'
                                        ],
                                        'submitted' => [
                                            'bg' => 'bg-blue-100',
                                            'text' => 'text-blue-800',
                                            'icon' => 'fa-paper-plane',
                                            'border' => 'border-blue-200'
                                        ],
                                        'late' => [
                                            'bg' => 'bg-red-100',
                                            'text' => 'text-red-800',
                                            'icon' => 'fa-exclamation-triangle',
                                            'border' => 'border-red-200'
                                        ],
                                        'approved' => [
                                            'bg' => 'bg-green-100',
                                            'text' => 'text-green-800',
                                            'icon' => 'fa-check-circle',
                                            'border' => 'border-green-200'
                                        ],
                                        'revision' => [
                                            'bg' => 'bg-yellow-100',
                                            'text' => 'text-yellow-800',
                                            'icon' => 'fa-redo',
                                            'border' => 'border-yellow-200'
                                        ],
                                    ];
                                    $status = $statusConfig[$target->submission_status] ?? $statusConfig['pending'];
                                @endphp
                                
                                <!-- Target Card -->
                                <div class="bg-white rounded-xl border-2 {{ $status['border'] }} hover:shadow-lg transition-all duration-300 overflow-hidden group {{ $target->isOverdue() && $target->submission_status === 'pending' ? 'ring-2 ring-orange-300' : '' }}">
                                    <div class="p-5">
                                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                                            <!-- Left Section: Info -->
                                            <div class="flex-1 space-y-3">
                                                <div class="flex items-start gap-3">
                                                    <!-- Week Badge -->
                                                    <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex flex-col items-center justify-center shadow-lg">
                                                        <span class="text-white text-xs font-semibold uppercase">Minggu</span>
                                                        <span class="text-white text-2xl font-black">{{ $target->week_number }}</span>
                                                    </div>
                                                    
                                                    <!-- Title & Description -->
                                                    <div class="flex-1 min-w-0">
                                                        <h4 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-indigo-600 transition-colors">
                                                            {{ $target->title }}
                                                        </h4>
                                                        @if($target->description)
                                                        <p class="text-sm text-gray-600 line-clamp-2">
                                                            {{ $target->description }}
                                                        </p>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <!-- Meta Info -->
                                                <div class="flex flex-wrap items-center gap-3 pl-19">
                                                    <!-- Deadline -->
                                                    @if($target->deadline)
                                                    <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-100 rounded-lg">
                                                        <i class="fas fa-calendar-alt text-gray-600 text-sm"></i>
                                                        <div class="text-xs">
                                                            <p class="font-semibold text-gray-900">{{ $target->deadline->format('d M Y') }}</p>
                                                            <p class="text-gray-500">{{ $target->deadline->format('H:i') }} WIB</p>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    
                                                    <!-- Status Badge -->
                                                    <div class="flex items-center gap-2 px-3 py-1.5 {{ $status['bg'] }} rounded-lg">
                                                        <i class="fas {{ $status['icon'] }} {{ $status['text'] }} text-sm"></i>
                                                        <span class="text-xs font-bold {{ $status['text'] }} uppercase">
                                                            {{ $target->getStatusLabel() }}
                                                        </span>
                                                    </div>
                                                    
                                                    <!-- Warning Badges -->
                                                    @if($target->isClosed())
                                                    <div class="flex items-center gap-2 px-3 py-1.5 bg-red-100 rounded-lg">
                                                        <i class="fas fa-lock text-red-600 text-sm"></i>
                                                        <span class="text-xs font-bold text-red-800 uppercase">Tertutup</span>
                                                    </div>
                                                    @elseif($target->isOverdue() && !$target->isSubmitted())
                                                    <div class="flex items-center gap-2 px-3 py-1.5 bg-orange-100 rounded-lg animate-pulse">
                                                        <i class="fas fa-exclamation-triangle text-orange-600 text-sm"></i>
                                                        <span class="text-xs font-bold text-orange-800 uppercase">Terlambat!</span>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- Right Section: Actions -->
                                            <div class="flex lg:flex-col gap-2 pt-2 lg:pt-0">
                                                @if($target->submission_status === 'pending' || $target->submission_status === 'revision')
                                                <!-- Upload Progress Button (Primary Action) -->
                                                <a href="{{ route('weekly-progress.upload', ['group_id' => $myGroup->id, 'week_number' => $target->week_number, 'target_id' => $target->id]) }}" 
                                                   class="flex-1 lg:flex-none inline-flex items-center justify-center gap-2 px-5 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl transition-all duration-200 text-sm font-bold shadow-lg shadow-green-200 hover:shadow-xl hover:-translate-y-0.5">
                                                    <i class="fas fa-upload"></i>
                                                    <span>Upload Progress</span>
                                                </a>
                                                @endif
                                                
                                                <!-- Detail Button (Secondary Action) -->
                                                <a href="{{ route('targets.submissions.show', $target->id) }}" 
                                                   class="flex-1 lg:flex-none inline-flex items-center justify-center gap-2 px-5 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-all duration-200 text-sm font-semibold">
                                                    <i class="fas fa-eye"></i>
                                                    <span>Detail</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Progress Bar (if submitted) -->
                                    @if($target->submission_status === 'submitted')
                                    <div class="bg-blue-50 px-5 py-2 border-t-2 border-blue-200">
                                        <div class="flex items-center gap-2 text-xs text-blue-800">
                                            <div class="flex-1 flex items-center gap-2">
                                                <i class="fas fa-clock animate-spin"></i>
                                                <span class="font-semibold">Menunggu review dari dosen...</span>
                                            </div>
                                        </div>
                                    </div>
                                    @elseif($target->submission_status === 'approved')
                                    <div class="bg-green-50 px-5 py-2 border-t-2 border-green-200">
                                        <div class="flex items-center gap-2 text-xs text-green-800">
                                            <i class="fas fa-check-circle"></i>
                                            <span class="font-semibold">Target telah disetujui!</span>
                                        </div>
                                    </div>
                                    @elseif($target->submission_status === 'revision')
                                    <div class="bg-yellow-50 px-5 py-2 border-t-2 border-yellow-200">
                                        <div class="flex items-center gap-2 text-xs text-yellow-800">
                                            <i class="fas fa-exclamation-circle"></i>
                                            <span class="font-semibold">Perlu revisi - silakan upload ulang!</span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-16">
                                <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-inner">
                                    <i class="fas fa-tasks text-gray-400 text-5xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Target Mingguan</h3>
                                <p class="text-gray-600 max-w-md mx-auto mb-6">
                                    Dosen belum memberikan target mingguan untuk kelompok Anda. Target akan muncul di sini setelah dosen menambahkannya.
                                </p>
                                <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 border border-blue-200 rounded-xl text-sm text-blue-800">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Cek kembali nanti atau hubungi dosen Anda</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            @else
                <!-- No Group Message -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="p-16 text-center">
                        <div class="relative inline-block mb-8">
                            <div class="w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-3xl flex items-center justify-center mx-auto shadow-2xl">
                                <i class="fas fa-users text-gray-400 text-6xl"></i>
                            </div>
                            <div class="absolute -top-2 -right-2 w-12 h-12 bg-orange-500 rounded-2xl flex items-center justify-center shadow-lg animate-bounce">
                                <i class="fas fa-exclamation text-white text-2xl"></i>
                            </div>
                        </div>
                        <h3 class="text-3xl font-black text-gray-900 mb-3">Anda Belum Memiliki Kelompok</h3>
                        <p class="text-gray-600 text-lg mb-8 max-w-lg mx-auto">
                            Untuk dapat mengakses fitur dashboard, Anda perlu tergabung dalam sebuah kelompok.
                        </p>
                        <div class="max-w-md mx-auto space-y-4">
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-2xl p-5">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                        <i class="fas fa-info-circle text-white text-xl"></i>
                                    </div>
                                    <div class="text-left flex-1">
                                        <p class="font-bold text-blue-900 mb-2">Cara Bergabung:</p>
                                        <ul class="text-sm text-blue-800 space-y-1">
                                            <li class="flex items-start gap-2">
                                                <i class="fas fa-check text-blue-600 mt-0.5"></i>
                                                <span>Hubungi koordinator atau admin</span>
                                            </li>
                                            <li class="flex items-start gap-2">
                                                <i class="fas fa-check text-blue-600 mt-0.5"></i>
                                                <span>Minta untuk ditambahkan ke kelompok</span>
                                            </li>
                                            <li class="flex items-start gap-2">
                                                <i class="fas fa-check text-blue-600 mt-0.5"></i>
                                                <span>Refresh halaman setelah ditambahkan</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        // Toggle Group Details (Progressive Disclosure)
        function toggleGroupDetails() {
            const details = document.getElementById('groupDetails');
            const icon = document.getElementById('toggleIcon');
            const text = document.getElementById('toggleText');
            
            if (details.classList.contains('hidden')) {
                details.classList.remove('hidden');
                details.classList.add('animate-slideDown');
                icon.style.transform = 'rotate(180deg)';
                text.textContent = 'Sembunyikan';
            } else {
                details.classList.add('hidden');
                details.classList.remove('animate-slideDown');
                icon.style.transform = 'rotate(0deg)';
                text.textContent = 'Lihat Detail';
            }
        }

        // Animate stats cards on load
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.group');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease-out';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 50);
                }, index * 100);
            });
        });
    </script>
    
    <style>
        @keyframes slideDown {
            from {
                opacity: 0;
                max-height: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                max-height: 1000px;
                transform: translateY(0);
            }
        }
        
        .animate-slideDown {
            animation: slideDown 0.3s ease-out forwards;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Smooth transitions for all interactive elements */
        * {
            scroll-behavior: smooth;
        }
    </style>
    @endpush
</x-app-layout>

