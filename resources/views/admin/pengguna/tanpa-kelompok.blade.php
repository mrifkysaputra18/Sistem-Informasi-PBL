<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- 1. HEADER SECTION -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">MAHASISWA TANPA KELOMPOK</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">Daftar mahasiswa yang belum bergabung ke kelompok manapun.</p>
                </div>
                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.users.index') }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-gray-600 hover:bg-gray-700 border-2 border-gray-800 rounded-lg font-bold text-white text-sm shadow-lg transform hover:-translate-y-1 transition-all">
                        <i class="fa-solid fa-arrow-left mr-2 text-lg"></i>
                        <span>Kembali</span>
                    </a>
                    <a href="{{ route('groups.create') }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 border-2 border-indigo-800 rounded-lg font-bold text-white text-sm shadow-lg transform hover:-translate-y-1 transition-all">
                        <i class="fa-solid fa-users-medical mr-2 text-lg"></i>
                        <span>Buat Kelompok</span>
                    </a>
                </div>
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

            <!-- 2. SUMMARY CARD (Statistik per Kelas) -->
            <div class="bg-white rounded-xl shadow-md border-b-4 border-rose-500 p-6 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <i class="fa-solid fa-chart-pie mr-2 text-rose-500"></i> Ringkasan Per Kelas
                        </h3>
                        <p class="text-sm text-gray-500">Jumlah mahasiswa yang belum memiliki kelompok.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($statsPerClass as $stat)
                        <div class="p-4 bg-rose-50 rounded-lg border border-rose-100 hover:border-rose-300 transition-colors">
                            <div class="text-sm font-bold text-gray-600 mb-1">{{ $stat->name }}</div>
                            <div class="flex items-baseline gap-2">
                                <span class="text-2xl font-black text-rose-600">{{ $stat->students_without_group }}</span>
                                <span class="text-xs font-bold text-gray-400">/ {{ $stat->total_students }} Siswa</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                                <div class="bg-rose-500 h-1.5 rounded-full" style="width: {{ $stat->total_students > 0 ? ($stat->students_without_group / $stat->total_students) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 3. FILTER CONTROL -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-rose-600"></div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fa-solid fa-filter mr-2 text-rose-600"></i> Filter Data
                </h3>
                
                <form method="GET" action="{{ route('admin.users.without-group') }}" class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                    <!-- Search Input -->
                    <div class="md:col-span-6">
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Cari Mahasiswa</label>
                        <div class="relative">
                            <i class="fa-solid fa-search absolute left-4 top-3.5 text-gray-400"></i>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Nama, Email, atau NIM..." 
                                   class="pl-12 w-full h-12 bg-gray-50 border-2 border-gray-200 rounded-lg text-sm font-semibold focus:border-rose-600 focus:ring-0 transition-colors">
                        </div>
                    </div>

                    <!-- Class Dropdown -->
                    <div class="md:col-span-4">
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Filter Kelas</label>
                        <select name="class_room_id" class="w-full h-12 bg-gray-50 border-2 border-gray-200 rounded-lg text-sm font-semibold focus:border-rose-600 focus:ring-0 cursor-pointer">
                            <option value="">Semua Kelas</option>
                            @foreach($classRooms as $classroom)
                                <option value="{{ $classroom->id }}" {{ request('class_room_id') == $classroom->id ? 'selected' : '' }}>
                                    {{ $classroom->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="md:col-span-2 flex gap-2">
                        <button type="submit" class="flex-1 h-12 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-lg shadow-md hover:shadow-lg transition-all flex items-center justify-center">
                            Terapkan
                        </button>
                        @if(request()->hasAny(['search', 'class_room_id']))
                            <a href="{{ route('admin.users.without-group') }}" class="h-12 w-12 bg-white border-2 border-gray-300 hover:border-gray-500 hover:bg-gray-50 text-gray-600 hover:text-gray-800 rounded-lg transition-all flex items-center justify-center" title="Reset">
                                <i class="fa-solid fa-rotate-left text-lg"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- 4. DATA TABLE -->
            <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    @if($students->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-900">
                                <tr>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-white uppercase tracking-wider w-16">No</th>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-white uppercase tracking-wider">Identitas Mahasiswa</th>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-white uppercase tracking-wider">Kelas & Prodi</th>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-white uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-5 text-center text-xs font-bold text-white uppercase tracking-wider w-56 border-l border-gray-800">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($students as $index => $student)
                                <tr class="hover:bg-rose-50 transition-colors group">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-bold">{{ $loop->iteration + ($students->currentPage() - 1) * $students->perPage() }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                <div class="h-12 w-12 rounded-lg bg-rose-500 flex items-center justify-center text-white text-lg font-bold shadow-lg">
                                                    {{ strtoupper(substr($student->name, 0, 2)) }}
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-black text-gray-900 group-hover:text-rose-700">{{ $student->name }}</div>
                                                <div class="text-sm text-gray-500 font-medium">{{ $student->email }}</div>
                                                @if($student->nim)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 mt-1 border border-gray-300">
                                                        NIM: {{ $student->nim }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($student->classRoom)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200 mb-1">
                                                {{ $student->classRoom->name }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500 border border-gray-200 mb-1">
                                                Belum Ada Kelas
                                            </span>
                                        @endif
                                        <div class="text-xs text-gray-500 font-medium">{{ $student->program_studi ?? 'Prodi Tidak Diset' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200 uppercase tracking-wide">
                                            Tanpa Kelompok
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center border-l border-gray-100">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.users.show', $student) }}" 
                                               class="inline-flex items-center justify-center px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-[10px] font-bold rounded shadow-sm hover:shadow transition-all uppercase tracking-wide">
                                                <i class="fas fa-eye mr-1.5"></i> Detail
                                            </a>
                                            <a href="{{ route('admin.users.edit', $student) }}" 
                                               class="inline-flex items-center justify-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-bold rounded shadow-sm hover:shadow transition-all uppercase tracking-wide">
                                                <i class="fas fa-edit mr-1.5"></i> Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="px-6 py-6 bg-gray-50 border-t border-gray-200">
                            {{ $students->appends(request()->except('page'))->links() }}
                        </div>
                    @else
                        <div class="py-24 text-center">
                            <div class="inline-block p-6 rounded-full bg-green-50 mb-4 border border-green-100">
                                <i class="fas fa-check-circle text-5xl text-green-500"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">Luar Biasa!</h3>
                            <p class="text-gray-500 mt-2">Semua mahasiswa (sesuai filter) sudah memiliki kelompok.</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Simple Helper -->
            <p class="text-center text-xs text-gray-400 mt-8 mb-4">
                Halaman ini menampilkan mahasiswa yang belum terdaftar dalam kelompok manapun. Gunakan tombol "Buat Kelompok" untuk segera memproses mereka.
            </p>
        </div>
    </div>
</x-app-layout>