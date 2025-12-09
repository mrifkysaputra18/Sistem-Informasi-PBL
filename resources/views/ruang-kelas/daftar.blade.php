<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- 1. HEADER SECTION -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">MANAJEMEN KELAS</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">Kelola data kelas dan pemetaan mahasiswa per periode akademik.</p>
                </div>
                <!-- Action Buttons -->
                @if(auth()->user()->isAdmin() || auth()->user()->isDosen())
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('classrooms.create') }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 border-2 border-indigo-800 rounded-lg font-bold text-white text-sm shadow-lg transform hover:-translate-y-1 transition-all">
                        <i class="fa-solid fa-plus mr-2 text-lg"></i>
                        <span>Tambah Kelas</span>
                    </a>
                </div>
                @endif
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="bg-emerald-100 border-l-8 border-emerald-600 text-emerald-800 px-6 py-4 rounded-lg shadow-md mb-8 flex items-start justify-between">
                    <div class="flex items-center">
                        <i class="fa-solid fa-check-circle text-2xl mr-4 text-emerald-600"></i>
                        <span class="font-bold text-lg">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-600 hover:text-emerald-800"><i class="fa-solid fa-times text-xl"></i></button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" class="bg-red-100 border-l-8 border-red-600 text-red-800 px-6 py-4 rounded-lg shadow-md mb-8 flex items-start justify-between">
                    <div class="flex items-center">
                        <i class="fa-solid fa-exclamation-triangle text-2xl mr-4 text-red-600"></i>
                        <span class="font-bold text-lg">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-times text-xl"></i></button>
                </div>
            @endif

            <!-- 2. STATS CARDS (Clean & Professional) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Kelas -->
                <div class="bg-white rounded-xl shadow-md border-b-4 border-indigo-600 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Total Kelas</p>
                        <p class="text-3xl font-black text-gray-800">{{ $stats['total_classes'] }}</p>
                    </div>
                    <div class="p-3 bg-indigo-100 rounded-full text-indigo-600">
                        <i class="fa-solid fa-chalkboard-user text-2xl"></i>
                    </div>
                </div>

                <!-- Kelas Aktif -->
                <div class="bg-white rounded-xl shadow-md border-b-4 border-emerald-600 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Kelas Aktif</p>
                        <p class="text-3xl font-black text-gray-800">{{ $stats['active_classes'] }}</p>
                    </div>
                    <div class="p-3 bg-emerald-100 rounded-full text-emerald-600">
                        <i class="fa-solid fa-circle-check text-2xl"></i>
                    </div>
                </div>

                <!-- Total Mahasiswa -->
                <div class="bg-white rounded-xl shadow-md border-b-4 border-blue-600 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Total Mahasiswa</p>
                        <p class="text-3xl font-black text-gray-800">{{ $stats['total_students'] }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full text-blue-600">
                        <i class="fa-solid fa-user-graduate text-2xl"></i>
                    </div>
                </div>

                <!-- Rata-rata -->
                <div class="bg-white rounded-xl shadow-md border-b-4 border-gray-600 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Rata-rata / Kelas</p>
                        <p class="text-3xl font-black text-gray-800">{{ $stats['average_students'] }}</p>
                    </div>
                    <div class="p-3 bg-gray-100 rounded-full text-gray-600">
                        <i class="fa-solid fa-chart-line text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- 3. FILTER CONTROL (Indigo Theme - Consistent) -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-indigo-600"></div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fa-solid fa-filter mr-2 text-indigo-600"></i> Filter Data
                </h3>
                
                <form method="GET" action="{{ route('classrooms.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                    <!-- Search Input -->
                    <div class="md:col-span-4">
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Cari Kelas</label>
                        <div class="relative">
                            <i class="fa-solid fa-search absolute left-4 top-3.5 text-gray-400"></i>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Nama atau Kode Kelas..." 
                                   class="pl-12 w-full h-12 bg-gray-50 border-2 border-gray-200 rounded-lg text-sm font-semibold focus:border-indigo-600 focus:ring-0 transition-colors">
                        </div>
                    </div>

                    <!-- Semester Dropdown -->
                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Semester</label>
                        <select name="semester" class="w-full h-12 bg-gray-50 border-2 border-gray-200 rounded-lg text-sm font-semibold focus:border-indigo-600 focus:ring-0 cursor-pointer">
                            <option value="">Semua Semester</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester }}" {{ request('semester') == $semester ? 'selected' : '' }}>
                                    Semester {{ $semester }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Dropdown -->
                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Status</label>
                        <select name="is_active" class="w-full h-12 bg-gray-50 border-2 border-gray-200 rounded-lg text-sm font-semibold focus:border-indigo-600 focus:ring-0 cursor-pointer">
                            <option value="">Semua Status</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="md:col-span-2 flex gap-2">
                        <button type="submit" class="flex-1 h-12 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-md hover:shadow-lg transition-all flex items-center justify-center">
                            Terapkan
                        </button>
                        @if(request()->hasAny(['search', 'semester', 'is_active']))
                            <a href="{{ route('classrooms.index') }}" class="h-12 w-12 bg-white border-2 border-gray-300 hover:border-red-500 hover:bg-red-50 text-gray-600 hover:text-red-600 rounded-lg transition-all flex items-center justify-center" title="Reset">
                                <i class="fa-solid fa-rotate-left text-lg"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- 4. DATA TABLE -->
            <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    @if($classRooms->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-900">
                                <tr>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-white uppercase tracking-wider w-16">No</th>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-white uppercase tracking-wider">Informasi Kelas</th>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-white uppercase tracking-wider">Statistik</th>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-white uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-5 text-center text-xs font-bold text-white uppercase tracking-wider w-64 border-l border-gray-800">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($classRooms as $index => $classRoom)
                                <tr class="hover:bg-indigo-50 transition-colors group">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-bold">{{ ($classRooms->currentPage() - 1) * $classRooms->perPage() + $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                <div class="h-12 w-12 rounded-lg bg-indigo-600 flex items-center justify-center text-white text-lg font-bold shadow-lg">
                                                    {{ strtoupper(substr($classRoom->name, 0, 2)) }}
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-black text-gray-900 group-hover:text-indigo-700">{{ $classRoom->name }}</div>
                                                <div class="text-sm text-gray-500 font-medium font-mono bg-gray-100 inline-block px-1 rounded mt-0.5">{{ $classRoom->code }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-4">
                                            <div class="text-center">
                                                <span class="block text-xs font-bold text-gray-400 uppercase">Siswa</span>
                                                <span class="text-lg font-black text-gray-800">{{ $classRoom->students_count }}</span>
                                            </div>
                                            <div class="text-center">
                                                <span class="block text-xs font-bold text-gray-400 uppercase">Kelompok</span>
                                                <span class="text-lg font-black text-gray-800">{{ $classRoom->groups_count ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold border uppercase tracking-wide
                                            {{ $classRoom->is_active ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                            {{ $classRoom->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center border-l border-gray-100">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('classrooms.show', $classRoom->id) }}" 
                                               class="inline-flex items-center justify-center px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-[10px] font-bold rounded shadow-sm hover:shadow transition-all uppercase tracking-wide">
                                                <i class="fas fa-eye mr-1.5"></i> Detail
                                            </a>
                                            
                                            @if(auth()->user()->isAdmin() || auth()->user()->isDosen())
                                            <a href="{{ url('/classrooms/' . $classRoom->id . '/edit') }}" 
                                               class="inline-flex items-center justify-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-bold rounded shadow-sm hover:shadow transition-all uppercase tracking-wide">
                                                <i class="fas fa-edit mr-1.5"></i> Edit
                                            </a>
                                            
                                            <button type="button"
                                                    onclick="deleteClass({{ $classRoom->id }}, '{{ addslashes($classRoom->name) }}')"
                                                    class="inline-flex items-center justify-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-[10px] font-bold rounded shadow-sm hover:shadow transition-all uppercase tracking-wide">
                                                <i class="fas fa-trash mr-1.5"></i> Hapus
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="px-6 py-6 bg-gray-50 border-t border-gray-200">
                            {{ $classRooms->links() }}
                        </div>
                    @else
                        <div class="py-24 text-center">
                            <div class="inline-block p-6 rounded-full bg-gray-100 mb-4 border border-gray-200">
                                <i class="fas fa-school text-5xl text-gray-300"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">Belum Ada Kelas</h3>
                            <p class="text-gray-500 mt-2">Silakan tambahkan kelas baru untuk memulai.</p>
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
            
            Swal.fire({
                title: 'Hapus Kelas?',
                html: `Anda akan menghapus kelas <b>"${className}"</b>.<br>Data ini tidak dapat dikembalikan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>
