<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-white leading-tight">
                Kelola User
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.users.without-group') }}" 
                   class="bg-orange-500 hover:bg-orange-600 text-white text-sm py-2 px-4 rounded-lg transition">
                    <i class="fa-solid fa-user-xmark mr-1"></i>Tanpa Kelompok
                </a>
                <a href="{{ route('users.import.form') }}" 
                   class="bg-green-500 hover:bg-green-600 text-white text-sm py-2 px-4 rounded-lg transition">
                    <i class="fa-solid fa-file-excel mr-1"></i>Import
                </a>
                <a href="{{ route('admin.users.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white text-sm py-2 px-4 rounded-lg transition">
                    <i class="fa-solid fa-plus mr-1"></i>Tambah
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
                    <i class="fa-solid fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
                    <i class="fa-solid fa-exclamation-circle mr-2"></i>{{ session('error') }}
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500">
                    <p class="text-xs text-gray-500 uppercase">Total User</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $users->total() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
                    <p class="text-xs text-gray-500 uppercase">Mahasiswa</p>
                    <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Pengguna::where('role', 'mahasiswa')->count() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-purple-500">
                    <p class="text-xs text-gray-500 uppercase">Dosen</p>
                    <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Pengguna::where('role', 'dosen')->count() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-orange-500">
                    <p class="text-xs text-gray-500 uppercase">Admin/Koordinator</p>
                    <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Pengguna::whereIn('role', ['admin', 'koordinator'])->count() }}</p>
                </div>
            </div>

            <!-- Filter -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-end gap-3">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-xs text-gray-600 mb-1">Cari</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Nama, email, atau NIM..." 
                               class="w-full text-sm rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="w-36">
                        <label class="block text-xs text-gray-600 mb-1">Role</label>
                        <select name="role" class="w-full text-sm rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua</option>
                            <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                            <option value="dosen" {{ request('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                            <option value="koordinator" {{ request('role') == 'koordinator' ? 'selected' : '' }}>Koordinator</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    <div class="w-36">
                        <label class="block text-xs text-gray-600 mb-1">Kelas</label>
                        <select name="class_room_id" class="w-full text-sm rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua</option>
                            @foreach($classRooms as $classroom)
                                <option value="{{ $classroom->id }}" {{ request('class_room_id') == $classroom->id ? 'selected' : '' }}>
                                    {{ $classroom->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-32">
                        <label class="block text-xs text-gray-600 mb-1">Status</label>
                        <select name="is_active" class="w-full text-sm rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm py-2 px-4 rounded-lg">
                            <i class="fa-solid fa-search mr-1"></i>Filter
                        </button>
                        @if(request()->hasAny(['search', 'role', 'class_room_id', 'is_active']))
                            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white text-sm py-2 px-4 rounded-lg">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tab Navigation -->
            @if(!request()->hasAny(['role', 'class_room_id', 'is_active', 'search']))
            <div class="bg-white rounded-t-lg shadow-md border-b">
                <nav class="flex">
                    @foreach(['admin' => 'Admin', 'koordinator' => 'Koordinator', 'dosen' => 'Dosen', 'mahasiswa' => 'Mahasiswa'] as $roleKey => $roleLabel)
                    <button onclick="showTab('{{ $roleKey }}')" id="tab-{{ $roleKey }}" 
                            class="role-tab flex-1 py-3 px-4 text-center text-sm font-medium border-b-2 transition {{ $roleKey === 'admin' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                        {{ $roleLabel }} ({{ $usersByRole[$roleKey]->count() }})
                    </button>
                    @endforeach
                </nav>
            </div>
            @endif

            <!-- Main Content -->
            <div class="bg-white shadow-md {{ request()->hasAny(['role', 'class_room_id', 'is_active', 'search']) ? 'rounded-lg' : 'rounded-b-lg' }}">
                <div class="p-4">
                    
                    @if(!request()->hasAny(['role', 'class_room_id', 'is_active', 'search']))
                        <!-- Tab Content -->
                        @foreach(['admin', 'koordinator', 'dosen', 'mahasiswa'] as $roleType)
                        <div id="content-{{ $roleType }}" class="tab-content {{ $roleType === 'admin' ? '' : 'hidden' }}">
                            @if($usersByRole[$roleType]->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full">
                                        <thead>
                                            <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase">
                                                <th class="px-4 py-3 font-medium">No</th>
                                                <th class="px-4 py-3 font-medium">User</th>
                                                @if($roleType === 'mahasiswa')
                                                <th class="px-4 py-3 font-medium">Kelas</th>
                                                @endif
                                                <th class="px-4 py-3 font-medium">Status</th>
                                                <th class="px-4 py-3 font-medium text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach($usersByRole[$roleType] as $index => $user)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-3 text-sm text-gray-600">{{ $index + 1 }}</td>
                                                <td class="px-4 py-3">
                                                    <div class="flex items-center gap-3">
                                                        <div class="h-9 w-9 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-medium">
                                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                                        </div>
                                                        <div>
                                                            <p class="font-medium text-gray-800">{{ $user->name }}</p>
                                                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                                            @if($roleType === 'mahasiswa' && $user->nim)
                                                            <p class="text-xs text-blue-600">NIM: {{ $user->nim }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                @if($roleType === 'mahasiswa')
                                                <td class="px-4 py-3 text-sm">
                                                    @if($user->classRoom)
                                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded">{{ $user->classRoom->name }}</span>
                                                    @else
                                                        <span class="text-gray-400 text-xs">-</span>
                                                    @endif
                                                </td>
                                                @endif
                                                <td class="px-4 py-3">
                                                    <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="px-2 py-1 text-xs rounded {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                                        </button>
                                                    </form>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <div class="flex items-center justify-center gap-1">
                                                        <a href="{{ route('admin.users.show', $user) }}" class="p-2 text-green-600 hover:bg-green-50 rounded" title="Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        @if($user->id !== auth()->id())
                                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="delete-btn p-2 text-red-600 hover:bg-red-50 rounded" data-name="{{ $user->name }}" title="Hapus">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-user-slash text-4xl mb-2 text-gray-300"></i>
                                    <p>Tidak ada {{ $roleType }}</p>
                                </div>
                            @endif
                        </div>
                        @endforeach
                    @else
                        <!-- Filtered Results -->
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-sm text-gray-600">Menampilkan {{ $users->count() }} dari {{ $users->total() }} user</span>
                        </div>

                        @if($users->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase">
                                        <th class="px-4 py-3 font-medium">No</th>
                                        <th class="px-4 py-3 font-medium">User</th>
                                        <th class="px-4 py-3 font-medium">Role</th>
                                        <th class="px-4 py-3 font-medium">Kelas</th>
                                        <th class="px-4 py-3 font-medium">Status</th>
                                        <th class="px-4 py-3 font-medium text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($users as $index => $user)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="h-9 w-9 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-medium">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-800">{{ $user->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                                    @if($user->role === 'mahasiswa' && $user->nim)
                                                    <p class="text-xs text-blue-600">NIM: {{ $user->nim }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs rounded
                                                {{ $user->role === 'admin' ? 'bg-red-100 text-red-700' : '' }}
                                                {{ $user->role === 'koordinator' ? 'bg-orange-100 text-orange-700' : '' }}
                                                {{ $user->role === 'dosen' ? 'bg-purple-100 text-purple-700' : '' }}
                                                {{ $user->role === 'mahasiswa' ? 'bg-green-100 text-green-700' : '' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($user->classRoom)
                                                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded">{{ $user->classRoom->name }}</span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-2 py-1 text-xs rounded {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex items-center justify-center gap-1">
                                                <a href="{{ route('admin.users.show', $user) }}" class="p-2 text-green-600 hover:bg-green-50 rounded" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($user->id !== auth()->id())
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="delete-btn p-2 text-red-600 hover:bg-red-50 rounded" data-name="{{ $user->name }}" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $users->appends(request()->except('page'))->links() }}
                        </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-search text-4xl mb-2 text-gray-300"></i>
                                <p>Tidak ada user ditemukan</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(role) {
            document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
            document.querySelectorAll('.role-tab').forEach(t => {
                t.classList.remove('border-blue-600', 'text-blue-600');
                t.classList.add('border-transparent', 'text-gray-500');
            });
            
            document.getElementById('content-' + role).classList.remove('hidden');
            const tab = document.getElementById('tab-' + role);
            tab.classList.add('border-blue-600', 'text-blue-600');
            tab.classList.remove('border-transparent', 'text-gray-500');
        }
    </script>

    @push('scripts')
    <script>
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const name = this.dataset.name;
                const form = this.closest('.delete-form');
                
                Swal.fire({
                    title: 'Hapus User?',
                    text: `Hapus "${name}" dari sistem?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
