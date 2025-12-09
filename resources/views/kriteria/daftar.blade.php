<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- 1. HEADER SECTION -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">MANAJEMEN KRITERIA</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">Atur kriteria penilaian dan pembobotan menggunakan metode AHP.</p>
                </div>
                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('ahp.index') }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-orange-500 hover:bg-orange-600 border-2 border-orange-700 rounded-lg font-bold text-white text-sm shadow-lg transform hover:-translate-y-1 transition-all">
                        <i class="fa-solid fa-calculator mr-2 text-lg"></i>
                        <span>Hitung Bobot AHP</span>
                    </a>
                    <a href="{{ route('criteria.create') }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 border-2 border-indigo-800 rounded-lg font-bold text-white text-sm shadow-lg transform hover:-translate-y-1 transition-all">
                        <i class="fa-solid fa-plus mr-2 text-lg"></i>
                        <span>Tambah Kriteria</span>
                    </a>
                </div>
            </div>

            <!-- Alert Messages -->
            @if(session('ok'))
                <div x-data="{ show: true }" x-show="show" class="bg-emerald-100 border-l-8 border-emerald-600 text-emerald-800 px-6 py-4 rounded-lg shadow-md mb-8 flex items-start justify-between">
                    <div class="flex items-center">
                        <i class="fa-solid fa-check-circle text-2xl mr-4 text-emerald-600"></i>
                        <span class="font-bold text-lg">{{ session('ok') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-600 hover:text-emerald-800"><i class="fa-solid fa-times text-xl"></i></button>
                </div>
            @endif

            <!-- 2. STATS CARDS (Indigo Theme) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Total Kriteria -->
                <div class="bg-white rounded-xl shadow-md border-b-4 border-indigo-600 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Total Kriteria</p>
                        <p class="text-3xl font-black text-gray-800">{{ $criteria->total() }}</p>
                    </div>
                    <div class="p-3 bg-indigo-100 rounded-full text-indigo-600">
                        <i class="fa-solid fa-list-check text-2xl"></i>
                    </div>
                </div>

                <!-- Kriteria Benefit -->
                <div class="bg-white rounded-xl shadow-md border-b-4 border-emerald-600 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Kriteria Benefit</p>
                        <p class="text-3xl font-black text-gray-800">{{ $criteria->where('tipe', 'benefit')->count() }}</p>
                        <p class="text-xs text-emerald-600 font-bold mt-1">Lebih tinggi lebih baik</p>
                    </div>
                    <div class="p-3 bg-emerald-100 rounded-full text-emerald-600">
                        <i class="fa-solid fa-arrow-trend-up text-2xl"></i>
                    </div>
                </div>

                <!-- Kriteria Cost -->
                <div class="bg-white rounded-xl shadow-md border-b-4 border-rose-600 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Kriteria Cost</p>
                        <p class="text-3xl font-black text-gray-800">{{ $criteria->where('tipe', 'cost')->count() }}</p>
                        <p class="text-xs text-rose-600 font-bold mt-1">Lebih rendah lebih baik</p>
                    </div>
                    <div class="p-3 bg-rose-100 rounded-full text-rose-600">
                        <i class="fa-solid fa-arrow-trend-down text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- 3. DATA TABLE -->
            <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    @if($criteria->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-900">
                                <tr>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-white uppercase tracking-wider w-16">No</th>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-white uppercase tracking-wider">Nama Kriteria</th>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-white uppercase tracking-wider">Bobot Penilaian</th>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-white uppercase tracking-wider">Tipe & Segment</th>
                                    <th class="px-6 py-5 text-center text-xs font-bold text-white uppercase tracking-wider w-64 border-l border-gray-800">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($criteria as $index => $criterion)
                                <tr class="hover:bg-indigo-50 transition-colors group">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-bold">{{ ($criteria->currentPage() - 1) * $criteria->perPage() + $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-black text-gray-900 group-hover:text-indigo-700">{{ $criterion->nama }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="text-lg font-black text-indigo-600 mr-2">{{ number_format($criterion->bobot, 3) }}</span>
                                            <div class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-indigo-500" style="width: {{ $criterion->bobot * 100 }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col gap-1">
                                            @if($criterion->tipe == 'benefit')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 w-fit uppercase tracking-wide">
                                                    <i class="fa-solid fa-arrow-up mr-1.5"></i> Benefit
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200 w-fit uppercase tracking-wide">
                                                    <i class="fa-solid fa-arrow-down mr-1.5"></i> Cost
                                                </span>
                                            @endif
                                            
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200 w-fit uppercase tracking-wide">
                                                <i class="fa-solid {{ $criterion->segment == 'group' ? 'fa-users' : 'fa-user' }} mr-1.5"></i> {{ ucfirst($criterion->segment) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center border-l border-gray-100">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('criteria.edit', $criterion) }}" 
                                               class="inline-flex items-center justify-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-bold rounded shadow-sm hover:shadow transition-all uppercase tracking-wide">
                                                <i class="fas fa-edit mr-1.5"></i> Edit
                                            </a>
                                            
                                            <form action="{{ route('criteria.destroy', $criterion) }}" method="POST" class="inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        class="delete-btn inline-flex items-center justify-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-[10px] font-bold rounded shadow-sm hover:shadow transition-all uppercase tracking-wide"
                                                        data-name="{{ $criterion->nama }}">
                                                    <i class="fas fa-trash mr-1.5"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="px-6 py-6 bg-gray-50 border-t border-gray-200">
                            {{ $criteria->links() }}
                        </div>
                    @else
                        <div class="py-24 text-center">
                            <div class="inline-block p-6 rounded-full bg-gray-100 mb-4 border border-gray-200">
                                <i class="fas fa-list-check text-5xl text-gray-300"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">Belum Ada Kriteria</h3>
                            <p class="text-gray-500 mt-2">Silakan tambahkan kriteria baru untuk memulai penilaian.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle delete button clicks
            const deleteButtons = document.querySelectorAll('.delete-btn');
            
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const name = this.getAttribute('data-name');
                    const form = this.closest('.delete-form');
                    
                    Swal.fire({
                        title: 'Hapus Kriteria?',
                        html: `Anda akan menghapus kriteria <b>"${name}"</b>.<br>Ini dapat mempengaruhi perhitungan nilai yang ada!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
    @endpush
</x-app-layout>