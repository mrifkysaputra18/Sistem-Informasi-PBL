<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Manajemen Mahasiswa & User') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Kelola semua user sistem</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.users.without-group') }}" 
                   class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                    <i class="fas fa-user-slash mr-2"></i>Mahasiswa Tanpa Kelompok
                </a>
                <a href="{{ route('admin.users.create') }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i>Tambah User
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Messages -->
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
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Filter -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <form method="GET" action="{{ route('admin.users.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <!-- Search -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-search mr-1"></i>Cari
                            </label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Nama, email, atau ID..." 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Role Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-user-tag mr-1"></i>Role
                            </label>
                            <select name="role" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Semua Role</option>
                                <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                <option value="dosen" {{ request('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                                <option value="koordinator" {{ request('role') == 'koordinator' ? 'selected' : '' }}>Koordinator</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>

                        <!-- Class Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-school mr-1"></i>Kelas
                            </label>
                            <select name="class_room_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Semua Kelas</option>
                                @foreach($classRooms as $classroom)
                                    <option value="{{ $classroom->id }}" {{ request('class_room_id') == $classroom->id ? 'selected' : '' }}>
                                        {{ $classroom->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-toggle-on mr-1"></i>Status
                            </label>
                            <select name="is_active" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Semua Status</option>
                                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mt-4">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-6 rounded-md">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                        @if(request()->hasAny(['search', 'role', 'class_room_id', 'is_active']))
                            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-6 rounded-md">
                                <i class="fas fa-times mr-2"></i>Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-xl shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total User</p>
                            <p class="text-3xl font-bold">{{ $users->total() }}</p>
                        </div>
                        <div class="bg-blue-400 bg-opacity-50 p-3 rounded-full">
                            <i class="fas fa-users text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 rounded-xl shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Mahasiswa</p>
                            <p class="text-3xl font-bold">{{ \App\Models\User::where('role', 'mahasiswa')->count() }}</p>
                        </div>
                        <div class="bg-green-400 bg-opacity-50 p-3 rounded-full">
                            <i class="fas fa-user-graduate text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 rounded-xl shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Dosen</p>
                            <p class="text-3xl font-bold">{{ \App\Models\User::where('role', 'dosen')->count() }}</p>
                        </div>
                        <div class="bg-purple-400 bg-opacity-50 p-3 rounded-full">
                            <i class="fas fa-chalkboard-teacher text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-6 rounded-xl shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm font-medium">Staff</p>
                            <p class="text-3xl font-bold">{{ \App\Models\User::whereIn('role', ['admin', 'koordinator'])->count() }}</p>
                        </div>
                        <div class="bg-orange-400 bg-opacity-50 p-3 rounded-full">
                            <i class="fas fa-user-tie text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-table mr-2 text-gray-600"></i>Daftar User
                        </h3>
                        <div class="text-sm text-gray-600">
                            Showing {{ $users->count() }} of {{ $users->total() }} entries
                        </div>
                    </div>

                    @if($users->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($users as $index => $user)
                                        <tr class="hover:bg-gray-50 transition duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold">
                                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                                        @if($user->politala_id)
                                                            <div class="text-xs text-gray-400">ID: {{ $user->politala_id }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : '' }}
                                                    {{ $user->role === 'koordinator' ? 'bg-purple-100 text-purple-800' : '' }}
                                                    {{ $user->role === 'dosen' ? 'bg-blue-100 text-blue-800' : '' }}
                                                    {{ $user->role === 'mahasiswa' ? 'bg-green-100 text-green-800' : '' }}">
                                                    <i class="fas fa-circle text-xs mr-1"></i>
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($user->classRoom)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-indigo-100 text-indigo-800">
                                                        <i class="fas fa-school mr-1"></i>{{ $user->classRoom->name }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        <i class="fas fa-circle text-xs mr-1"></i>
                                                        {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <div class="flex items-center justify-center space-x-2">
                                                    <a href="{{ route('admin.users.show', $user) }}" 
                                                       class="inline-flex items-center px-3 py-2 text-sm font-medium text-green-600 bg-green-100 hover:bg-green-200 hover:text-green-900 rounded-lg transition">
                                                        <i class="fas fa-eye mr-1.5"></i>Detail
                                                    </a>
                                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                                       class="inline-flex items-center px-3 py-2 text-sm font-medium text-indigo-600 bg-indigo-100 hover:bg-indigo-200 hover:text-indigo-900 rounded-lg transition">
                                                        <i class="fas fa-edit mr-1.5"></i>Edit
                                                    </a>
                                                    @if($user->id !== auth()->id())
                                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    onclick="return confirm('Yakin ingin menghapus user ini?')"
                                                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 bg-red-100 hover:bg-red-200 hover:text-red-900 rounded-lg transition">
                                                                <i class="fas fa-trash mr-1.5"></i>Hapus
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

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $users->appends(request()->except('page'))->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-400 mb-4">
                                <i class="fas fa-users text-6xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada user ditemukan</h3>
                            <p class="text-gray-500 mb-4">Coba ubah filter atau tambahkan user baru.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
