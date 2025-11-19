<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 rounded-b-3xl shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="relative px-6 py-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-2 animate-fade-in">
                            Target Mingguan
                        </h2>
                        <p class="text-white/80 text-sm">Track your weekly progress and submissions</p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-md rounded-xl px-6 py-3 border border-white/30">
                        <p class="text-white/90 text-sm">Kelompok</p>
                        <p class="text-white font-bold text-lg">{{ $group->name }}</p>
                    </div>
                </div>
            </div>
            <!-- Decorative Element -->
            <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -top-4 -left-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Alert Messages with Animation -->
            @if(session('success'))
            <div class="mb-6 transform transition-all duration-500 animate-slide-down">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-4 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500 animate-bounce" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 transform transition-all duration-500 animate-slide-down">
                <div class="bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 p-4 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(session('info'))
            <div class="mb-6 transform transition-all duration-500 animate-slide-down">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 p-4 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-blue-800">{{ session('info') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Progress Overview with Visual Hierarchy (Miller's Law & Aesthetic-Usability Effect) -->
            <div class="mb-8">
                <div class="bg-gradient-to-br from-white via-gray-50 to-indigo-50 rounded-2xl shadow-xl p-6 border border-indigo-100">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Progress Overview
                        </h3>
                        @php
                            $totalTargets = $targets->count();
                            $completedTargets = $targets->where('submission_status', 'approved')->count();
                            $progressPercentage = $totalTargets > 0 ? round(($completedTargets / $totalTargets) * 100) : 0;
                        @endphp
                        <div class="text-right">
                            <p class="text-2xl font-bold text-indigo-600">{{ $progressPercentage }}%</p>
                            <p class="text-xs text-gray-500">Completion Rate</p>
                        </div>
                    </div>

                    <!-- Progress Bar (Visual Feedback) -->
                    <div class="mb-6">
                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-full rounded-full transition-all duration-1000 ease-out"
                                 style="width: {{ $progressPercentage }}%"></div>
                        </div>
                    </div>

                    <!-- Statistics Cards (Law of Common Region) -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Pending Card -->
                        <div class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-gray-50 to-gray-100 p-4 transition-all duration-300 hover:scale-105 hover:shadow-lg cursor-pointer">
                            <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-gray-200 opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
                            <div class="relative">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="p-2 bg-white rounded-lg shadow-sm">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-3xl font-black text-gray-700">{{ $targets->where('submission_status', 'pending')->count() }}</span>
                                </div>
                                <p class="text-xs font-medium text-gray-600">Belum Dikerjakan</p>
                                @if($targets->where('submission_status', 'pending')->where('deadline', '<=', now()->addDay())->count() > 0)
                                    <div class="mt-2 text-xs text-orange-600 font-semibold animate-pulse">
                                        ⚠ Ada deadline mendekat!
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Submitted Card -->
                        <div class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-blue-50 to-indigo-100 p-4 transition-all duration-300 hover:scale-105 hover:shadow-lg cursor-pointer">
                            <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-blue-200 opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
                            <div class="relative">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="p-2 bg-white rounded-lg shadow-sm">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                    </div>
                                    <span class="text-3xl font-black text-blue-700">{{ $targets->whereIn('submission_status', ['submitted', 'late'])->count() }}</span>
                                </div>
                                <p class="text-xs font-medium text-gray-600">Sudah Submit</p>
                                @if($targets->where('submission_status', 'late')->count() > 0)
                                    <div class="mt-2 text-xs text-gray-500">
                                        {{ $targets->where('submission_status', 'late')->count() }} terlambat
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Approved Card (Von Restorff Effect - make success stand out) -->
                        <div class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-green-50 to-emerald-100 p-4 transition-all duration-300 hover:scale-105 hover:shadow-lg cursor-pointer ring-2 ring-green-200 ring-opacity-50">
                            <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-green-200 opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
                            <div class="relative">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="p-2 bg-white rounded-lg shadow-sm">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-3xl font-black text-green-700">{{ $targets->where('submission_status', 'approved')->count() }}</span>
                                </div>
                                <p class="text-xs font-medium text-gray-600">Disetujui</p>
                                @if($completedTargets > 0)
                                    <div class="mt-2">
                                        <div class="flex items-center">
                                            <svg class="w-3 h-3 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            <span class="text-xs text-green-600 font-semibold">Great Job!</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Revision Card -->
                        <div class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-yellow-50 to-amber-100 p-4 transition-all duration-300 hover:scale-105 hover:shadow-lg cursor-pointer">
                            <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-yellow-200 opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
                            <div class="relative">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="p-2 bg-white rounded-lg shadow-sm">
                                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-3xl font-black text-yellow-700">{{ $targets->where('submission_status', 'revision')->count() }}</span>
                                </div>
                                <p class="text-xs font-medium text-gray-600">Perlu Revisi</p>
                                @if($targets->where('submission_status', 'revision')->count() > 0)
                                    <div class="mt-2 text-xs text-yellow-600 font-semibold animate-pulse">
                                        Segera perbaiki!
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Targets List with Timeline Design (Jakob's Law & Progressive Disclosure) -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Daftar Target Mingguan
                    </h3>
                    @if($targets->count() > 0)
                    <div class="flex items-center space-x-2">
                        <!-- Filter Buttons (Hick's Law - reduce choices) -->
                        <button onclick="filterTargets('all')" class="filter-btn active px-3 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-700 hover:bg-indigo-200 transition-colors">
                            Semua ({{ $targets->count() }})
                        </button>
                        @if($targets->where('submission_status', 'pending')->count() > 0)
                        <button onclick="filterTargets('pending')" class="filter-btn px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors">
                            Belum ({{ $targets->where('submission_status', 'pending')->count() }})
                        </button>
                        @endif
                        @if($targets->where('submission_status', 'revision')->count() > 0)
                        <button onclick="filterTargets('revision')" class="filter-btn px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700 hover:bg-yellow-200 transition-colors">
                            Revisi ({{ $targets->where('submission_status', 'revision')->count() }})
                        </button>
                        @endif
                    </div>
                    @endif
                </div>
                    
                @if($targets->count() > 0)
                    <div class="space-y-6" id="targets-container">
                        @foreach($targets as $index => $target)
                        @php
                            // Determine vibrant card colors based on status
                            $cardStyle = match($target->submission_status) {
                                'pending' => 'border-slate-300 bg-gradient-to-br from-slate-50 via-white to-blue-50',
                                'submitted' => 'border-blue-400 bg-gradient-to-br from-blue-50 via-sky-50 to-indigo-50',
                                'late' => 'border-orange-400 bg-gradient-to-br from-orange-50 via-amber-50 to-yellow-50',
                                'approved' => 'border-emerald-400 bg-gradient-to-br from-emerald-50 via-green-50 to-teal-50',
                                'revision' => 'border-amber-400 bg-gradient-to-br from-amber-50 via-yellow-50 to-orange-50',
                                default => 'border-gray-300 bg-gradient-to-br from-gray-50 to-white',
                            };
                            
                            $ribbonColor = match($target->submission_status) {
                                'pending' => 'from-gray-400 to-slate-500',
                                'submitted' => 'from-blue-500 to-indigo-600',
                                'late' => 'from-orange-500 to-red-500',
                                'approved' => 'from-emerald-500 to-teal-600',
                                'revision' => 'from-amber-500 to-orange-600',
                                default => 'from-gray-400 to-gray-600',
                            };
                            
                            $isUrgent = $target->deadline && $target->isPending() && $target->deadline->diffInHours(now()) <= 24;
                        @endphp
                        
                        <div class="target-card relative group animate-fade-in-up" 
                             data-status="{{ $target->submission_status }}" 
                             data-index="{{ $index }}"
                             style="animation-delay: {{ $index * 0.1 }}s">
                            
                            <!-- Card Container with Ultra Colorful Design -->
                            <div class="relative {{ $cardStyle }} rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border-2">
                                
                                <!-- Colorful Side Ribbon -->
                                <div class="absolute left-0 top-0 bottom-0 w-3 bg-gradient-to-b {{ $ribbonColor }} rounded-l-2xl"></div>
                                
                                @if($isUrgent)
                                <!-- Urgent Banner with Animation -->
                                <div class="absolute top-4 left-16 z-10 transform hover:scale-105 transition-transform">
                                    <div class="bg-gradient-to-r from-red-500 to-rose-600 text-white px-4 py-1.5 rounded-lg shadow-lg animate-pulse">
                                        <div class="flex items-center space-x-2">
                                            <span class="relative flex h-2 w-2">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                                            </span>
                                            <span class="font-bold text-xs uppercase tracking-wider">Urgent!</span>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                <!-- Fixed Week Badge -->
                                <div class="absolute top-4 right-4 z-10">
                                    <div class="bg-gradient-to-br from-purple-600 via-pink-600 to-indigo-600 text-white px-6 py-3 rounded-xl shadow-lg transform hover:scale-110 transition-all duration-300">
                                        <div class="text-center">
                                            <span class="text-xs font-bold uppercase tracking-widest opacity-90">Week</span>
                                            <span class="block text-2xl font-black">{{ $target->week_number }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="p-6">
                                    <!-- Header Section -->
                                    <div class="flex justify-between items-start mb-4 pr-20">
                                        <div class="flex-1 pr-4">
                                            <h4 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-indigo-600 transition-colors">
                                                {{ $target->title }}
                                            </h4>
                                            <p class="text-sm text-gray-600 line-clamp-2">
                                                {{ $target->description }}
                                            </p>
                                        </div>
                                        
                                        <!-- Status Badge (Von Restorff Effect) -->
                                        <div class="flex-shrink-0">
                                            @php
                                                $statusStyle = match($target->submission_status) {
                                                    'pending' => 'bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 border-gray-300',
                                                    'submitted' => 'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-700 border-blue-300',
                                                    'late' => 'bg-gradient-to-r from-orange-100 to-red-100 text-orange-700 border-orange-300',
                                                    'approved' => 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 border-green-300 ring-2 ring-green-400 ring-opacity-50',
                                                    'revision' => 'bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-700 border-yellow-300 animate-pulse',
                                                    default => 'bg-gray-100 text-gray-700 border-gray-300',
                                                };
                                                
                                                $statusIcon = match($target->submission_status) {
                                                    'pending' => 'far fa-circle',
                                                    'submitted' => 'fas fa-upload',
                                                    'late' => 'fas fa-clock',
                                                    'approved' => 'fas fa-check-circle',
                                                    'revision' => 'fas fa-edit',
                                                    default => 'fas fa-question-circle',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold {{ $statusStyle }} border transform transition-all hover:scale-110">
                                                <i class="{{ $statusIcon }} mr-2"></i>
                                                {{ $target->getStatusLabel() }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Colorful Meta Information Cards -->
                                    <div class="flex flex-wrap gap-3 mb-6">
                                        @if($target->deadline)
                                        <div class="flex items-center bg-gradient-to-r from-purple-100 to-pink-100 border border-purple-200 rounded-xl px-4 py-2">
                                            <div class="p-2 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg mr-3 shadow-md">
                                                <i class="fas fa-calendar-alt text-white"></i>
                                            </div>
                                            <div>
                                                <span class="block text-xs font-medium text-purple-600">Deadline</span>
                                                <span class="font-bold text-gray-800 {{ $target->deadline->isPast() ? 'text-red-600' : '' }}">
                                                    {{ $target->deadline->format('d M Y, H:i') }}
                                                </span>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        @if($target->deadline)
                                        <div class="flex items-center bg-gradient-to-r from-orange-100 to-yellow-100 border border-orange-200 rounded-xl px-4 py-2">
                                            <div class="p-2 bg-gradient-to-br from-orange-500 to-yellow-500 rounded-lg mr-3 shadow-md">
                                                <i class="fas fa-hourglass-half text-white animate-pulse"></i>
                                            </div>
                                            <div>
                                                <span class="block text-xs font-medium text-orange-600">Sisa Waktu</span>
                                                <span class="font-bold {{ $target->deadline->diffInHours(now()) <= 24 ? 'text-red-600 animate-pulse' : 'text-gray-800' }}">
                                                    {{ $target->deadline->isPast() ? '❌ Terlewat' : $target->deadline->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <div class="flex items-center bg-gradient-to-r from-blue-100 to-cyan-100 border border-blue-200 rounded-xl px-4 py-2">
                                            <div class="p-2 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-lg mr-3 shadow-md">
                                                <i class="fas fa-user-tie text-white"></i>
                                            </div>
                                            <div>
                                                <span class="block text-xs font-medium text-blue-600">Pembuat</span>
                                                <span class="font-bold text-gray-800">{{ $target->creator->name ?? 'System' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submission Info with Progressive Disclosure -->
                                    @if($target->isSubmitted())
                                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-4 mb-4 border border-green-200">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center mb-2">
                                                    <div class="p-2 bg-green-100 rounded-lg mr-3">
                                                        <i class="fas fa-check-circle text-green-600 text-lg"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-green-800">Submission Completed</p>
                                                        <p class="text-sm text-green-600">
                                                            {{ $target->completed_at->format('d M Y, H:i') }}
                                                            @if($target->isLate())
                                                            <span class="ml-2 px-2 py-0.5 bg-orange-100 text-orange-700 text-xs rounded-full font-medium">
                                                                <i class="fas fa-exclamation-circle mr-1"></i>Late Submission
                                                            </span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                                
                                                @if($target->submission_notes || ($target->evidence_files && count($target->evidence_files) > 0))
                                                <div class="ml-11 space-y-2">
                                                    @if($target->submission_notes)
                                                    <div class="flex items-start">
                                                        <i class="fas fa-comment-dots text-gray-400 mr-2 mt-1"></i>
                                                        <div class="text-sm text-gray-700">
                                                            <span class="font-medium">Notes:</span> {{ $target->submission_notes }}
                                                        </div>
                                                    </div>
                                                    @endif
                                                    
                                                    @if($target->evidence_files && count($target->evidence_files) > 0)
                                                    <div class="flex items-center">
                                                        <i class="fas fa-paperclip text-gray-400 mr-2"></i>
                                                        <span class="text-sm text-gray-700">
                                                            <span class="font-medium">{{ count($target->evidence_files) }}</span> file(s) attached
                                                        </span>
                                                    </div>
                                                    @elseif($target->is_checked_only)
                                                    <div class="flex items-center">
                                                        <i class="fas fa-check-square text-gray-400 mr-2"></i>
                                                        <span class="text-sm text-gray-700">Checklist only (no files)</span>
                                                    </div>
                                                    @endif
                                                </div>
                                                @endif
                                            </div>
                                            
                                            @if($target->isReviewed())
                                            <div class="ml-4 text-right">
                                                <div class="inline-flex items-center px-3 py-2 bg-indigo-100 rounded-lg">
                                                    <i class="fas fa-user-check text-indigo-600 mr-2"></i>
                                                    <div class="text-left">
                                                        <p class="text-xs text-indigo-600 font-medium">Reviewed by</p>
                                                        <p class="text-sm font-semibold text-indigo-800">{{ $target->reviewer->name ?? 'Dosen' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Actions Section (Fitts's Law - larger touch targets) -->
                                    <div class="border-t border-gray-100 pt-4 mt-4">
                                        <div class="flex flex-wrap items-center justify-between gap-3">
                                            <div class="flex flex-wrap gap-2">
                                                <!-- Primary CTA (Von Restorff Effect - make primary action stand out) -->
                                                @if($target->canAcceptSubmission())
                                                    @if($target->isPending())
                                                    <a href="{{ route('targets.submissions.submit', $target->id) }}" 
                                                       class="group relative inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-600 hover:to-emerald-700 transform hover:-translate-y-0.5 transition-all duration-200 shadow-lg hover:shadow-xl">
                                                        <span class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 rounded-xl transition-opacity"></span>
                                                        <i class="fas fa-upload mr-2 group-hover:animate-bounce"></i>
                                                        <span>Submit Target</span>
                                                    </a>
                                                    @elseif($target->submission_status === 'revision')
                                                    <a href="{{ route('targets.submissions.edit', $target->id) }}" 
                                                       class="group relative inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-semibold rounded-xl hover:from-yellow-600 hover:to-orange-600 transform hover:-translate-y-0.5 transition-all duration-200 shadow-lg hover:shadow-xl animate-pulse">
                                                        <span class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 rounded-xl transition-opacity"></span>
                                                        <i class="fas fa-edit mr-2"></i>
                                                        <span>Perbaiki Revisi</span>
                                                    </a>
                                                    @elseif($target->isSubmitted() && !$target->isReviewed())
                                                    <a href="{{ route('targets.submissions.edit', $target->id) }}" 
                                                       class="group relative inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-xl hover:from-indigo-600 hover:to-purple-700 transform hover:-translate-y-0.5 transition-all duration-200 shadow-md hover:shadow-lg">
                                                        <i class="fas fa-pen mr-2"></i>
                                                        <span>Edit Submission</span>
                                                    </a>
                                                    @endif
                                                @else
                                                    <div class="inline-flex items-center px-5 py-2.5 bg-red-50 text-red-600 font-medium rounded-xl border-2 border-red-200">
                                                        <i class="fas fa-lock mr-2"></i>
                                                        <span>Target Tertutup</span>
                                                    </div>
                                                @endif
                                                
                                                <!-- Secondary Action -->
                                                <a href="{{ route('targets.submissions.show', $target->id) }}" 
                                                   class="group inline-flex items-center px-5 py-2.5 bg-white text-gray-700 font-medium rounded-xl border-2 border-gray-200 hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-600 transform hover:-translate-y-0.5 transition-all duration-200">
                                                    <i class="fas fa-info-circle mr-2 group-hover:text-indigo-500"></i>
                                                    <span>Lihat Detail</span>
                                                </a>
                                            </div>
                                            
                                            <!-- Status Indicators (right side) -->
                                            <div class="flex items-center gap-2">
                                                @if($target->deadline && $target->isPending())
                                                    @if($target->isPastFinalDeadline())
                                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                                        <i class="fas fa-lock mr-1.5"></i>DITUTUP
                                                    </span>
                                                    @elseif($target->isClosed())
                                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                                        <i class="fas fa-lock mr-1.5"></i>CLOSED
                                                    </span>
                                                    @elseif($target->isInGracePeriod())
                                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-orange-100 text-orange-700 border border-orange-200 animate-pulse">
                                                        <i class="fas fa-hourglass-end mr-1.5"></i>GRACE PERIOD
                                                    </span>
                                                    @elseif($target->isOverdue())
                                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200 animate-pulse">
                                                        <i class="fas fa-exclamation-triangle mr-1.5"></i>OVERDUE
                                                    </span>
                                                    @elseif($target->deadline->diffInHours(now()) <= 24)
                                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-orange-100 text-orange-700 border border-orange-200 animate-pulse">
                                                        <i class="fas fa-clock mr-1.5"></i>DUE SOON
                                                    </span>
                                                    @endif
                                                @endif
                                                
                                                @if($target->isReviewed() && $target->submission_status === 'approved')
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                                    <i class="fas fa-star mr-1.5"></i>APPROVED
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State (Better UX) -->
                    <div class="flex flex-col items-center justify-center py-16 px-4">
                        <div class="w-32 h-32 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center mb-6 animate-pulse">
                            <i class="fas fa-clipboard-list text-5xl text-indigo-500"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Belum Ada Target Mingguan</h3>
                        <p class="text-gray-600 text-center max-w-md">
                            Dosen belum membuat target untuk kelompok Anda. Target akan muncul di sini setelah dosen membuat penugasan.
                        </p>
                        <div class="mt-8">
                            <a href="{{ route('dashboard') }}" 
                               class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Custom Styles -->
    <style>
        /* Animation Classes */
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slide-down {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fade-in-up 0.5s ease-out forwards;
            opacity: 0;
        }
        
        .animate-slide-down {
            animation: slide-down 0.3s ease-out;
        }
        
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        /* Line clamp utility */
        .line-clamp-2 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #667eea, #764ba2);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #5a67d8, #6b4699);
        }
        
        /* Filter button active state */
        .filter-btn.active {
            background: linear-gradient(to right, #667eea, #764ba2);
            color: white;
        }
    </style>
    
    <!-- JavaScript for Interactions -->
    <script>
        // Filter functionality
        function filterTargets(status) {
            const cards = document.querySelectorAll('.target-card');
            const buttons = document.querySelectorAll('.filter-btn');
            
            // Update button states
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            // Filter cards with animation
            cards.forEach((card, index) => {
                const cardStatus = card.dataset.status;
                
                if (status === 'all' || cardStatus === status) {
                    card.style.display = 'block';
                    card.style.animationDelay = `${index * 0.05}s`;
                    card.classList.remove('animate-fade-in-up');
                    void card.offsetWidth; // Trigger reflow
                    card.classList.add('animate-fade-in-up');
                } else {
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                }
            });
        }
        
        // Add hover sound effect (optional)
        document.querySelectorAll('a, button').forEach(element => {
            element.addEventListener('mouseenter', () => {
                // Add subtle hover feedback
                element.style.transition = 'all 0.2s ease';
            });
        });
        
        // Progressive enhancement: Add loading states
        document.querySelectorAll('a[href]').forEach(link => {
            link.addEventListener('click', function(e) {
                if (this.href && !this.href.includes('#')) {
                    const icon = this.querySelector('i');
                    if (icon) {
                        icon.classList.add('fa-spin');
                    }
                }
            });
        });
    </script>
</x-app-layout>
