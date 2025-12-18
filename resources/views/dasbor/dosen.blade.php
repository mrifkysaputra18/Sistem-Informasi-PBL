<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-white leading-tight">
                    Dashboard Dosen
                </h2>
                <p class="text-sm text-white/90">Selamat datang kembali, <span class="font-semibold">{{ auth()->user()->name }}</span> 👋</p>
            </div>
            
            <!-- Quick Actions -->
            <!-- Quick Actions -->
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('scores.create') }}" 
                   class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors border border-white/20 backdrop-blur-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Nilai Kelompok
                </a>
                <a href="{{ route('nilai-rubrik.index') }}" 
                   class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors border border-white/20 backdrop-blur-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Nilai Mata Kuliah
                </a>
                <a href="{{ route('targets.create') }}" 
                   class="inline-flex items-center gap-2 bg-white text-primary-700 hover:bg-gray-50 text-sm font-bold py-2 px-4 rounded-lg transition-all shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Target
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Key Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Stat 1: Perlu Review - Orange Bottom Border -->
                <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all" style="border: 1px solid #d1d5db; border-bottom: 4px solid #f97316;">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">PERLU REVIEW</p>
                            <h3 class="text-3xl font-black text-gray-800">{{ $stats['pendingReviews'] }}</h3>
                            <p class="text-xs text-gray-500 mt-1">Submission menunggu</p>
                        </div>
                        <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #ffedd5;">
                            <i class="fa-solid fa-clock text-xl" style="color: #f97316;"></i>
                        </div>
                    </div>
                </div>

                <!-- Stat 2: Kelas Aktif - Blue Bottom Border -->
                <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all" style="border: 1px solid #d1d5db; border-bottom: 4px solid #2563eb;">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">KELAS AKTIF</p>
                            <h3 class="text-3xl font-black text-gray-800">{{ $stats['totalClassRooms'] }}</h3>
                            <p class="text-xs text-gray-500 mt-1">Kelas aktif semester ini</p>
                        </div>
                        <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #dbeafe;">
                            <i class="fa-solid fa-school text-xl" style="color: #2563eb;"></i>
                        </div>
                    </div>
                </div>

                <!-- Stat 3: Total Kelompok - Indigo Bottom Border -->
                <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all" style="border: 1px solid #d1d5db; border-bottom: 4px solid #6366f1;">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">TOTAL KELOMPOK</p>
                            <h3 class="text-3xl font-black text-gray-800">{{ $stats['totalGroups'] }}</h3>
                            <p class="text-xs text-gray-500 mt-1">Kelompok dibimbing</p>
                        </div>
                        <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #eef2ff;">
                            <i class="fa-solid fa-users text-xl" style="color: #6366f1;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <!-- MAIN COLUMN: Review Queue -->
                <div class="lg:col-span-2">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Target Review</h3>
                            <p class="text-sm text-gray-500">Daftar submission yang perlu diperiksa</p>
                        </div>
                        @if($stats['pendingReviews'] > 5)
                        <a href="{{ route('target-reviews.index') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 hover:underline">
                            Lihat Semua
                        </a>
                        @endif
                    </div>

                    @if($progressToReview->count() > 0)
                        <div class="space-y-4">
                            @foreach($progressToReview as $progress)
                            <div class="bg-white rounded-xl border border-gray-300 shadow-sm hover:border-primary-500 hover:shadow-md transition-all duration-200 overflow-hidden relative"> 
                                <div class="p-5 flex flex-col sm:flex-row gap-5 items-start sm:items-center justify-between">
                                    <div class="flex items-start gap-4">
                                        <!-- Icon Box -->
                                        <div class="w-10 h-10 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center flex-shrink-0 border border-orange-200">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        
                                        <div>
                                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                                <span class="px-2 py-0.5 rounded text-xs font-bold bg-gray-100 text-gray-700 border border-gray-300">
                                                    {{ $progress->group->classRoom->name ?? 'N/A' }}
                                                </span>
                                                <span class="text-xs font-medium text-orange-600 bg-orange-50 px-2 py-0.5 rounded border border-orange-200">
                                                    Minggu {{ $progress->week_number }}
                                                </span>
                                            </div>
                                            <h4 class="font-bold text-gray-900 text-base">
                                                {{ $progress->group->name }}
                                            </h4>
                                            <p class="text-sm text-gray-500">{{ $progress->title }}</p>
                                        </div>
                                    </div>

                                    <div class="flex-shrink-0 self-end sm:self-center">
                                        <a href="{{ route('target-reviews.show', $progress) }}" 
                                           class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors shadow-sm">
                                            <span>Review</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="bg-white rounded-xl border border-dashed border-gray-300 p-8 text-center">
                            <h3 class="font-bold text-gray-900">Semua Beres!</h3>
                            <p class="text-sm text-gray-500 mt-1">Tidak ada submission waiting.</p>
                        </div>
                    @endif
                </div>

                <!-- SIDEBAR: Class Management -->
                <div class="space-y-6">
                    <!-- Class List Card -->
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden" style="border: 1px solid #d1d5db; border-bottom: 4px solid #2563eb;">
                        <div class="px-5 py-4 flex items-center justify-between" style="border-bottom: 1px solid #e5e7eb;">
                            <h3 class="font-bold text-gray-900 text-sm">Kelas Aktif</h3>
                            <a href="{{ route('classrooms.index') }}" class="text-xs font-medium text-blue-600 hover:text-blue-700">Kelola</a>
                        </div>
                        
                        @if($classRooms->count() > 0)
                            <div class="divide-y divide-gray-100">
                                @foreach($classRooms->take(5) as $classRoom)
                                <a href="{{ url('/classrooms/' . $classRoom->id) }}" class="block px-5 py-3 hover:bg-gray-50 transition-colors group">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs border border-blue-200">
                                                {{ substr($classRoom->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900 text-sm group-hover:text-blue-600">{{ $classRoom->name }}</p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $classRoom->groups_count }} Kelompok
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                            @if($classRooms->count() > 5)
                            <div class="p-2 bg-gray-50 text-center border-t border-gray-200">
                                <a href="{{ route('classrooms.index') }}" class="text-xs font-semibold text-gray-500 hover:text-blue-600">
                                    + {{ $classRooms->count() - 5 }} lainnya
                                </a>
                            </div>
                            @endif
                        @else
                            <div class="p-8 text-center">
                                <p class="text-gray-500 text-sm">Belum ada kelas yang diampu.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Shortcut Card -->
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden" style="border: 1px solid #d1d5db; border-bottom: 4px solid #6366f1;">
                         <div class="px-5 py-4" style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <h4 class="font-bold text-gray-800 text-sm">Pintasan</h4>
                        </div>
                        <div class="p-3 space-y-1">
                            <a href="{{ route('scores.index') }}" class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg transition-colors group">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background-color: #eef2ff;">
                                    <i class="fa-solid fa-chart-bar" style="color: #6366f1;"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Rekap Nilai</span>
                            </a>
                            
                            <a href="{{ route('groups.index') }}" class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg transition-colors group">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background-color: #d1fae5;">
                                    <i class="fa-solid fa-users" style="color: #10b981;"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Daftar Kelompok</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
