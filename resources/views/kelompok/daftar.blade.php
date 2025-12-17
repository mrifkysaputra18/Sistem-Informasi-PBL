<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- 1. HEADER SECTION -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">MANAJEMEN KELOMPOK</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">Kelola kelompok mahasiswa, ketua, dan anggota project.</p>
                </div>
                <!-- Action Buttons - Hanya untuk admin dan dosen (koordinator hanya monitoring) -->
                @if(in_array(auth()->user()->role, ['admin', 'dosen']))
                <div class="flex flex-wrap gap-3">
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('import.groups') }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-green-600 hover:bg-green-700 border-2 border-green-800 rounded-lg font-bold text-white text-sm shadow-lg transform hover:-translate-y-1 transition-all">
                        <i class="fa-solid fa-file-excel mr-2 text-lg"></i>
                        <span>Import Kelompok</span>
                    </a>
                    @endif
                    <a href="{{ route('groups.create', ['classroom' => request('classroom')]) }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 border-2 border-indigo-800 rounded-lg font-bold text-white text-sm shadow-lg transform hover:-translate-y-1 transition-all">
                        <i class="fa-solid fa-plus mr-2 text-lg"></i>
                        <span>Tambah Kelompok</span>
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

            <!-- 2. STATS CARDS (Indigo Theme) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Total Kelompok -->
                <div class="bg-white rounded-xl shadow-md border-b-4 border-indigo-600 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Total Kelompok</p>
                        <p class="text-3xl font-black text-gray-800">{{ $groups->total() }}</p>
                    </div>
                    <div class="p-3 bg-indigo-100 rounded-full text-indigo-600">
                        <i class="fa-solid fa-users text-2xl"></i>
                    </div>
                </div>

                <!-- Kelompok Aktif -->
                <div class="bg-white rounded-xl shadow-md border-b-4 border-emerald-600 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Kelompok Aktif</p>
                        <p class="text-3xl font-black text-gray-800">{{ $groups->count() }}</p>
                    </div>
                    <div class="p-3 bg-emerald-100 rounded-full text-emerald-600">
                        <i class="fa-solid fa-circle-check text-2xl"></i>
                    </div>
                </div>

                <!-- Kelompok Penuh -->
                <div class="bg-white rounded-xl shadow-md border-b-4 border-purple-600 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Kelompok Penuh</p>
                        <p class="text-3xl font-black text-gray-800">{{ $groups->filter(fn($g) => $g->isFull())->count() }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-full text-purple-600">
                        <i class="fa-solid fa-users-cog text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- 3. FILTER CONTROL -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-indigo-600"></div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fa-solid fa-filter mr-2 text-indigo-600"></i> Filter Data
                </h3>
                
                <form method="GET" action="{{ route('groups.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="flex-1 w-full">
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Filter Berdasarkan Kelas</label>
                        <select name="classroom" class="w-full h-12 bg-gray-50 border-2 border-gray-200 rounded-lg text-sm font-semibold focus:border-indigo-600 focus:ring-0 cursor-pointer transition-colors">
                            <option value="">Semua Kelas</option>
                            @foreach($classRooms as $classroom)
                                <option value="{{ $classroom->id }}" {{ request('classroom') == $classroom->id ? 'selected' : '' }}>
                                    {{ $classroom->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2 w-full md:w-auto">
                        <button type="submit" class="flex-1 md:flex-none h-12 px-6 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-md hover:shadow-lg transition-all flex items-center justify-center">
                            Terapkan
                        </button>
                        @if(request()->has('classroom'))
                            <a href="{{ route('groups.index') }}" class="h-12 w-12 bg-white border-2 border-gray-300 hover:border-red-500 hover:bg-red-50 text-gray-600 hover:text-red-600 rounded-lg transition-all flex items-center justify-center" title="Reset Filter">
                                <i class="fa-solid fa-rotate-left text-lg"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- 4. DATA TABLE -->
            <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    @if($groups->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-900">
                                <tr>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-white uppercase tracking-wider w-16">No</th>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-white uppercase tracking-wider">Nama Kelompok</th>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-white uppercase tracking-wider">Kelas</th>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-white uppercase tracking-wider">Ketua</th>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-white uppercase tracking-wider">Anggota</th>
                                    <th class="px-6 py-5 text-center text-xs font-bold text-white uppercase tracking-wider w-64 border-l border-gray-800">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($groups as $index => $group)
                                <tr class="hover:bg-indigo-50 transition-colors group">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-bold">{{ ($groups->currentPage() - 1) * $groups->perPage() + $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-black text-gray-900 group-hover:text-indigo-700">{{ $group->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($group->classRoom)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-indigo-100 text-indigo-800 border border-indigo-200 uppercase tracking-wide">
                                                {{ $group->classRoom->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs italic">Tidak ada kelas</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($group->leader)
                                            <div class="flex items-center">
                                                <i class="fa-solid fa-crown text-yellow-500 mr-2 text-xs"></i>
                                                <span class="text-sm font-bold text-gray-700">{{ $group->leader->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Belum ditentukan</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold border uppercase tracking-wide
                                            {{ $group->isFull() ? 'bg-rose-100 text-rose-800 border-rose-200' : 'bg-emerald-100 text-emerald-800 border-emerald-200' }}">
                                            <i class="fas fa-users mr-1.5"></i> {{ $group->members->count() }} / {{ $group->max_members }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center border-l border-gray-100">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('groups.show', $group) }}" 
                                               class="inline-flex items-center justify-center px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-[10px] font-bold rounded shadow-sm hover:shadow transition-all uppercase tracking-wide">
                                                <i class="fas fa-eye mr-1.5"></i> Detail
                                            </a>
                                            
                                            {{-- Edit dan Hapus hanya untuk admin dan dosen (koordinator hanya monitoring) --}}
                                            @if(in_array(auth()->user()->role, ['admin', 'dosen']))
                                            <a href="{{ route('groups.edit', $group) }}" 
                                               class="inline-flex items-center justify-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-bold rounded shadow-sm hover:shadow transition-all uppercase tracking-wide">
                                                <i class="fas fa-edit mr-1.5"></i> Edit
                                            </a>
                                            
                                            <form action="{{ route('groups.destroy', $group) }}" method="POST" class="inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="delete-btn inline-flex items-center justify-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-[10px] font-bold rounded shadow-sm hover:shadow transition-all uppercase tracking-wide" data-group-name="{{ $group->name }}">
                                                    <i class="fas fa-trash mr-1.5"></i> Hapus
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="px-6 py-6 bg-gray-50 border-t border-gray-200">
                            {{ $groups->links() }}
                        </div>
                    @else
                        <div class="py-24 text-center">
                            <div class="inline-block p-6 rounded-full bg-gray-100 mb-4 border border-gray-200">
                                <i class="fas fa-users text-5xl text-gray-300"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">Belum Ada Kelompok</h3>
                            <p class="text-gray-500 mt-2">Silakan buat kelompok baru untuk memulai.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-btn')) {
                const btn = e.target.closest('.delete-btn');
                const groupName = btn.getAttribute('data-group-name');
                const form = btn.closest('.delete-form');
                
                Swal.fire({
                    title: 'Hapus Kelompok?',
                    html: `Anda akan menghapus kelompok <b>"${groupName}"</b>.<br>Data ini tidak dapat dikembalikan!`,
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
        });
    </script>
    @endpush
</x-app-layout>