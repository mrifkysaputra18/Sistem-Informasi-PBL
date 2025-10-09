<x-app-layout>
    <x-slot name="header">
        <!-- Law of Proximity & Visual Hierarchy -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="space-y-1">
                <h2 class="font-bold text-2xl text-white leading-tight flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Dashboard Dosen
                </h2>
                <p class="text-sm text-white/90">Selamat datang kembali, <span class="font-semibold">{{ auth()->user()->name }}</span> 👋</p>
            </div>
            
            <!-- Fitts's Law: Larger, accessible action buttons -->
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('classrooms.index') }}" 
                   class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white font-semibold py-2.5 px-4 rounded-lg border border-white/30 transition-all duration-200 hover:scale-105 hover:shadow-lg group">
                    <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span>Kelas</span>
                </a>
                <a href="{{ route('scores.create') }}" 
                   class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white font-semibold py-2.5 px-4 rounded-lg border border-white/30 transition-all duration-200 hover:scale-105 hover:shadow-lg group">
                    <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    <span>Input Nilai</span>
                </a>
                <a href="{{ route('targets.index') }}" 
                   class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white font-semibold py-2.5 px-4 rounded-lg border border-white/30 transition-all duration-200 hover:scale-105 hover:shadow-lg group">
                    <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    <span>Target</span>
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Law of Symmetry: Balanced spacing -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Statistics Cards - Law of Common Region & Von Restorff Effect -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Statistik Utama
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    
                    <!-- Card 1: Total Kelas -->
                    <div class="group relative bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg hover:shadow-2xl p-6 text-white transition-all duration-300 hover:scale-105 cursor-pointer overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="relative flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-blue-100 text-xs font-medium uppercase tracking-wider">Total Kelas</p>
                                <p class="text-4xl font-black mt-3 mb-1 group-hover:scale-110 transition-transform duration-300">{{ $stats['totalClassRooms'] }}</p>
                                <p class="text-xs text-blue-100 mt-2">Kelas yang diampu</p>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl group-hover:rotate-12 transition-transform duration-300">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Total Kelompok -->
                    <div class="group relative bg-gradient-to-br from-red-500 to-red-600 rounded-2xl shadow-lg hover:shadow-2xl p-6 text-white transition-all duration-300 hover:scale-105 cursor-pointer overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="relative flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-red-100 text-xs font-medium uppercase tracking-wider">Total Kelompok</p>
                                <p class="text-4xl font-black mt-3 mb-1 group-hover:scale-110 transition-transform duration-300">{{ $stats['totalGroups'] }}</p>
                                <p class="text-xs text-red-100 mt-2">Kelompok aktif</p>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl group-hover:rotate-12 transition-transform duration-300">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Nilai Tersimpan -->
                    <div class="group relative bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-lg hover:shadow-2xl p-6 text-white transition-all duration-300 hover:scale-105 cursor-pointer overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="relative flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-green-100 text-xs font-medium uppercase tracking-wider">Nilai Tersimpan</p>
                                <p class="text-4xl font-black mt-3 mb-1 group-hover:scale-110 transition-transform duration-300">{{ $stats['totalScores'] }}</p>
                                <p class="text-xs text-green-100 mt-2">Total penilaian</p>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl group-hover:rotate-12 transition-transform duration-300">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4: Perlu Review - Von Restorff Effect -->
                    <div class="group relative bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl shadow-lg hover:shadow-2xl p-6 text-white transition-all duration-300 hover:scale-105 cursor-pointer overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="relative flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-orange-100 text-xs font-medium uppercase tracking-wider">Perlu Review</p>
                                <p class="text-4xl font-black mt-3 mb-1 group-hover:scale-110 transition-transform duration-300">{{ $stats['pendingReviews'] }}</p>
                                <p class="text-xs text-orange-100 mt-2">Menunggu review</p>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl group-hover:rotate-12 transition-transform duration-300">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Target Mingguan Stats -->
                <h3 class="text-lg font-semibold text-gray-900 mb-4 mt-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    Target Mingguan
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    <!-- Target 1: Total -->
                    <div class="group relative bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl shadow-lg hover:shadow-2xl p-6 text-white transition-all duration-300 hover:scale-105 cursor-pointer overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="relative flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-cyan-100 text-xs font-medium uppercase tracking-wider">Total Target</p>
                                <p class="text-4xl font-black mt-3 mb-1 group-hover:scale-110 transition-transform duration-300">{{ $stats['totalTargets'] }}</p>
                                <p class="text-xs text-cyan-100 mt-2">Target dibuat</p>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl group-hover:rotate-12 transition-transform duration-300">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Target 2: Selesai -->
                    <div class="group relative bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl shadow-lg hover:shadow-2xl p-6 text-white transition-all duration-300 hover:scale-105 cursor-pointer overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="relative flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-teal-100 text-xs font-medium uppercase tracking-wider">Target Selesai</p>
                                <p class="text-4xl font-black mt-3 mb-1 group-hover:scale-110 transition-transform duration-300">{{ $stats['completedTargets'] }}</p>
                                <p class="text-xs text-teal-100 mt-2">Sudah tercapai</p>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl group-hover:rotate-12 transition-transform duration-300">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Target 3: Tingkat -->
                    <div class="group relative bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl shadow-lg hover:shadow-2xl p-6 text-white transition-all duration-300 hover:scale-105 cursor-pointer overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="relative flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-indigo-100 text-xs font-medium uppercase tracking-wider">Tingkat Selesai</p>
                                <p class="text-4xl font-black mt-3 mb-1 group-hover:scale-110 transition-transform duration-300">{{ $stats['completionRate'] }}%</p>
                                <p class="text-xs text-indigo-100 mt-2">Completion rate</p>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl group-hover:rotate-12 transition-transform duration-300">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Target 4: Pending -->
                    <div class="group relative bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg hover:shadow-2xl p-6 text-white transition-all duration-300 hover:scale-105 cursor-pointer overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="relative flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-purple-100 text-xs font-medium uppercase tracking-wider">Target Pending</p>
                                <p class="text-4xl font-black mt-3 mb-1 group-hover:scale-110 transition-transform duration-300">{{ $stats['pendingTargets'] }}</p>
                                <p class="text-xs text-purple-100 mt-2">Belum selesai</p>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl group-hover:rotate-12 transition-transform duration-300">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content - Law of Proximity & Progressive Disclosure -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Classes with Groups -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 transition-all duration-300 hover:shadow-xl">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-blue-200">
                        <h3 class="font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                            </svg>
                            Kelas & Kelompok
                        </h3>
                        <p class="text-xs text-gray-600 mt-1">Kelas yang Anda ampu</p>
                    </div>
                    <div class="p-6 max-h-96 overflow-y-auto">
                        @if($classRooms->count() > 0)
                            <div class="space-y-3">
                                @foreach($classRooms as $classRoom)
                                <!-- Fitts's Law: Larger clickable areas -->
                                <div class="group/item p-4 bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center gap-3 flex-1 min-w-0">
                                            <div class="bg-blue-100 p-2.5 rounded-lg group-hover/item:scale-110 group-hover/item:bg-blue-200 transition-all duration-200">
                                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-semibold text-gray-900 truncate">{{ $classRoom->name }}</p>
                                                <p class="text-xs text-gray-600 flex items-center gap-1 mt-1">
                                                    @if($classRoom->subject)
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                                    </svg>
                                                    <span class="truncate">{{ $classRoom->subject->name }}</span>
                                                    <span class="text-gray-400">•</span>
                                                    @endif
                                                    <span class="truncate">{{ $classRoom->program_studi }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <span class="ml-3 flex-shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                            </svg>
                                            {{ $classRoom->groups_count }} Kelompok
                                        </span>
                                    </div>
                                    <a href="{{ url('/classrooms/' . $classRoom->id) }}" 
                                       class="inline-flex items-center gap-1.5 text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors group/link">
                                        <span>Lihat Detail</span>
                                        <svg class="w-4 h-4 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                            <div class="mt-4 text-center">
                                <a href="{{ route('classrooms.index') }}" 
                                   class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 text-sm font-semibold transition-colors group">
                                    <span>Lihat Semua Kelas</span>
                                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </a>
                            </div>
                        @else
                            <!-- Feedback Visibility - Empty state -->
                            <div class="text-center py-12">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                    </svg>
                                </div>
                                <p class="text-gray-900 font-semibold mb-1">Belum Ada Kelas</p>
                                <p class="text-sm text-gray-600">Belum ada kelas yang diampu</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Progress to Review - Von Restorff Effect -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 transition-all duration-300 hover:shadow-xl">
                    <div class="bg-gradient-to-r from-orange-50 to-amber-50 px-6 py-4 border-b border-orange-200">
                        <h3 class="font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            Progress Perlu Review
                        </h3>
                        <p class="text-xs text-gray-600 mt-1">Progress yang menunggu review Anda</p>
                    </div>
                    <div class="p-6 max-h-96 overflow-y-auto">
                        @if($progressToReview->count() > 0)
                            <div class="space-y-3">
                                @foreach($progressToReview as $progress)
                                <!-- Law of Common Region -->
                                <div class="group/item p-4 bg-gradient-to-r from-orange-50 to-amber-50 rounded-xl border border-orange-200 hover:border-orange-300 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-start gap-3 mb-3">
                                        <div class="bg-orange-100 p-2.5 rounded-lg group-hover/item:scale-110 transition-transform duration-200 flex-shrink-0">
                                            <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-gray-900">{{ $progress->title }}</p>
                                            <p class="text-xs text-gray-600 mt-1 flex items-center gap-1">
                                                @if($progress->group)
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                                </svg>
                                                <span class="truncate">{{ $progress->group->name }}</span>
                                                @if($progress->group->classRoom)
                                                <span class="text-gray-400">•</span>
                                                <span class="truncate">{{ $progress->group->classRoom->name }}</span>
                                                @endif
                                                @endif
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                                <span>Minggu {{ $progress->week_number }}</span>
                                                <span class="text-gray-400">•</span>
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                </svg>
                                                <span>{{ $progress->submitted_at ? $progress->submitted_at->diffForHumans() : '-' }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <!-- Fitts's Law: Adequate button sizes -->
                                    <div class="flex gap-2">
                                        <button class="flex-1 inline-flex items-center justify-center gap-1.5 bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all duration-200 hover:scale-105 hover:shadow-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Review
                                        </button>
                                        <button class="inline-flex items-center justify-center gap-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all duration-200 hover:scale-105 hover:shadow-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Detail
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Feedback Visibility - Success state -->
                            <div class="text-center py-12">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <p class="text-gray-900 font-semibold mb-1">Semua Sudah Direview!</p>
                                <p class="text-sm text-gray-600">Tidak ada progress yang perlu direview</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Weekly Targets Section - Jakob's Law: Familiar table pattern -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 mt-6">
                <div class="bg-gradient-to-r from-cyan-50 to-blue-50 px-6 py-4 border-b border-cyan-200 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                            Target Mingguan Terbaru
                        </h3>
                        <p class="text-xs text-gray-600 mt-1">10 target terbaru</p>
                    </div>
                    <a href="{{ route('targets.index') }}" 
                       class="inline-flex items-center gap-1.5 text-cyan-600 hover:text-cyan-800 text-sm font-semibold transition-colors group">
                        <span>Lihat Semua</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                </div>
                <div class="p-6">
                    @if($recentTargets->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-gray-50 to-slate-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider w-12">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Kelompok
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Kelas
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider w-20">
                                            Minggu
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Target
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Waktu
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentTargets as $target)
                                    <tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-slate-50 transition-colors {{ $target->is_completed ? 'bg-green-50/50' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($target->is_completed)
                                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            @else
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <div class="bg-red-100 p-2 rounded-lg">
                                                    <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                                    </svg>
                                                </div>
                                                <span class="font-semibold text-gray-900">
                                                    @if($target->group)
                                                    {{ $target->group->name }}
                                                    @else
                                                    -
                                                    @endif
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-600 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                                </svg>
                                                @if($target->group && $target->group->classRoom)
                                                {{ $target->group->classRoom->name }}
                                                @else
                                                -
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-indigo-100 text-indigo-800 border border-indigo-200">
                                                Minggu {{ $target->week_number }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="font-semibold text-gray-900 {{ $target->is_completed ? 'line-through text-gray-500' : '' }}">
                                                {{ Str::limit($target->title, 50) }}
                                            </p>
                                            @if($target->description)
                                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($target->description, 60) }}</p>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($target->completed_at)
                                                <div class="flex items-center gap-1.5 text-green-600">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <div>
                                                        <p class="text-sm font-medium">Selesai</p>
                                                        <p class="text-xs text-gray-500">{{ $target->completed_at->diffForHumans() }}</p>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="flex items-center gap-1.5">
                                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <div>
                                                        <p class="text-sm text-gray-600">Dibuat</p>
                                                        <p class="text-xs text-gray-500">{{ $target->created_at->diffForHumans() }}</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <!-- Feedback Visibility - Empty state -->
                        <div class="text-center py-12">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                            </div>
                            <p class="text-gray-900 font-semibold mb-1">Belum Ada Target</p>
                            <p class="text-sm text-gray-600">Belum ada target mingguan yang dibuat</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions - Hick's Law (limited choices) & Jakob's Law (familiar patterns) -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 mt-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Aksi Cepat
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Action 1 - Fitts's Law: Large, accessible -->
                    <a href="{{ route('scores.create') }}" 
                       class="group relative bg-white rounded-2xl shadow-lg p-6 hover:shadow-2xl transition-all duration-300 hover:scale-105 border border-gray-100 overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-green-100 to-transparent rounded-full -mr-16 -mt-16 opacity-50"></div>
                        <div class="relative flex items-center gap-4">
                            <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-4 rounded-xl group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-lg">
                                <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900 mb-1 group-hover:text-green-600 transition-colors">Input Nilai</h3>
                                <p class="text-sm text-gray-600">Berikan nilai kelompok</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>

                    <!-- Action 2 -->
                    <a href="{{ route('scores.index') }}" 
                       class="group relative bg-white rounded-2xl shadow-lg p-6 hover:shadow-2xl transition-all duration-300 hover:scale-105 border border-gray-100 overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-yellow-100 to-transparent rounded-full -mr-16 -mt-16 opacity-50"></div>
                        <div class="relative flex items-center gap-4">
                            <div class="bg-gradient-to-br from-yellow-500 to-orange-600 p-4 rounded-xl group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-lg">
                                <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900 mb-1 group-hover:text-yellow-600 transition-colors">Lihat Ranking</h3>
                                <p class="text-sm text-gray-600">Monitor peringkat</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-yellow-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>

                    <!-- Action 3 -->
                    <a href="{{ route('groups.index') }}" 
                       class="group relative bg-white rounded-2xl shadow-lg p-6 hover:shadow-2xl transition-all duration-300 hover:scale-105 border border-gray-100 overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-red-100 to-transparent rounded-full -mr-16 -mt-16 opacity-50"></div>
                        <div class="relative flex items-center gap-4">
                            <div class="bg-gradient-to-br from-red-500 to-red-600 p-4 rounded-xl group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-lg">
                                <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900 mb-1 group-hover:text-red-600 transition-colors">Lihat Kelompok</h3>
                                <p class="text-sm text-gray-600">Monitor kelompok</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-red-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>


