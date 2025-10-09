<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Detail User') }}: {{ $user->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.users.edit', $user) }}" 
                   class="bg-primary-500 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <a href="{{ route('admin.users.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - User Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                        <!-- Profile Header -->
                        <div class="bg-gradient-to-r from-blue-500 to-secondary-600 p-6 text-white">
                            <div class="flex flex-col items-center">
                                <div class="w-24 h-24 rounded-full bg-white bg-opacity-20 backdrop-blur-lg flex items-center justify-center text-3xl font-bold mb-3">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <h3 class="text-xl font-bold text-center">{{ $user->name }}</h3>
                                <p class="text-sm text-primary-100 mt-1">{{ $user->email }}</p>
                                @if($user->politala_id)
                                    <p class="text-xs text-primary-100 mt-1 font-mono">ID: {{ $user->politala_id }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- User Info -->
                        <div class="p-6 space-y-4">
                            <!-- Role Badge -->
                            <div class="text-center">
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium 
                                    {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $user->role === 'koordinator' ? 'bg-secondary-100 text-secondary-800' : '' }}
                                    {{ $user->role === 'dosen' ? 'bg-primary-100 text-primary-800' : '' }}
                                    {{ $user->role === 'mahasiswa' ? 'bg-green-100 text-green-800' : '' }}">
                                    <i class="fas fa-user-tag mr-2"></i>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>

                            <!-- Status Badge -->
                            <div class="text-center">
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fas fa-circle text-xs mr-2"></i>
                                    {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </div>

                            <div class="border-t pt-4 space-y-3">
                                @if($user->phone)
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-phone text-gray-400 w-5"></i>
                                    <span class="ml-2 text-gray-700">{{ $user->phone }}</span>
                                </div>
                                @endif

                                @if($user->program_studi)
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-graduation-cap text-gray-400 w-5"></i>
                                    <span class="ml-2 text-gray-700">{{ $user->program_studi }}</span>
                                </div>
                                @endif

                                @if($user->classRoom)
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-school text-gray-400 w-5"></i>
                                    <span class="ml-2 text-gray-700">{{ $user->classRoom->name }}</span>
                                </div>
                                @endif

                                <div class="flex items-center text-sm">
                                    <i class="fas fa-calendar-plus text-gray-400 w-5"></i>
                                    <span class="ml-2 text-gray-700">Bergabung {{ $user->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-lg shadow-xl p-4 mt-6">
                        <h4 class="font-semibold text-gray-800 mb-3">Aksi Cepat</h4>
                        <div class="space-y-2">
                            <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                                    <i class="fas fa-toggle-{{ $user->is_active ? 'off' : 'on' }} mr-2 text-gray-600"></i>
                                    {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} User
                                </button>
                            </form>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
                                  onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full text-left px-3 py-2 rounded-lg hover:bg-red-50 text-red-600 transition">
                                    <i class="fas fa-trash mr-2"></i>Hapus User
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column - Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Groups Information (For Students) -->
                    @if($user->role === 'mahasiswa')
                    <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-blue-500 p-4 text-white">
                            <h3 class="text-lg font-semibold flex items-center">
                                <i class="fas fa-users mr-2"></i>
                                Kelompok
                            </h3>
                        </div>
                        <div class="p-6">
                            @if($user->groups->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($user->groups as $group)
                                    <div class="border rounded-lg p-4 hover:shadow-md transition">
                                        <div class="flex items-start justify-between mb-2">
                                            <div>
                                                <h4 class="font-semibold text-gray-800">{{ $group->name }}</h4>
                                                @if($group->classRoom)
                                                <p class="text-sm text-gray-500">{{ $group->classRoom->name }}</p>
                                                @endif
                                            </div>
                                            @if($group->leader_id === $user->id)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-star mr-1"></i>Ketua
                                            </span>
                                            @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                                Anggota
                                            </span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-600 mt-2">
                                            <i class="fas fa-users text-gray-400 mr-1"></i>
                                            {{ $group->members->count() }} anggota
                                        </div>
                                        <a href="{{ route('groups.show', $group) }}" 
                                           class="inline-flex items-center mt-3 text-sm text-primary-600 hover:text-primary-800">
                                            Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-user-slash text-4xl mb-3 text-gray-300"></i>
                                    <p>Belum masuk kelompok manapun</p>
                                    <a href="{{ route('groups.create') }}" 
                                       class="inline-flex items-center mt-3 px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition">
                                        <i class="fas fa-plus mr-2"></i>Tambahkan ke Kelompok
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Leading Groups (If Leader) -->
                    @if($user->ledGroups->count() > 0)
                    <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-4 text-white">
                            <h3 class="text-lg font-semibold flex items-center">
                                <i class="fas fa-crown mr-2"></i>
                                Kelompok yang Dipimpin
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                @foreach($user->ledGroups as $ledGroup)
                                <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 transition">
                                    <div>
                                        <h4 class="font-medium text-gray-800">{{ $ledGroup->name }}</h4>
                                        @if($ledGroup->classRoom)
                                        <p class="text-sm text-gray-500">{{ $ledGroup->classRoom->name }}</p>
                                        @endif
                                    </div>
                                    <a href="{{ route('groups.show', $ledGroup) }}" 
                                       class="text-primary-600 hover:text-primary-800">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif

                    <!-- Activity Timeline -->
                    <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                        <div class="bg-gradient-to-r from-secondary-500 to-secondary-500 p-4 text-white">
                            <h3 class="text-lg font-semibold flex items-center">
                                <i class="fas fa-history mr-2"></i>
                                Aktivitas Terakhir
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                                            <i class="fas fa-user-plus text-primary-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">User dibuat</p>
                                        <p class="text-sm text-gray-500">{{ $user->created_at->format('d M Y H:i') }}</p>
                                        <p class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>

                                @if($user->updated_at != $user->created_at)
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                            <i class="fas fa-edit text-green-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">Terakhir diupdate</p>
                                        <p class="text-sm text-gray-500">{{ $user->updated_at->format('d M Y H:i') }}</p>
                                        <p class="text-xs text-gray-400">{{ $user->updated_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Card -->
                    <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                        <div class="bg-gradient-to-r from-primary-500 to-blue-500 p-4 text-white">
                            <h3 class="text-lg font-semibold flex items-center">
                                <i class="fas fa-chart-bar mr-2"></i>
                                Statistik
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div class="text-center p-4 bg-primary-50 rounded-lg">
                                    <div class="text-2xl font-bold text-primary-600">{{ $user->groups->count() }}</div>
                                    <div class="text-sm text-gray-600 mt-1">Kelompok</div>
                                </div>
                                @if($user->role === 'mahasiswa')
                                <div class="text-center p-4 bg-green-50 rounded-lg">
                                    <div class="text-2xl font-bold text-green-600">{{ $user->ledGroups->count() }}</div>
                                    <div class="text-sm text-gray-600 mt-1">Sebagai Ketua</div>
                                </div>
                                @endif
                                <div class="text-center p-4 bg-secondary-50 rounded-lg">
                                    <div class="text-2xl font-bold text-secondary-600">
                                        {{ $user->is_active ? 'Aktif' : 'Tidak' }}
                                    </div>
                                    <div class="text-sm text-gray-600 mt-1">Status</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
