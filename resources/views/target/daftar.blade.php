<x-app-layout>
    <x-slot name="header">
        <!-- Law of Proximity & Visual Hierarchy -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="space-y-1">
                <h2 class="font-bold text-2xl text-white leading-tight flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    Kelola Target Mingguan
                </h2>
                <p class="text-sm text-white/90">Monitoring dan kelola target mingguan kelompok</p>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <!-- Export PDF Button -->
                <a href="{{ route('targets.export-pdf', ['class_room_id' => request('class_room_id'), 'week_number' => request('week_number')]) }}" 
                   class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white font-semibold py-2.5 px-4 rounded-lg shadow transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export PDF
                </a>
                
                <!-- Buat Target Button -->
                @if(in_array(auth()->user()->role, ['dosen', 'admin']))
                <a href="{{ route('targets.create') }}" 
                   class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white font-semibold py-2.5 px-4 rounded-lg border border-white/30 transition-all duration-200 hover:scale-105 hover:shadow-lg group">
                    <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span>Buat Target Baru</span>
                </a>
                @endif
            </div>
        </div>
    </x-slot>

    <!-- Law of Symmetry: Balanced spacing -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Feedback Visibility - Success/Error Messages -->
            @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl animate-slide-in shadow-md">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl animate-slide-in shadow-md">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            <!-- Statistics Cards - Von Restorff Effect & Miller's Law (5 items) -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Statistik Submission
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    
                    <!-- Card 1: Total Target -->
                    <div class="group relative bg-gradient-to-br from-gray-500 to-gray-600 rounded-2xl shadow-lg hover:shadow-2xl p-6 text-white transition-all duration-300 hover:scale-105 cursor-pointer overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="relative flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-gray-100 text-xs font-medium uppercase tracking-wider">Total Target</p>
                                <p class="text-4xl font-black mt-3 mb-1 group-hover:scale-110 transition-transform duration-300">{{ $stats['total'] }}</p>
                                <p class="text-xs text-gray-100 mt-2">Total keseluruhan</p>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl group-hover:rotate-12 transition-transform duration-300">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Sudah Submit -->
                    <div class="group relative bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg hover:shadow-2xl p-6 text-white transition-all duration-300 hover:scale-105 cursor-pointer overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="relative flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-blue-100 text-xs font-medium uppercase tracking-wider">Sudah Submit</p>
                                <p class="text-4xl font-black mt-3 mb-1 group-hover:scale-110 transition-transform duration-300">{{ $stats['submitted'] }}</p>
                                <p class="text-xs text-blue-100 mt-2">Menunggu review</p>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl group-hover:rotate-12 transition-transform duration-300">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Disetujui -->
                    <div class="group relative bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-lg hover:shadow-2xl p-6 text-white transition-all duration-300 hover:scale-105 cursor-pointer overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="relative flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-green-100 text-xs font-medium uppercase tracking-wider">Disetujui</p>
                                <p class="text-4xl font-black mt-3 mb-1 group-hover:scale-110 transition-transform duration-300">{{ $stats['approved'] }}</p>
                                <p class="text-xs text-green-100 mt-2">Sudah direview</p>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl group-hover:rotate-12 transition-transform duration-300">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4: Perlu Revisi - Von Restorff Effect -->
                    <div class="group relative bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl shadow-lg hover:shadow-2xl p-6 text-white transition-all duration-300 hover:scale-105 cursor-pointer overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="relative flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-yellow-100 text-xs font-medium uppercase tracking-wider">Perlu Revisi</p>
                                <p class="text-4xl font-black mt-3 mb-1 group-hover:scale-110 transition-transform duration-300">{{ $stats['revision'] }}</p>
                                <p class="text-xs text-yellow-100 mt-2">Butuh perbaikan</p>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl group-hover:rotate-12 transition-transform duration-300">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Card 5: Belum Submit -->
                    <div class="group relative bg-gradient-to-br from-red-500 to-red-600 rounded-2xl shadow-lg hover:shadow-2xl p-6 text-white transition-all duration-300 hover:scale-105 cursor-pointer overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="relative flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-red-100 text-xs font-medium uppercase tracking-wider">Belum Submit</p>
                                <p class="text-4xl font-black mt-3 mb-1 group-hover:scale-110 transition-transform duration-300">{{ $stats['pending'] + $stats['late'] }}</p>
                                <p class="text-xs text-red-100 mt-2">Pending & terlambat</p>
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

            <!-- Progress Bar - Feedback Visibility -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <h3 class="text-sm font-bold text-gray-900">Progress Submission</h3>
                    </div>
                    <span class="text-2xl font-black bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">{{ $stats['submitted_percentage'] }}%</span>
                </div>
                <div class="relative w-full bg-gray-200 rounded-full h-6 overflow-hidden shadow-inner">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500 via-indigo-500 to-green-500 h-6 rounded-full transition-all duration-700 ease-out shadow-lg" 
                         style="width: {{ $stats['submitted_percentage'] }}%">
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-shimmer"></div>
                    </div>
                </div>
                <div class="mt-3 flex items-center justify-between text-xs">
                    <p class="text-gray-600 flex items-center gap-1">
                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-semibold text-gray-900">{{ $stats['submitted'] + $stats['approved'] + $stats['revision'] }}</span> dari <span class="font-semibold text-gray-900">{{ $stats['total'] }}</span> kelompok sudah submit
                    </p>
                    @if($stats['pending'] + $stats['late'] > 0)
                    <p class="text-red-600 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-semibold">{{ $stats['pending'] + $stats['late'] }}</span> belum submit
                    </p>
                    @endif
                </div>
            </div>

            <!-- Filters - Hick's Law: Organized choices -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <h3 class="text-sm font-bold text-gray-900">Filter Data</h3>
                </div>
                <form method="GET" action="{{ route('targets.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Class Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kelas</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                            </svg>
                            <select name="class_room_id" class="w-full pl-10 pr-4 py-2.5 rounded-lg border-2 border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200">
                                <option value="">Semua Kelas</option>
                                @foreach($classRooms as $classRoom)
                                <option value="{{ $classRoom->id }}" {{ request('class_room_id') == $classRoom->id ? 'selected' : '' }}>
                                    {{ $classRoom->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Week Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Minggu</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            <input type="number" name="week_number" min="1" placeholder="Semua"
                                   value="{{ request('week_number') }}"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-lg border-2 border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200">
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <select name="status" class="w-full pl-10 pr-4 py-2.5 rounded-lg border-2 border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Belum Dikerjakan</option>
                                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Sudah Submit</option>
                                <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Terlambat</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                <option value="revision" {{ request('status') == 'revision' ? 'selected' : '' }}>Perlu Revisi</option>
                            </select>
                        </div>
                    </div>

                    <!-- Submit Button - Fitts's Law -->
                    <div class="flex items-end">
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold py-2.5 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Targets List - Grouped by Week with Accordion -->
            <div class="space-y-4">
                @if($targetsByWeek->count() > 0)
                @foreach($targetsByWeek as $weekIndex => $week)
                @php
                    $submittedCount = $week['stats']['submitted'] + $week['stats']['approved'] + $week['stats']['revision'];
                    $totalCount = $week['stats']['total'];
                    $progressPercent = $totalCount > 0 ? round(($submittedCount / $totalCount) * 100) : 0;
                    $isPastDeadline = \Carbon\Carbon::parse($week['deadline'])->isPast();
                    $firstTarget = $week['targets']->first();
                    $classRoomId = $firstTarget->group->class_room_id ?? null;
                    // Cek apakah ada target yang masih terbuka
                    $adaTargetTerbuka = $week['targets']->contains(fn($t) => $t->is_open);
                    // Target bisa ditutup jika batas waktu belum lewat dan masih terbuka
                    $bisaDitutup = !$isPastDeadline && $adaTargetTerbuka;
                    // Target bisa dibuka jika batas waktu sudah lewat ATAU sudah ditutup manual
                    $bisaDibuka = $isPastDeadline || !$adaTargetTerbuka;
                @endphp
                <!-- Week Accordion Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" x-data="{ open: false }">
                    <!-- Week Header (Clickable) -->
                    <button @click="open = !open" 
                            class="w-full bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 px-6 py-4 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:ring-offset-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <!-- Expand/Collapse Icon -->
                                <div class="bg-white/20 backdrop-blur-sm p-2 rounded-lg">
                                    <svg class="w-5 h-5 text-white transform transition-transform duration-300" 
                                         :class="{ 'rotate-180': open }"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                                <div class="flex items-center gap-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <div class="text-left">
                                        <h3 class="text-lg font-bold text-white">Minggu {{ $week['week_number'] }}</h3>
                                        <span class="text-sm text-white/80">{{ $week['title'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-6">
                                <!-- Deadline Info -->
                                <div class="text-right hidden sm:block">
                                    <div class="text-xs text-white/70 uppercase font-medium">Deadline</div>
                                    <div class="text-sm font-semibold text-white">
                                        {{ \Carbon\Carbon::parse($week['deadline'])->format('d/m/Y H:i') }}
                                    </div>
                                    @if($isPastDeadline)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 mt-1">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        Lewat Deadline
                                    </span>
                                    @endif
                                </div>
                                <!-- Progress Badge -->
                                <div class="flex items-center gap-3">
                                    <div class="text-right">
                                        <div class="text-xs text-white/70 uppercase font-medium">Progress</div>
                                        <div class="text-lg font-bold text-white">
                                            {{ $submittedCount }}/{{ $totalCount }}
                                        </div>
                                    </div>
                                    <!-- Progress Circle -->
                                    <div class="relative w-12 h-12">
                                        <svg class="w-12 h-12 transform -rotate-90">
                                            <circle cx="24" cy="24" r="20" stroke="rgba(255,255,255,0.3)" stroke-width="4" fill="none"/>
                                            <circle cx="24" cy="24" r="20" stroke="white" stroke-width="4" fill="none"
                                                    stroke-dasharray="{{ 125.6 }}" 
                                                    stroke-dashoffset="{{ 125.6 - (125.6 * $progressPercent / 100) }}"
                                                    stroke-linecap="round"/>
                                        </svg>
                                        <span class="absolute inset-0 flex items-center justify-center text-xs font-bold text-white">
                                            {{ $progressPercent }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </button>
                    
                    <!-- Week Action Buttons -->
                    @if(in_array(auth()->user()->role, ['dosen', 'admin']) && $classRoomId)
                    <div class="bg-gray-50 border-b border-gray-200 px-6 py-3 flex items-center justify-end gap-2">
                        <!-- Edit Week -->
                        <a href="{{ route('targets.week.edit', [$week['week_number'], $classRoomId]) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-all duration-200 hover:scale-105">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Minggu
                        </a>
                        
                        @if($bisaDitutup)
                        <!-- Tutup Minggu (jika batas waktu belum lewat dan masih terbuka) -->
                        <form action="{{ route('targets.week.close', [$week['week_number'], $classRoomId]) }}" method="POST" class="inline" id="close-week-form-{{ $week['week_number'] }}">
                            @csrf
                            <button type="button" onclick="closeWeek({{ $week['week_number'] }}, '{{ $week['title'] }}')"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-all duration-200 hover:scale-105">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Tutup Target
                            </button>
                        </form>
                        @endif
                        
                        @if($bisaDibuka)
                        <!-- Buka Minggu (jika batas waktu sudah lewat ATAU sudah ditutup manual) -->
                        <form action="{{ route('targets.week.reopen', [$week['week_number'], $classRoomId]) }}" method="POST" class="inline" id="reopen-week-form-{{ $week['week_number'] }}">
                            @csrf
                            <button type="button" onclick="reopenWeek({{ $week['week_number'] }}, '{{ $week['title'] }}')"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-cyan-500 hover:bg-cyan-600 text-white text-sm font-medium rounded-lg transition-all duration-200 hover:scale-105">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                </svg>
                                Buka Target
                            </button>
                        </form>
                        @endif
                        
                        <!-- Delete Week -->
                        <form action="{{ route('targets.week.destroy', [$week['week_number'], $classRoomId]) }}" method="POST" class="inline" id="delete-week-form-{{ $week['week_number'] }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="deleteWeek({{ $week['week_number'] }}, '{{ $week['title'] }}', {{ $totalCount }})"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-all duration-200 hover:scale-105">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus Minggu
                            </button>
                        </form>
                    </div>
                    @endif

                    <!-- Collapsible Content -->
                    <div x-show="open" 
                         x-collapse
                         x-cloak>
                        <!-- Targets Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelompok</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($week['targets'] as $target)
                                    @php
                                        $rowClass = match($target->submission_status) {
                                            'submitted' => 'bg-primary-50 hover:bg-primary-100 border-l-4 border-primary-500',
                                            'approved' => 'bg-green-50 hover:bg-green-100 border-l-4 border-green-500',
                                            'revision' => 'bg-yellow-50 hover:bg-yellow-100 border-l-4 border-yellow-500',
                                            'late' => 'bg-orange-50 hover:bg-orange-100 border-l-4 border-orange-500',
                                            default => 'hover:bg-gray-50',
                                        };
                                    @endphp
                                    <tr class="{{ $rowClass }}">
                                        <td class="px-6 py-4">
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">{{ $target->group->name }}</div>
                                                @if($target->completedByUser)
                                                    <div class="text-xs text-primary-600 mt-1 flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                        </svg>
                                                        {{ $target->completedByUser->name }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $target->group->classRoom->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $color = match($target->submission_status) {
                                                    'pending' => 'bg-gray-100 text-gray-800',
                                                    'submitted' => 'bg-blue-100 text-blue-800',
                                                    'late' => 'bg-orange-100 text-orange-800',
                                                    'approved' => 'bg-green-100 text-green-800',
                                                    'revision' => 'bg-yellow-100 text-yellow-800',
                                                    default => 'bg-gray-100 text-gray-800',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                                {{ $target->getStatusLabel() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($target->completed_at && in_array($target->submission_status, ['submitted', 'approved', 'revision', 'late']))
                                                <div class="text-sm text-gray-900">
                                                    {{ $target->completed_at->format('d/m/Y') }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $target->completed_at->format('H:i') }}
                                                </div>
                                                <div class="text-xs text-gray-400 mt-1">
                                                    {{ $target->completed_at->diffForHumans() }}
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                <!-- Empty State -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12">
                    <div class="text-center">
                        <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada target mingguan</h3>
                        @if(in_array(auth()->user()->role, ['dosen', 'admin']))
                            <p class="mt-2 text-sm text-gray-500">Silakan buat target mingguan pertama untuk kelompok Anda</p>
                            <div class="mt-6">
                                <a href="{{ route('targets.create') }}" 
                                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Buat Target Pertama
                                </a>
                            </div>
                        @else
                            <p class="mt-2 text-sm text-gray-500">Target mingguan akan dibuat oleh dosen pengampu kelas</p>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function deleteTarget(targetId, targetTitle, groupName) {
            const form = document.getElementById('delete-form-' + targetId);
            
            confirmDelete(
                'Hapus Target?',
                `Apakah Anda yakin ingin menghapus target ini?<br><strong>Target:</strong> ${targetTitle}<br><strong>Kelompok:</strong> ${groupName}<br><small class="text-gray-500">Tindakan ini tidak dapat dibatalkan.</small>`,
                form
            );
        }

        function reopenTarget(targetId, targetTitle) {
            const form = document.getElementById('reopen-form-' + targetId);
            
            confirmAction(
                'Buka Kembali Target?',
                `Yakin ingin membuka kembali target <strong>"${targetTitle}"</strong>?<br><small class="text-gray-500">Mahasiswa akan dapat mensubmit target yang sudah tertutup.</small>`,
                '<i class="fas fa-unlock mr-2"></i>Ya, Buka Kembali!',
                '#0891b2'
            ).then((result) => {
                if (result.isConfirmed) {
                    showLoading('Membuka Target...', 'Mohon tunggu sebentar');
                    form.submit();
                }
            });
        }

        function closeTarget(targetId, targetTitle) {
            const form = document.getElementById('close-form-' + targetId);
            
            confirmAction(
                'Tutup Target?',
                `Yakin ingin menutup target <strong>"${targetTitle}"</strong>?<br><small class="text-gray-500">Mahasiswa tidak akan dapat mensubmit target ini.</small>`,
                '<i class="fas fa-lock mr-2"></i>Ya, Tutup!',
                '#6b7280'
            ).then((result) => {
                if (result.isConfirmed) {
                    showLoading('Menutup Target...', 'Mohon tunggu sebentar');
                    form.submit();
                }
            });
        }

        function deleteWeek(weekNumber, weekTitle, totalCount) {
            const form = document.getElementById('delete-week-form-' + weekNumber);
            
            confirmDelete(
                'Hapus Semua Target Minggu Ini?',
                `Apakah Anda yakin ingin menghapus semua target minggu ${weekNumber}?<br><strong>Target:</strong> ${weekTitle}<br><strong>Jumlah:</strong> ${totalCount} kelompok<br><small class="text-gray-500">Tindakan ini tidak dapat dibatalkan.</small>`,
                form
            );
        }

        function reopenWeek(weekNumber, weekTitle) {
            const form = document.getElementById('reopen-week-form-' + weekNumber);
            
            confirmAction(
                'Buka Target?',
                `Yakin ingin membuka target minggu ${weekNumber}?`,
                '<i class="fas fa-unlock mr-2"></i>Ya, Buka',
                '#0891b2'
            ).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        function closeWeek(weekNumber, weekTitle) {
            const form = document.getElementById('close-week-form-' + weekNumber);
            
            confirmAction(
                'Tutup Target?',
                `Yakin ingin menutup target minggu ${weekNumber}?`,
                '<i class="fas fa-lock mr-2"></i>Ya, Tutup',
                '#6b7280'
            ).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>
