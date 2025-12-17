<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- 1. HEADER SECTION -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">KELOLA USER</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">Sistem Manajemen Pengguna, Dosen & Mahasiswa</p>
                </div>
                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.users.without-group') }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-red-600 hover:bg-red-700 border-2 border-red-800 rounded-lg font-bold text-white text-sm shadow-lg transform hover:-translate-y-1 transition-all">
                        <i class="fa-solid fa-user-xmark mr-2 text-lg"></i>
                        <span>Tanpa Kelompok</span>
                    </a>
                    <a href="{{ route('users.import.form') }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-green-600 hover:bg-green-700 border-2 border-green-800 rounded-lg font-bold text-white text-sm shadow-lg transform hover:-translate-y-1 transition-all">
                        <i class="fa-solid fa-file-excel mr-2 text-lg"></i>
                        <span>Import User</span>
                    </a>
                    <a href="{{ route('admin.users.create') }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 border-2 border-indigo-800 rounded-lg font-bold text-white text-sm shadow-lg transform hover:-translate-y-1 transition-all">
                        <i class="fa-solid fa-plus mr-2 text-lg"></i>
                        <span>Tambah User</span>
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

            <!-- 2. STATS CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total User -->
                <div class="bg-white rounded-xl shadow-md border-b-4 border-blue-600 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Total User</p>
                        <p class="text-4xl font-black text-gray-800">{{ $counts['all'] }}</p>
                    </div>
                    <div class="p-4 bg-blue-100 rounded-full text-blue-600">
                        <i class="fa-solid fa-users text-2xl"></i>
                    </div>
                </div>

                <!-- Mahasiswa -->
                <div class="bg-white rounded-xl shadow-md border-b-4 border-emerald-600 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Mahasiswa</p>
                        <p class="text-4xl font-black text-gray-800">{{ $counts['mahasiswa'] }}</p>
                    </div>
                    <div class="p-4 bg-emerald-100 rounded-full text-emerald-600">
                        <i class="fa-solid fa-user-graduate text-2xl"></i>
                    </div>
                </div>

                <!-- Dosen -->
                <div class="bg-white rounded-xl shadow-md border-b-4 border-purple-600 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Dosen</p>
                        <p class="text-4xl font-black text-gray-800">{{ $counts['dosen'] }}</p>
                    </div>
                    <div class="p-4 bg-purple-100 rounded-full text-purple-600">
                        <i class="fa-solid fa-chalkboard-teacher text-2xl"></i>
                    </div>
                </div>

                <!-- Admin -->
                <div class="bg-white rounded-xl shadow-md border-b-4 border-orange-600 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Admin/Koord</p>
                        <p class="text-4xl font-black text-gray-800">{{ $counts['admin'] + $counts['koordinator'] }}</p>
                    </div>
                    <div class="p-4 bg-orange-100 rounded-full text-orange-600">
                        <i class="fa-solid fa-user-shield text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- 3. FILTER & SEARCH (Collapsible / Compact) -->
            <div x-data="{ open: {{ request()->hasAny(['search', 'class_room_id', 'is_active', 'role']) && !request()->has('page') ? 'true' : 'false' }} }" class="mb-8">
                <!-- Toggle Button (High Visibility) -->
                <button @click="open = !open" 
                        class="w-full flex items-center justify-between p-4 bg-white border-2 border-dashed border-gray-300 rounded-xl hover:border-indigo-500 hover:bg-indigo-50/50 transition-all duration-300 group focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <div class="flex items-center">
                        <div class="h-12 w-12 rounded-full bg-gray-100 group-hover:bg-indigo-600 flex items-center justify-center transition-colors duration-300 mr-4 shadow-sm">
                            <i class="fa-solid fa-filter text-lg text-gray-500 group-hover:text-white transition-colors duration-300"></i>
                        </div>
                        <div class="text-left">
                            <h3 class="text-base font-black text-gray-800 uppercase tracking-wide group-hover:text-indigo-700 transition-colors">Filter & Pencarian Lanjutan</h3>
                            <p class="text-xs text-gray-500 font-medium group-hover:text-indigo-600 transition-colors mt-0.5">
                                <span x-show="!open">Klik untuk membuka opsi filter data user</span>
                                <span x-show="open">Klik untuk menyembunyikan opsi filter</span>
                            </p>
                        </div>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-white border border-gray-200 flex items-center justify-center shadow-sm group-hover:border-indigo-200">
                        <i class="fa-solid fa-chevron-down text-gray-400 group-hover:text-indigo-600 transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                    </div>
                </button>
                
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     class="bg-white rounded-xl shadow-xl border border-gray-200 p-6 mt-4 relative overflow-hidden ring-1 ring-black/5">
                    <div class="absolute top-0 left-0 w-1 h-full bg-indigo-600"></div>
                    <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                        <!-- Role is controlled by tabs, not by search form -->
                        
                        <!-- Search Input -->
                        <div class="md:col-span-4">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Cari User</label>
                            <div class="relative">
                                <i class="fa-solid fa-search absolute left-4 top-3.5 text-gray-400"></i>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Ketik NIM, Nama, atau Email..." 
                                       class="pl-12 w-full h-12 bg-gray-50 border-2 border-gray-200 rounded-lg text-sm font-semibold focus:border-indigo-600 focus:ring-0 transition-colors">
                            </div>
                        </div>

                        <!-- Class Dropdown -->
                        <div class="md:col-span-3">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Kelas</label>
                            <select name="class_room_id" class="w-full h-12 bg-gray-50 border-2 border-gray-200 rounded-lg text-sm font-semibold focus:border-indigo-600 focus:ring-0 cursor-pointer">
                                <option value="">Semua Kelas</option>
                                @foreach($classRooms as $classroom)
                                    <option value="{{ $classroom->id }}" {{ request('class_room_id') == $classroom->id ? 'selected' : '' }}>
                                        {{ $classroom->name }}
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
                                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="md:col-span-2 flex gap-2">
                            <button type="submit" class="flex-1 h-12 bg-blue-800 hover:bg-blue-900 text-white font-bold rounded-lg shadow-md hover:shadow-lg transition-all flex items-center justify-center">
                                Terapkan
                            </button>
                            @if(request()->hasAny(['search', 'class_room_id', 'is_active']))
                                <a href="{{ route('admin.users.index', ['role' => request('role')]) }}" class="h-12 w-12 bg-white border-2 border-gray-300 hover:border-red-500 hover:bg-red-50 text-gray-600 hover:text-red-600 rounded-lg transition-all flex items-center justify-center" title="Reset">
                                    <i class="fa-solid fa-rotate-left text-lg"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- 4. MAIN DATA SECTION -->
            <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
                
                <!-- SERVER SIDE TABS -->
                <div class="flex border-b-2 border-gray-100 overflow-x-auto">
                    @php
                        $tabs = [
                            '' => ['label' => 'Semua', 'icon' => 'fa-users', 'count' => $counts['all']],
                            'admin' => ['label' => 'Admin', 'icon' => 'fa-user-shield', 'count' => $counts['admin']],
                            'koordinator' => ['label' => 'Koordinator', 'icon' => 'fa-user-tie', 'count' => $counts['koordinator']],
                            'dosen' => ['label' => 'Dosen', 'icon' => 'fa-chalkboard-teacher', 'count' => $counts['dosen']],
                            'mahasiswa' => ['label' => 'Mahasiswa', 'icon' => 'fa-user-graduate', 'count' => $counts['mahasiswa']],
                        ];
                        $currentRole = request('role', '');
                    @endphp

                    @foreach($tabs as $roleKey => $tab)
                    <a href="{{ route('admin.users.index', array_merge(request()->except(['role', 'page']), ['role' => $roleKey])) }}" 
                       class="flex-1 min-w-[120px] py-5 text-center group transition-all duration-300 border-b-4 
                       {{ $currentRole == $roleKey ? 'border-blue-600 bg-blue-600 text-white shadow-lg' : 'border-transparent hover:bg-gray-100 bg-white' }}">
                        
                        <div class="flex flex-col items-center justify-center">
                            <span class="text-xs font-extrabold uppercase tracking-widest mb-1 
                                {{ $currentRole == $roleKey ? 'text-white' : 'text-gray-500 group-hover:text-gray-700' }}">
                                <i class="fa-solid {{ $tab['icon'] }} mr-1"></i> {{ $tab['label'] }}
                            </span>
                            <span class="py-0.5 px-2.5 rounded-full text-xs font-black shadow-sm 
                                {{ $currentRole == $roleKey ? 'bg-white text-blue-700' : 'bg-gray-200 text-gray-600 group-hover:bg-gray-300' }}">
                                {{ $tab['count'] }}
                            </span>
                        </div>
                    </a>
                    @endforeach
                </div>

                <!-- TABLE CONTENT -->
                <div class="overflow-x-auto">
                    @if($users->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-900">
                                <tr>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-white uppercase tracking-wider w-16">No</th>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-white uppercase tracking-wider">Identitas Pengguna</th>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-white uppercase tracking-wider">Role / Kelas</th>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-white uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-5 text-center text-xs font-bold text-white uppercase tracking-wider w-56 border-l border-gray-800">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($users as $index => $user)
                                <tr class="hover:bg-blue-50 transition-colors group">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-bold">{{ $users->firstItem() + $index }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                <div class="h-12 w-12 rounded-lg bg-indigo-600 flex items-center justify-center text-white text-lg font-bold shadow-lg">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-black text-gray-900 group-hover:text-indigo-700">{{ $user->name }}</div>
                                                <div class="text-sm text-gray-500 font-medium">{{ $user->email }}</div>
                                                @if($user->nim)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 mt-1 border border-gray-300">
                                                        {{ $user->nim }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col items-start gap-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold uppercase tracking-wide border 
                                                {{ $user->role === 'admin' ? 'bg-red-100 text-red-800 border-red-200' : '' }}
                                                {{ $user->role === 'koordinator' ? 'bg-orange-100 text-orange-800 border-orange-200' : '' }}
                                                {{ $user->role === 'dosen' ? 'bg-purple-100 text-purple-800 border-purple-200' : '' }}
                                                {{ $user->role === 'mahasiswa' ? 'bg-blue-100 text-blue-800 border-blue-200' : '' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                            
                                            @if($user->classRoom)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                                    <i class="fa-solid fa-door-open mr-1"></i> {{ $user->classRoom->name }}
                                                </span>
                                            @elseif($user->role === 'mahasiswa')
                                                <span class="text-xs text-red-500 font-bold italic">Belum Masuk Kelas</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                         <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 {{ $user->is_active ? 'bg-emerald-500' : 'bg-gray-300' }}">
                                                <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $user->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                            </button>
                                        </form>
                                        <span class="text-xs font-bold mt-1 block {{ $user->is_active ? 'text-emerald-600' : 'text-gray-400' }}">{{ $user->is_active ? 'AKTIF' : 'NONAKTIF' }}</span>
                                    </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-center border-l border-gray-100">
                                                                <div class="flex items-center justify-center gap-2">
                                                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                                                       class="inline-flex items-center justify-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-bold rounded shadow-sm hover:shadow transition-all uppercase tracking-wide">
                                                                        <i class="fas fa-edit mr-1.5"></i> Edit
                                                                    </a>
                                                                    @if($user->id !== auth()->id())
                                                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline delete-form">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="button" class="delete-btn inline-flex items-center justify-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-[10px] font-bold rounded shadow-sm hover:shadow transition-all uppercase tracking-wide" data-name="{{ $user->name }}">
                                                                            <i class="fas fa-trash mr-1.5"></i> Hapus
                                                                        </button>
                                                                    </form>
                                                                    @endif
                                                                </div>
                                                            </td>                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        <!-- Pagination Links -->
                        <div class="px-6 py-6 bg-gray-50 border-t border-gray-200">
                            {{ $users->appends(request()->except('page'))->links() }}
                        </div>
                    @else
                        <div class="py-24 text-center">
                            <div class="inline-block p-6 rounded-full bg-gray-100 mb-4">
                                <i class="fas fa-search text-5xl text-gray-300"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">Data Tidak Ditemukan</h3>
                            <p class="text-gray-500 mt-2">Belum ada data user untuk kategori ini.</p>
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
                const name = btn.dataset.name;
                const form = btn.closest('.delete-form');
                
                Swal.fire({
                    title: 'Hapus User?',
                    html: `Anda akan menghapus user <b>${name}</b>.<br>Data ini tidak bisa dikembalikan!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            }
        });
    </script>
    @endpush
</x-app-layout>