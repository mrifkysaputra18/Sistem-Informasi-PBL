<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Manajemen Kriteria Penilaian') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('ahp.index') }}" 
                   class="bg-secondary-500 hover:bg-secondary-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                    <i class="fas fa-calculator mr-2"></i>Hitung Bobot AHP
                </a>
                <a href="{{ route('criteria.create') }}" 
                   class="bg-primary-500 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i>Tambah Kriteria
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

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-xl shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-primary-100 text-sm font-medium">Total Kriteria</p>
                            <p class="text-3xl font-bold">{{ $criteria->total() }}</p>
                        </div>
                        <div class="bg-primary-400 bg-opacity-50 p-3 rounded-full">
                            <i class="fas fa-list-check text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 rounded-xl shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Kriteria Benefit</p>
                            <p class="text-3xl font-bold">{{ $criteria->where('tipe', 'benefit')->count() }}</p>
                        </div>
                        <div class="bg-green-400 bg-opacity-50 p-3 rounded-full">
                            <i class="fas fa-arrow-trend-up text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-red-500 to-red-600 p-6 rounded-xl shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-red-100 text-sm font-medium">Kriteria Cost</p>
                            <p class="text-3xl font-bold">{{ $criteria->where('tipe', 'cost')->count() }}</p>
                        </div>
                        <div class="bg-red-400 bg-opacity-50 p-3 rounded-full">
                            <i class="fas fa-arrow-trend-down text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-table mr-2 text-gray-600"></i>Daftar Kriteria
                        </h3>
                        <div class="text-sm text-gray-600">
                            Showing {{ $criteria->count() }} of {{ $criteria->total() }} entries
                        </div>
                    </div>

                    @if($criteria->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-hashtag mr-1"></i>No
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-tag mr-1"></i>Nama Kriteria
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-weight-hanging mr-1"></i>Bobot
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-chart-line mr-1"></i>Tipe
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-users mr-1"></i>Segment
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-cogs mr-1"></i>Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($criteria as $index => $criterion)
                                        <tr class="hover:bg-gray-50 transition duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ ($criteria->currentPage() - 1) * $criteria->perPage() + $index + 1 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $criterion->nama }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-center">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary-100 text-primary-800">
                                                        <i class="fas fa-weight-hanging mr-1.5"></i>
                                                        {{ number_format($criterion->bobot, 2) }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($criterion->tipe == 'benefit')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-arrow-up mr-1"></i>Benefit
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <i class="fas fa-arrow-down mr-1"></i>Cost
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $criterion->segment == 'group' ? 'bg-primary-100 text-primary-800' : 'bg-secondary-100 text-secondary-800' }}">
                                                    <i class="fas {{ $criterion->segment == 'group' ? 'fa-users' : 'fa-user' }} mr-1"></i>
                                                    {{ ucfirst($criterion->segment) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <div class="flex items-center justify-center space-x-2">
                                                    <a href="{{ route('criteria.edit', $criterion) }}" 
                                                       class="inline-flex items-center px-3 py-2 text-sm font-medium text-primary-600 bg-primary-100 hover:bg-primary-200 hover:text-primary-900 rounded-lg transition duration-200 ease-in-out">
                                                        <i class="fas fa-edit mr-1.5"></i>
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('criteria.destroy', $criterion) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                onclick="return confirm('Yakin ingin menghapus kriteria ini?')"
                                                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 bg-red-100 hover:bg-red-200 hover:text-red-900 rounded-lg transition duration-200 ease-in-out">
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
                            {{ $criteria->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-400 mb-4">
                                <i class="fas fa-inbox text-6xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada kriteria</h3>
                            <p class="text-gray-500 mb-4">Mulai dengan menambahkan kriteria penilaian pertama Anda.</p>
                            <a href="{{ route('criteria.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-700 text-white font-bold rounded-lg shadow-md transition duration-300">
                                <i class="fas fa-plus mr-2"></i>Tambah Kriteria Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
