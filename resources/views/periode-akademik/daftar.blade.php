<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="space-y-1">
                <h2 class="font-bold text-2xl text-white leading-tight flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ __('Periode Akademik') }}
                </h2>
                <p class="text-sm text-white/90">Kelola tahun ajaran dan semester PBL</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('academic-periods.create') }}" class="bg-primary-500 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fa-solid fa-circle-plus mr-2"></i>Tambah Periode
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <i class="fa-solid fa-circle-exclamation mr-2"></i>{{ session('error') }}
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Daftar Periode Akademik</h3>
                    
                    @if($academicPeriods->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode Akademik</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun Ajaran</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($academicPeriods as $period)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $period->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $period->code }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                            {{ $period->academic_year }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-secondary-100 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-sm font-semibold text-secondary-600">{{ $period->semester_number }}</span>
                                            </div>
                                            @if($period->isPbl())
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fa-solid fa-diagram-project mr-1"></i>PBL
                                            </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="text-sm">
                                            <i class="fa-solid fa-calendar-days text-gray-400 mr-1"></i>
                                            {{ $period->start_date->format('d M Y') }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <i class="fa-solid fa-calendar-days text-gray-400 mr-1"></i>
                                            {{ $period->end_date->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($period->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fa-solid fa-circle-check mr-1"></i>Aktif
                                        </span>
                                        @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fa-solid fa-circle-pause mr-1"></i>Tidak Aktif
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="text-sm">
                                            <i class="fa-solid fa-chalkboard-user text-gray-400 mr-1"></i>
                                            {{ $period->classrooms_count ?? 0 }} Kelas
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-2">
                                            <!-- Edit Button -->
                                            <a href="{{ route('academic-periods.edit', $period) }}" 
                                               class="inline-flex items-center px-3 py-2 text-sm font-medium text-yellow-600 bg-yellow-100 hover:bg-yellow-200 hover:text-yellow-900 rounded-lg transition duration-200 ease-in-out"
                                               title="Edit Periode">
                                                <i class="fa-solid fa-pen-to-square mr-1"></i>
                                                Edit
                                            </a>
                                            
                                            <!-- Delete Button -->
                                            <form action="{{ route('academic-periods.destroy', $period) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus periode \'{{ $period->name }}\'?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 bg-red-100 hover:bg-red-200 hover:text-red-900 rounded-lg transition duration-200 ease-in-out"
                                                        title="Hapus Periode">
                                                    <i class="fa-solid fa-trash-can mr-1"></i>
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
                    @if($academicPeriods->hasPages())
                    <div class="mt-6">
                        {{ $academicPeriods->links() }}
                    </div>
                    @endif
                    @else
                    <div class="text-center py-8 bg-gray-50 rounded-lg">
                        <div class="text-gray-400 mb-3"><i class="fa-solid fa-calendar-days text-4xl"></i></div>
                        <p class="text-gray-600 mb-3">Belum ada periode akademik</p>
                        <a href="{{ route('academic-periods.create') }}" class="inline-flex items-center bg-primary-500 hover:bg-primary-600 text-white text-sm px-4 py-2 rounded">
                            <i class="fa-solid fa-circle-plus mr-2"></i>Tambah Periode Akademik Pertama
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

