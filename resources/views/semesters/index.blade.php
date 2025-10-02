<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Semester') }}
            </h2>
            <a href="{{ route('semesters.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-plus mr-2"></i>Tambah Semester
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Daftar Semester</h3>
                    
                    @if($semesters->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun Ajaran</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($semesters as $semester)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-sm font-semibold text-blue-600">{{ $semester->number }}</span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $semester->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $semester->code }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="text-sm">{{ $semester->academicYear->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $semester->academicYear->code }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="text-sm">
                                            <i class="fas fa-calendar-start text-gray-400 mr-1"></i>
                                            {{ $semester->start_date->format('d M Y') }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <i class="fas fa-calendar-end text-gray-400 mr-1"></i>
                                            {{ $semester->end_date->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($semester->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Aktif
                                        </span>
                                        @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-pause-circle mr-1"></i>Tidak Aktif
                                        </span>
                                        @endif
                                        
                                        @if($semester->isPbl())
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <i class="fas fa-project-diagram mr-1"></i>PBL
                                            </span>
                                        </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="text-sm">
                                            <i class="fas fa-school text-gray-400 mr-1"></i>
                                            {{ $semester->classrooms_count ?? 0 }} Kelas
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-2">
                                            <!-- View Button -->
                                            <a href="{{ route('semesters.show', $semester) }}" 
                                               class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-100 hover:bg-blue-200 hover:text-blue-900 rounded-lg transition duration-200 ease-in-out"
                                               title="Lihat Detail">
                                                <i class="fas fa-eye mr-1"></i>
                                                Lihat
                                            </a>
                                            
                                            <!-- Edit Button -->
                                            <a href="{{ route('semesters.edit', $semester) }}" 
                                               class="inline-flex items-center px-3 py-2 text-sm font-medium text-yellow-600 bg-yellow-100 hover:bg-yellow-200 hover:text-yellow-900 rounded-lg transition duration-200 ease-in-out"
                                               title="Edit Semester">
                                                <i class="fas fa-edit mr-1"></i>
                                                Edit
                                            </a>
                                            
                                            <!-- Delete Button -->
                                            <form action="{{ route('semesters.destroy', $semester) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus semester \'{{ $semester->name }}\'?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 bg-red-100 hover:bg-red-200 hover:text-red-900 rounded-lg transition duration-200 ease-in-out"
                                                        title="Hapus Semester">
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
                    @if($semesters->hasPages())
                    <div class="mt-6">
                        {{ $semesters->links() }}
                    </div>
                    @endif
                    @else
                    <div class="text-center py-8 bg-gray-50 rounded-lg">
                        <div class="text-gray-400 mb-3"><i class="fas fa-graduation-cap text-4xl"></i></div>
                        <p class="text-gray-600 mb-3">Belum ada semester</p>
                        <a href="{{ route('semesters.create') }}" class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white text-sm px-4 py-2 rounded">
                            <i class="fas fa-plus mr-2"></i>Tambah Semester Pertama
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
