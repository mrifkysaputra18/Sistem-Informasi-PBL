<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tahun Ajaran') }}
            </h2>
            <a href="{{ route('academic-years.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-plus mr-2"></i>Tambah Tahun Ajaran
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
                    <h3 class="text-lg font-semibold mb-4">Daftar Tahun Ajaran</h3>
                    
                    @if($academicYears->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun Ajaran</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($academicYears as $academicYear)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $academicYear->name }}</div>
                                        @if($academicYear->description)
                                        <div class="text-sm text-gray-500">{{ Str::limit($academicYear->description, 50) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $academicYear->code }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="text-sm">
                                            <i class="fas fa-calendar-start text-gray-400 mr-1"></i>
                                            {{ $academicYear->start_date->format('d M Y') }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <i class="fas fa-calendar-end text-gray-400 mr-1"></i>
                                            {{ $academicYear->end_date->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($academicYear->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Aktif
                                        </span>
                                        @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-pause-circle mr-1"></i>Tidak Aktif
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="text-sm">
                                            <i class="fas fa-graduation-cap text-gray-400 mr-1"></i>
                                            {{ $academicYear->semesters_count ?? 0 }} Semester
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-2">
                                            <!-- View Button -->
                                            <a href="{{ route('academic-years.show', $academicYear) }}" 
                                               class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-100 hover:bg-blue-200 hover:text-blue-900 rounded-lg transition duration-200 ease-in-out"
                                               title="Lihat Detail">
                                                <i class="fas fa-eye mr-1"></i>
                                                Lihat
                                            </a>
                                            
                                            <!-- Edit Button -->
                                            <a href="{{ route('academic-years.edit', $academicYear) }}" 
                                               class="inline-flex items-center px-3 py-2 text-sm font-medium text-yellow-600 bg-yellow-100 hover:bg-yellow-200 hover:text-yellow-900 rounded-lg transition duration-200 ease-in-out"
                                               title="Edit Tahun Ajaran">
                                                <i class="fas fa-edit mr-1"></i>
                                                Edit
                                            </a>
                                            
                                            <!-- Delete Button -->
                                            <form action="{{ route('academic-years.destroy', $academicYear) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus tahun ajaran \'{{ $academicYear->name }}\'?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 bg-red-100 hover:bg-red-200 hover:text-red-900 rounded-lg transition duration-200 ease-in-out"
                                                        title="Hapus Tahun Ajaran">
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
                    @if($academicYears->hasPages())
                    <div class="mt-6">
                        {{ $academicYears->links() }}
                    </div>
                    @endif
                    @else
                    <div class="text-center py-8 bg-gray-50 rounded-lg">
                        <div class="text-gray-400 mb-3"><i class="fas fa-calendar-alt text-4xl"></i></div>
                        <p class="text-gray-600 mb-3">Belum ada tahun ajaran</p>
                        <a href="{{ route('academic-years.create') }}" class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white text-sm px-4 py-2 rounded">
                            <i class="fas fa-plus mr-2"></i>Tambah Tahun Ajaran Pertama
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
