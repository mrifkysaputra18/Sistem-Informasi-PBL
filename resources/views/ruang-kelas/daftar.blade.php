<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="space-y-1">
                <h2 class="font-bold text-2xl text-white leading-tight flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    {{ __('Manajemen Kelas') }}
                </h2>
                <p class="text-sm text-white/90">Kelola kelas dan data mahasiswa per periode akademik</p>
            </div>
            @if(auth()->user()->isAdmin() || auth()->user()->isDosen())
            <a href="{{ route('classrooms.create') }}" 
               class="bg-primary-500 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                <i class="fas fa-plus mr-2"></i>Tambah Kelas
            </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Success -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg shadow-md">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg shadow-md">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Card 1: Total Kelas -->
                <div class="group relative bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg hover:shadow-2xl p-6 text-white transition-all duration-300 hover:scale-105 cursor-pointer overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    
                    <div class="relative flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-blue-100 text-xs font-medium uppercase tracking-wider">Total Kelas</p>
                            <p class="text-4xl font-black mt-3 mb-1 group-hover:scale-110 transition-transform duration-300">{{ $stats['total_classes'] }}</p>
                            <p class="text-xs text-blue-100 mt-2">Kelas terdaftar</p>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl group-hover:rotate-12 transition-transform duration-300">
                            <i class="fa-solid fa-chalkboard-user text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Card 2: Kelas Aktif -->
                <div class="group relative bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-lg hover:shadow-2xl p-6 text-white transition-all duration-300 hover:scale-105 cursor-pointer overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    
                    <div class="relative flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-green-100 text-xs font-medium uppercase tracking-wider">Kelas Aktif</p>
                            <p class="text-4xl font-black mt-3 mb-1 group-hover:scale-110 transition-transform duration-300">{{ $stats['active_classes'] }}</p>
                            <p class="text-xs text-green-100 mt-2">Kelas berjalan</p>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl group-hover:rotate-12 transition-transform duration-300">
                            <i class="fa-solid fa-circle-check text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Card 3: Total Mahasiswa -->
                <div class="group relative bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg hover:shadow-2xl p-6 text-white transition-all duration-300 hover:scale-105 cursor-pointer overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    
                    <div class="relative flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-purple-100 text-xs font-medium uppercase tracking-wider">Total Mahasiswa</p>
                            <p class="text-4xl font-black mt-3 mb-1 group-hover:scale-110 transition-transform duration-300">{{ $stats['total_students'] }}</p>
                            <p class="text-xs text-purple-100 mt-2">Mahasiswa terdaftar</p>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl group-hover:rotate-12 transition-transform duration-300">
                            <i class="fa-solid fa-user-graduate text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Rata-rata Mahasiswa -->
                <div class="group relative bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl shadow-lg hover:shadow-2xl p-6 text-white transition-all duration-300 hover:scale-105 cursor-pointer overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    
                    <div class="relative flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-orange-100 text-xs font-medium uppercase tracking-wider">Rata-rata per Kelas</p>
                            <p class="text-4xl font-black mt-3 mb-1 group-hover:scale-110 transition-transform duration-300">{{ $stats['average_students'] }}</p>
                            <p class="text-xs text-orange-100 mt-2">Mahasiswa per kelas</p>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl group-hover:rotate-12 transition-transform duration-300">
                            <i class="fa-solid fa-chart-line text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <form method="GET" action="{{ route('classrooms.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cari Kelas</label>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Nama atau kode kelas..."
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-500 focus:ring-secondary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                        <select name="semester" class="w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-500 focus:ring-secondary-500">
                            <option value="">Semua Semester</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester }}" {{ request('semester') == $semester ? 'selected' : '' }}>
                                    Semester {{ $semester }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="is_active" class="w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-500 focus:ring-secondary-500">
                            <option value="">Semua Status</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="bg-secondary-500 hover:bg-secondary-600 text-white py-2 px-6 rounded-md">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                        @if(request()->hasAny(['search','semester','is_active']))
                            <a href="{{ route('classrooms.index') }}"
                               class="bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-md transition">
                                <i class="fas fa-undo"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Main Content Card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-table mr-2 text-gray-600"></i>Daftar Kelas
                        </h3>
                        <div class="text-sm text-gray-700 font-medium">
                            Showing {{ $classRooms->count() }} of {{ $classRooms->total() }} entries
                        </div>
                    </div>

                    @if($classRooms->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-hashtag mr-1"></i>No
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-school mr-1"></i>Nama Kelas
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-code mr-1"></i>Kode
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-user-graduate mr-1"></i>Mahasiswa
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-toggle-on mr-1"></i>Status
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-cogs mr-1"></i>Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($classRooms as $index => $classRoom)
                                        <tr class="hover:bg-gray-50 transition duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ ($classRooms->currentPage() - 1) * $classRooms->perPage() + $index + 1 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $classRoom->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ $classRoom->code }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="fas fa-user-graduate mr-1"></i>{{ $classRoom->students_count }} Mahasiswa
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $classRoom->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ $classRoom->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <div class="flex items-center justify-center space-x-2">
                                                    <a href="{{ route('classrooms.show', $classRoom->id) }}" 
                                                       class="inline-flex items-center px-3 py-2 text-sm font-medium text-green-600 bg-green-100 hover:bg-green-200 hover:text-green-900 rounded-lg transition duration-200 ease-in-out">
                                                        <i class="fas fa-user-graduate mr-1.5"></i>
                                                        Lihat Mahasiswa
                                                    </a>
                                                    @if(auth()->user()->isAdmin() || auth()->user()->isDosen())
                                                    <a href="{{ url('/classrooms/' . $classRoom->id . '/edit') }}" 
                                                       class="inline-flex items-center px-3 py-2 text-sm font-medium text-primary-600 bg-primary-100 hover:bg-primary-200 hover:text-primary-900 rounded-lg transition duration-200 ease-in-out">
                                                        <i class="fas fa-edit mr-1.5"></i>
                                                        Edit
                                                    </a>
                                                    <button type="button"
                                                            onclick="deleteClass({{ $classRoom->id }}, '{{ addslashes($classRoom->name) }}')"
                                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 bg-red-100 hover:bg-red-200 hover:text-red-900 rounded-lg transition duration-200 ease-in-out">
                                                        <i class="fas fa-trash mr-1.5"></i>
                                                        Hapus
                                                    </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $classRooms->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-400 mb-4">
                                <i class="fas fa-school text-6xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada kelas</h3>
                            <p class="text-gray-500 mb-4">Mulai dengan menambahkan kelas pertama Anda.</p>
                            @if(auth()->user()->isAdmin() || auth()->user()->isDosen())
                            <a href="{{ route('classrooms.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-700 text-white font-bold rounded-lg shadow-md transition duration-300">
                                <i class="fas fa-plus mr-2"></i>Tambah Kelas Pertama
                            </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Delete Form -->
    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function deleteClass(classId, className) {
            const form = document.getElementById('delete-form');
            form.action = '/classrooms/' + classId;
            
            confirmDelete(
                'Hapus Kelas?',
                `Apakah Anda yakin ingin menghapus kelas <strong>"${className}"</strong>?<br><small class="text-gray-500">Tindakan ini tidak dapat dibatalkan.</small>`,
                form
            );
        }
    </script>
</x-app-layout>
