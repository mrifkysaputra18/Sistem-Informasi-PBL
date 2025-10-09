<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-white leading-tight">
                    Manajemen Mata Kuliah
                </h2>
                <p class="text-sm text-gray-600 mt-1">Kelola mata kuliah yang terkait dengan PBL</p>
            </div>
            <a href="{{ route('projects.create') }}" 
               class="bg-primary-500 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                <i class="fas fa-plus mr-2"></i>Tambah Mata Kuliah
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg shadow-md">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Search -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <form method="GET" action="{{ route('projects.index') }}" class="flex gap-4">
                    <!-- Search -->
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cari Mata Kuliah</label>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Nama atau kode mata kuliah..."
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500">
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-end">
                        <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white py-2 px-6 rounded-md">
                            <i class="fas fa-search mr-2"></i>Cari
                        </button>
                    </div>
                </form>
            </div>

            <!-- Subjects List -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                @if($subjects->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kode
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Mata Kuliah
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status PBL
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah Kelas
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($subjects as $subject)
                                <tr class="hover:bg-gray-50 transition duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $subject->id }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $subject->title }}</div>
                                        @if($subject->description)
                                        <div class="text-sm text-gray-500">{{ Str::limit($subject->description, 50) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if(true)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Terkait PBL
                                        </span>
                                        @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-times-circle mr-1"></i>Tidak Terkait
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if(($subject->status === 'active'))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                            <i class="fas fa-check mr-1"></i>Aktif
                                        </span>
                                        @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-ban mr-1"></i>Tidak Aktif
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            <i class="fas fa-school text-gray-400 mr-1"></i>
                                            {{ $subject->class_rooms_count ?? 0 }} Kelas
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-2">
                                            <!-- View Button -->
                                            <a href="{{ route('projects.show', $subject) }}" 
                                               class="inline-flex items-center px-3 py-2 text-sm font-medium text-primary-600 bg-primary-100 hover:bg-primary-200 hover:text-primary-900 rounded-lg transition duration-200 ease-in-out"
                                               title="Lihat Detail">
                                                <i class="fas fa-eye mr-1"></i>
                                                Lihat
                                            </a>
                                            
                                            <!-- Edit Button -->
                                            <a href="{{ route('projects.edit', $subject) }}" 
                                               class="inline-flex items-center px-3 py-2 text-sm font-medium text-yellow-600 bg-yellow-100 hover:bg-yellow-200 hover:text-yellow-900 rounded-lg transition duration-200 ease-in-out"
                                               title="Edit Mata Kuliah">
                                                <i class="fas fa-edit mr-1"></i>
                                                Edit
                                            </a>
                                            
                                            <!-- Delete Button -->
                                            <form action="{{ route('projects.destroy', $subject) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus mata kuliah \'{{ $subject->title }}\'?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 bg-red-100 hover:bg-red-200 hover:text-red-900 rounded-lg transition duration-200 ease-in-out"
                                                        title="Hapus Mata Kuliah">
                                                    <i class="fas fa-trash mr-1"></i>
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
                    <div class="bg-gray-50 px-6 py-4">
                        {{ $subjects->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-400 mb-4">
                            <i class="fas fa-book text-6xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada mata kuliah</h3>
                        <p class="text-gray-500 mb-4">Mulai dengan menambahkan mata kuliah pertama.</p>
                        <a href="{{ route('projects.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-700 text-white rounded-md">
                            <i class="fas fa-plus mr-2"></i>Tambah Mata Kuliah
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>


