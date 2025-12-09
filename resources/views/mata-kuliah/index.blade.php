<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">MATA KULIAH</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">Kelola mata kuliah dan rubrik penilaian.</p>
                </div>
                @if(auth()->user()->isAdmin() || auth()->user()->isKoordinator())
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('mata-kuliah.create') }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 border-2 border-indigo-800 rounded-lg font-bold text-white text-sm shadow-lg transform hover:-translate-y-1 transition-all">
                        <i class="fa-solid fa-plus mr-2 text-lg"></i>
                        <span>Tambah Mata Kuliah</span>
                    </a>
                </div>
                @endif
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="bg-emerald-100 border-l-8 border-emerald-600 text-emerald-800 px-6 py-4 rounded-lg shadow-md mb-8 flex items-start justify-between">
                    <div class="flex items-center">
                        <i class="fa-solid fa-check-circle text-2xl mr-4 text-emerald-600"></i>
                        <span class="font-bold text-lg">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-600 hover:text-emerald-800"><i class="fa-solid fa-times text-xl"></i></button>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-md border-b-4 border-indigo-600 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Total Mata Kuliah</p>
                        <p class="text-3xl font-black text-gray-800">{{ $mataKuliahs->total() }}</p>
                    </div>
                    <div class="p-3 bg-indigo-100 rounded-full text-indigo-600">
                        <i class="fa-solid fa-book text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    @if($mataKuliahs->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-900">
                                <tr>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-white uppercase tracking-wider w-16">No</th>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-white uppercase tracking-wider">Kode</th>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-white uppercase tracking-wider">Nama Mata Kuliah</th>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-white uppercase tracking-wider">SKS</th>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-white uppercase tracking-wider">Dosen</th>
                                    <th class="px-6 py-5 text-left text-xs font-bold text-white uppercase tracking-wider">Rubrik</th>
                                    <th class="px-6 py-5 text-center text-xs font-bold text-white uppercase tracking-wider w-64 border-l border-gray-800">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($mataKuliahs as $index => $mk)
                                <tr class="hover:bg-indigo-50 transition-colors group">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-bold">{{ ($mataKuliahs->currentPage() - 1) * $mataKuliahs->perPage() + $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">
                                            {{ $mk->kode }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-black text-gray-900 group-hover:text-indigo-700">{{ $mk->nama }}</div>
                                        @if($mk->deskripsi)
                                            <div class="text-xs text-gray-500 mt-1 truncate max-w-xs">{{ Str::limit($mk->deskripsi, 50) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-lg font-black text-indigo-600">{{ $mk->sks }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($mk->dosens && $mk->dosens->count() > 0)
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($mk->dosens->take(2) as $dosen)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                                        {{ $dosen->name }}
                                                    </span>
                                                @endforeach
                                                @if($mk->dosens->count() > 2)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-200 text-gray-600">
                                                        +{{ $mk->dosens->count() - 2 }}
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-xs">Belum ada dosen</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php $activeRubrik = $mk->rubrikPenilaians->where('is_active', true)->first(); @endphp
                                        @if($activeRubrik)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                <i class="fa-solid fa-check-circle mr-1"></i> {{ $activeRubrik->nama }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs">Belum ada rubrik aktif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center border-l border-gray-100">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('mata-kuliah.show', $mk) }}" 
                                               class="inline-flex items-center justify-center px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-bold rounded shadow-sm hover:shadow transition-all uppercase tracking-wide">
                                                <i class="fas fa-eye mr-1.5"></i> Lihat
                                            </a>
                                            @if(auth()->user()->isAdmin() || auth()->user()->isKoordinator())
                                            <a href="{{ route('mata-kuliah.edit', $mk) }}" 
                                               class="inline-flex items-center justify-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-bold rounded shadow-sm hover:shadow transition-all uppercase tracking-wide">
                                                <i class="fas fa-edit mr-1.5"></i> Edit
                                            </a>
                                            <form action="{{ route('mata-kuliah.destroy', $mk) }}" method="POST" class="inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        class="delete-btn inline-flex items-center justify-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-[10px] font-bold rounded shadow-sm hover:shadow transition-all uppercase tracking-wide"
                                                        data-name="{{ $mk->nama }}">
                                                    <i class="fas fa-trash mr-1.5"></i> Hapus
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="px-6 py-6 bg-gray-50 border-t border-gray-200">
                            {{ $mataKuliahs->links() }}
                        </div>
                    @else
                        <div class="py-24 text-center">
                            <div class="inline-block p-6 rounded-full bg-gray-100 mb-4 border border-gray-200">
                                <i class="fas fa-book text-5xl text-gray-300"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">Belum Ada Mata Kuliah</h3>
                            <p class="text-gray-500 mt-2">Silakan tambahkan mata kuliah baru.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const name = this.getAttribute('data-name');
                    const form = this.closest('.delete-form');
                    Swal.fire({
                        title: 'Hapus Mata Kuliah?',
                        html: `Anda akan menghapus mata kuliah <b>"${name}"</b>.<br>Semua rubrik terkait juga akan dihapus!`,
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
