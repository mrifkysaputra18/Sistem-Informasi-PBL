<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <a href="{{ route('academic-years.index') }}" 
                   class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h2 class="font-semibold text-xl text-white leading-tight">
                        Detail Tahun Ajaran
                    </h2>
                    <p class="text-sm text-gray-600">{{ $academicYear->name }} ({{ $academicYear->code }})</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('academic-years.edit', $academicYear) }}" 
                   class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Status Banner -->
            @if($academicYear->is_active)
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span class="font-semibold">Tahun Ajaran Aktif</span>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Info -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Informasi Tahun Ajaran</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Nama Tahun Ajaran</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ $academicYear->name }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Kode</label>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary-100 text-primary-800">
                                        {{ $academicYear->code }}
                                    </span>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Mulai</label>
                                    <p class="text-sm text-gray-900">
                                        <i class="fas fa-calendar-start text-gray-400 mr-1"></i>
                                        {{ $academicYear->start_date->format('d M Y') }}
                                    </p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Selesai</label>
                                    <p class="text-sm text-gray-900">
                                        <i class="fas fa-calendar-end text-gray-400 mr-1"></i>
                                        {{ $academicYear->end_date->format('d M Y') }}
                                    </p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Durasi</label>
                                    <p class="text-sm text-gray-900">
                                        <i class="fas fa-clock text-gray-400 mr-1"></i>
                                        {{ $academicYear->start_date->diffInDays($academicYear->end_date) }} hari
                                    </p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                                    @if($academicYear->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Aktif
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-pause-circle mr-1"></i>Tidak Aktif
                                    </span>
                                    @endif
                                </div>
                            </div>
                            
                            @if($academicYear->description)
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-500 mb-2">Deskripsi</label>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-700">{{ $academicYear->description }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Semester List -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Semester</h3>
                            
                            @if($academicYear->semesters->count() > 0)
                            <div class="space-y-3">
                                @foreach($academicYear->semesters->sortBy('number') as $semester)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-sm font-semibold text-primary-600">{{ $semester->number }}</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $semester->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $semester->start_date->format('d M') }} - {{ $semester->end_date->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                    @if($semester->is_active)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Aktif
                                    </span>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            
                            <div class="mt-4">
                                <a href="{{ route('semesters.create') }}?academic_year_id={{ $academicYear->id }}" 
                                   class="inline-flex items-center text-sm text-primary-600 hover:text-primary-800">
                                    <i class="fas fa-plus mr-1"></i>Tambah Semester
                                </a>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <div class="text-gray-400 mb-2"><i class="fas fa-graduation-cap text-2xl"></i></div>
                                <p class="text-sm text-gray-600 mb-3">Belum ada semester</p>
                                <a href="{{ route('semesters.create') }}?academic_year_id={{ $academicYear->id }}" 
                                   class="inline-flex items-center text-sm text-primary-600 hover:text-primary-800">
                                    <i class="fas fa-plus mr-1"></i>Tambah Semester Pertama
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Statistik</h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Total Semester</span>
                                    <span class="text-lg font-semibold text-gray-900">{{ $academicYear->semesters->count() }}</span>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Total Kelas</span>
                                    <span class="text-lg font-semibold text-gray-900">{{ $academicYear->classrooms->count() }}</span>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Semester Aktif</span>
                                    <span class="text-lg font-semibold text-green-600">{{ $academicYear->semesters->where('is_active', true)->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
