<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="space-y-1">
                <h2 class="font-bold text-2xl text-white leading-tight flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    {{ __('Manajemen Kelompok') }}
                </h2>
                <p class="text-sm text-white/90">Kelola kelompok mahasiswa dan anggota untuk project PBL</p>
            </div>
            <div class="flex gap-2">
                @if(auth()->user()->isAdmin())
                <a href="{{ route('import.groups') }}" 
                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                    <i class="fas fa-file-excel mr-2"></i>Import Excel
                </a>
                @endif
                <a href="{{ route('groups.create', ['classroom' => request('classroom')]) }}" 
                   class="bg-primary-500 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i>Tambah Kelompok
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Success -->
            @if(session('ok'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg shadow-md">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ session('ok') }}</span>
                    </div>
                </div>
            @endif

            <!-- Filter hanya tampil pada halaman awal (tanpa filter kelas terpilih) -->
            @if(!request()->filled('classroom'))
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <form method="GET" action="{{ route('groups.index') }}" class="flex gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Kelas</label>
                            <select name="classroom" class="w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-500 focus:ring-secondary-500">
                                <option value="">Semua Kelas</option>
                                @foreach($classRooms as $classroom)
                                <option value="{{ $classroom->id }}" {{ request('classroom') == $classroom->id ? 'selected' : '' }}>
                                    {{ $classroom->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="bg-secondary-500 hover:bg-secondary-600 text-white py-2 px-6 rounded-md">
                                <i class="fas fa-filter mr-2"></i>Filter
                            </button>
                        </div>
                    </form>
                </div>
            @else
                @php
                    $selectedClass = $classRooms->firstWhere('id', request('classroom'));
                @endphp
                <div class="bg-white rounded-lg shadow-md p-6 mb-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Menampilkan kelompok untuk kelas:</p>
                        <p class="text-xl font-semibold text-gray-900 mt-1">
                            {{ $selectedClass?->name ?? 'Kelas tidak ditemukan' }}
                        </p>
                    </div>
                    <a href="{{ route('classrooms.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition">
                        <i class="fas fa-undo mr-2"></i>Ganti Kelas
                    </a>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Card 1: Total Kelompok -->
                <div class="group relative bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg hover:shadow-2xl p-6 text-white transition-all duration-300 hover:scale-105 cursor-pointer overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    
                    <div class="relative flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-blue-100 text-xs font-medium uppercase tracking-wider">Total Kelompok</p>
                            <p class="text-4xl font-black mt-3 mb-1 group-hover:scale-110 transition-transform duration-300">{{ $groups->total() }}</p>
                            <p class="text-xs text-blue-100 mt-2">Kelompok terdaftar</p>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl group-hover:rotate-12 transition-transform duration-300">
                            <i class="fa-solid fa-users text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Card 2: Kelompok Aktif -->
                <div class="group relative bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-lg hover:shadow-2xl p-6 text-white transition-all duration-300 hover:scale-105 cursor-pointer overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    
                    <div class="relative flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-green-100 text-xs font-medium uppercase tracking-wider">Kelompok Aktif</p>
                            <p class="text-4xl font-black mt-3 mb-1 group-hover:scale-110 transition-transform duration-300">{{ $groups->count() }}</p>
                            <p class="text-xs text-green-100 mt-2">Kelompok aktif</p>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl group-hover:rotate-12 transition-transform duration-300">
                            <i class="fa-solid fa-circle-check text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Card 3: Kelompok Penuh -->
                <div class="group relative bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg hover:shadow-2xl p-6 text-white transition-all duration-300 hover:scale-105 cursor-pointer overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    
                    <div class="relative flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-purple-100 text-xs font-medium uppercase tracking-wider">Kelompok Penuh</p>
                            <p class="text-4xl font-black mt-3 mb-1 group-hover:scale-110 transition-transform duration-300">{{ $groups->filter(fn($g) => $g->isFull())->count() }}</p>
                            <p class="text-xs text-purple-100 mt-2">Anggota lengkap</p>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl group-hover:rotate-12 transition-transform duration-300">
                            <i class="fa-solid fa-user-check text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-table mr-2 text-gray-600"></i>Daftar Kelompok
                        </h3>
                        <div class="text-sm text-gray-700 font-medium">
                            Showing {{ $groups->count() }} of {{ $groups->total() }} entries
                        </div>
                    </div>

                    @if($groups->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-hashtag mr-1"></i>No
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-users mr-1"></i>Nama Kelompok
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-school mr-1"></i>Kelas
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-user-tie mr-1"></i>Ketua
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-users-cog mr-1"></i>Anggota
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-cogs mr-1"></i>Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($groups as $index => $group)
                                        <tr class="hover:bg-gray-50 transition duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ ($groups->currentPage() - 1) * $groups->perPage() + $index + 1 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $group->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($group->classRoom)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                                    {{ $group->classRoom->name }}
                                                </span>
                                                @else
                                                <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($group->leader)
                                                <div class="flex items-center">
                                                    <span class="text-primary-600 mr-1">★</span>
                                                    <div class="text-sm text-gray-900">{{ $group->leader->name }}</div>
                                                </div>
                                                @else
                                                <span class="text-gray-400">Belum ada</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $group->isFull() ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                    <i class="fas fa-users mr-1"></i>{{ $group->members->count() }}/{{ $group->max_members }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <div class="flex items-center justify-center space-x-2">
                                                    <a href="{{ route('groups.show', $group) }}" 
                                                       class="inline-flex items-center px-3 py-2 text-sm font-medium text-green-600 bg-green-100 hover:bg-green-200 hover:text-green-900 rounded-lg transition duration-200 ease-in-out">
                                                        <i class="fas fa-eye mr-1.5"></i>
                                                        Detail
                                                    </a>
                                                    <a href="{{ route('groups.edit', $group) }}" 
                                                       class="inline-flex items-center px-3 py-2 text-sm font-medium text-primary-600 bg-primary-100 hover:bg-primary-200 hover:text-primary-900 rounded-lg transition duration-200 ease-in-out">
                                                        <i class="fas fa-edit mr-1.5"></i>
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('groups.destroy', $group) }}" method="POST" class="inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" 
                                                                class="delete-btn inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 bg-red-100 hover:bg-red-200 hover:text-red-900 rounded-lg transition duration-200 ease-in-out"
                                                                data-group-name="{{ $group->name }}">
                                                            <i class="fas fa-trash mr-1.5"></i>
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div class="text-sm text-gray-700 font-medium">
                                    Menampilkan <span class="font-semibold text-primary-600">{{ $groups->firstItem() ?? 0 }}</span> 
                                    sampai <span class="font-semibold text-primary-600">{{ $groups->lastItem() ?? 0 }}</span> 
                                    dari <span class="font-semibold text-primary-600">{{ $groups->total() }}</span> kelompok
                                </div>
                                <div>
                                    {{ $groups->links('pagination::tailwind') }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-400 mb-4">
                                <i class="fas fa-users text-6xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada kelompok</h3>
                            <p class="text-gray-500 mb-4">Mulai dengan menambahkan kelompok pertama Anda.</p>
                            <a href="{{ route('groups.create', ['classroom' => request('classroom')]) }}" 
                               class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-700 text-white font-bold rounded-lg shadow-md transition duration-300">
                                <i class="fas fa-plus mr-2"></i>Tambah Kelompok Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        /* Custom Pagination Styling - Fix spacing and color */
        nav[role="navigation"] {
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            gap: 0.75rem !important;
        }
        
        nav[role="navigation"] > span,
        nav[role="navigation"] > a {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 2.75rem !important;
            height: 2.75rem !important;
            padding: 0.5rem !important;
            margin: 0 !important;
            font-size: 0.875rem !important;
            font-weight: 600 !important;
            border-radius: 0.5rem !important;
            transition: all 0.2s ease-in-out !important;
        }
        
        /* Pagination links - BLUE COLOR */
        nav[role="navigation"] > a {
            background-color: #ffffff !important;
            color: #3b82f6 !important;
            border: 2px solid #3b82f6 !important;
            text-decoration: none !important;
        }
        
        /* Hover state */
        nav[role="navigation"] > a:hover:not([aria-disabled="true"]) {
            background-color: #3b82f6 !important;
            color: #ffffff !important;
            border-color: #3b82f6 !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 12px -2px rgba(59, 130, 246, 0.4) !important;
        }
        
        /* Active page - BLUE BACKGROUND */
        nav[role="navigation"] > span[aria-current="page"] {
            background-color: #3b82f6 !important;
            color: #ffffff !important;
            border: 2px solid #3b82f6 !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 8px rgba(59, 130, 246, 0.4) !important;
        }
        
        /* Disabled state */
        nav[role="navigation"] > a[aria-disabled="true"],
        nav[role="navigation"] > span[aria-disabled="true"] {
            opacity: 0.4 !important;
            cursor: not-allowed !important;
            background-color: #f3f4f6 !important;
            color: #9ca3af !important;
            border-color: #e5e7eb !important;
        }
        
        /* Remove negative margins from pagination wrapper */
        nav[role="navigation"] .flex {
            gap: 0.75rem !important;
        }
        
        /* Ensure proper spacing */
        nav[role="navigation"] span:not([aria-current]):not([aria-disabled]),
        nav[role="navigation"] a:not([aria-disabled]) {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle delete button clicks
            const deleteButtons = document.querySelectorAll('.delete-btn');
            
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const groupName = this.getAttribute('data-group-name');
                    const form = this.closest('.delete-form');
                    
                    confirmDelete(
                        'Hapus Kelompok?',
                        `Apakah Anda yakin ingin menghapus kelompok <strong>"${groupName}"</strong>?<br><small class="text-gray-500">Tindakan ini tidak dapat dibatalkan.</small>`,
                        form
                    );
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
