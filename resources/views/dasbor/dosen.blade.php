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
            <div class="flex gap-3">
                <a href="{{ route('scores.create') }}" 
                   class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors border border-white/20 backdrop-blur-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2-2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Input Nilai
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

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Key Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <!-- Stat 1: Perlu Review -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-all">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-orange-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="relative flex justify-between items-start">
                        <div>
                            <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Perlu Review</p>
                            <h3 class="text-4xl font-black text-gray-900 mt-2">{{ $stats['pendingReviews'] }}</h3>
                            <p class="text-sm text-gray-500 mt-1">Submission menunggu</p>
                        </div>
                        <div class="p-3 bg-orange-100 rounded-xl text-orange-600 shadow-sm">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Stat 2: Kelas Diampu -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-all">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="relative flex justify-between items-start">
                        <div>
                            <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Kelas Diampu</p>
                            <h3 class="text-4xl font-black text-gray-900 mt-2">{{ $stats['totalClassRooms'] }}</h3>
                            <p class="text-sm text-gray-500 mt-1">Kelas aktif semester ini</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-xl text-blue-600 shadow-sm">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Stat 3: Total Kelompok -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-all">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="relative flex justify-between items-start">
                        <div>
                            <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Kelompok</p>
                            <h3 class="text-4xl font-black text-gray-900 mt-2">{{ $stats['totalGroups'] }}</h3>
                            <p class="text-sm text-gray-500 mt-1">Kelompok dibimbing</p>
                        </div>
                        <div class="p-3 bg-indigo-100 rounded-xl text-indigo-600 shadow-sm">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <!-- MAIN COLUMN: Review Queue -->
                <div class="lg:col-span-2">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Queue Review</h3>
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
                            <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:border-primary-500 hover:shadow-md transition-all duration-200 overflow-hidden relative"> 
                                <div class="p-5 flex flex-col sm:flex-row gap-5 items-start sm:items-center justify-between">
                                    <div class="flex items-start gap-4">
                                        <!-- Icon Box -->
                                        <div class="w-10 h-10 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center flex-shrink-0 border border-orange-100">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        
                                        <div>
                                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                                <span class="px-2 py-0.5 rounded text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">
                                                    {{ $progress->group->classRoom->name ?? 'N/A' }}
                                                </span>
                                                <span class="text-xs font-medium text-orange-600 bg-orange-50 px-2 py-0.5 rounded border border-orange-100">
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
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="font-bold text-gray-900 text-sm">Kelas Aktif</h3>
                            <a href="{{ route('classrooms.index') }}" class="text-xs font-medium text-blue-600 hover:text-blue-700">Kelola</a>
                        </div>
                        
                        @if($classRooms->count() > 0)
                            <div class="divide-y divide-gray-50">
                                @foreach($classRooms->take(5) as $classRoom)
                                <a href="{{ url('/classrooms/' . $classRoom->id) }}" class="block px-5 py-3 hover:bg-gray-50 transition-colors group">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs border border-blue-100">
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
                            <div class="p-2 bg-gray-50 text-center border-t border-gray-100">
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

                    <!-- Shortcut Card (Clean Light) -->
                    <div class="bg-white rounded-xl shadow-sm border border-blue-100 overflow-hidden">
                         <div class="px-5 py-4 bg-blue-50 border-b border-blue-100">
                            <h4 class="font-bold text-blue-800 text-sm">Pintasan</h4>
                        </div>
                        <div class="p-2">
                            <a href="{{ route('scores.index') }}" class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg transition-colors group">
                                <div class="w-8 h-8 rounded bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0 group-hover:bg-indigo-100 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Rekap Nilai</span>
                            </a>
                            
                            <a href="{{ route('groups.index') }}" class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg transition-colors group">
                                <div class="w-8 h-8 rounded bg-teal-50 text-teal-600 flex items-center justify-center flex-shrink-0 group-hover:bg-teal-100 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
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
