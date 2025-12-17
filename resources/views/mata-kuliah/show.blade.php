<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">
                            {{ $mataKuliah->kode }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-bold bg-gray-100 text-gray-700">
                            {{ $mataKuliah->sks }} SKS
                        </span>
                    </div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">{{ $mataKuliah->nama }}</h2>
                    @if($mataKuliah->deskripsi)
                        <p class="text-sm font-medium text-gray-500 mt-1">{{ $mataKuliah->deskripsi }}</p>
                    @endif
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('mata-kuliah.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-bold rounded-lg shadow transition-all">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
                    </a>
                    @php
                        $canManageRubrik = auth()->user()->isAdmin() || 
                            (auth()->user()->isDosen() && $mataKuliah->isDosenAssigned(auth()->id()));
                    @endphp
                    @if($canManageRubrik)
                    <a href="{{ route('rubrik-penilaian.create', $mataKuliah) }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 border-2 border-emerald-800 rounded-lg font-bold text-white text-sm shadow-lg transform hover:-translate-y-1 transition-all">
                        <i class="fa-solid fa-plus mr-2 text-lg"></i>
                        <span>Buat Rubrik Penilaian</span>
                    </a>
                    @endif
                </div>
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

            @if($errors->any())
                <div class="bg-red-100 border-l-8 border-red-600 text-red-800 px-6 py-4 rounded-lg shadow-md mb-8">
                    <div class="flex items-center">
                        <i class="fa-solid fa-exclamation-circle text-2xl mr-4 text-red-600"></i>
                        <span class="font-bold text-lg">{{ $errors->first() }}</span>
                    </div>
                </div>
            @endif

            <!-- Rubrik Penilaian Section -->
            <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="bg-gray-900 px-6 py-4">
                    <h3 class="text-lg font-bold text-white">
                        <i class="fa-solid fa-clipboard-list mr-2"></i> Daftar Rubrik Penilaian
                    </h3>
                </div>
                
                @if($mataKuliah->rubrikPenilaians->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($mataKuliah->rubrikPenilaians as $rubrik)
                            <div class="p-6 hover:bg-gray-50 transition-colors {{ $rubrik->is_active ? 'bg-emerald-50 border-l-4 border-emerald-500' : '' }}">
                                <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h4 class="text-lg font-bold text-gray-900">{{ $rubrik->nama }}</h4>
                                            @if($rubrik->is_active)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                    <i class="fa-solid fa-check-circle mr-1"></i> AKTIF
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-3">
                                            <span><i class="fa-solid fa-calendar mr-1"></i> {{ $rubrik->periodeAkademik->name ?? '-' }}</span>
                                            <span><i class="fa-solid fa-graduation-cap mr-1"></i> Semester {{ $rubrik->semester }}</span>
                                            <span><i class="fa-solid fa-user mr-1"></i> {{ $rubrik->creator->name ?? '-' }}</span>
                                        </div>
                                        
                                        <!-- Bobot UTS/UAS -->
                                        <div class="flex flex-wrap gap-2 mb-3">
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                                <i class="fa-solid fa-scale-balanced mr-1"></i> UTS: {{ number_format($rubrik->bobot_uts, 0) }}%
                                            </span>
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-orange-100 text-orange-800 border border-orange-200">
                                                <i class="fa-solid fa-scale-balanced mr-1"></i> UAS: {{ number_format($rubrik->bobot_uas, 0) }}%
                                            </span>
                                            <span class="{{ $rubrik->isComplete() ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-red-100 text-red-800 border-red-200' }} inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold border">
                                                @if($rubrik->isComplete())
                                                    <i class="fa-solid fa-check-circle mr-1"></i> Valid
                                                @else
                                                    <i class="fa-solid fa-exclamation-circle mr-1"></i> Belum Lengkap
                                                @endif
                                            </span>
                                        </div>
                                        
                                        <!-- Rubrik Items Preview - Grouped by Periode -->
                                        @if($rubrik->items->count() > 0)
                                            <div class="space-y-2">
                                                <!-- Item UTS -->
                                                @if($rubrik->items->where('periode_ujian', 'uts')->count() > 0)
                                                <div class="flex flex-wrap gap-1 items-center">
                                                    <span class="text-xs font-bold text-blue-700 mr-1">UTS:</span>
                                                    @foreach($rubrik->items->where('periode_ujian', 'uts') as $item)
                                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                                            {{ $item->nama }}: {{ number_format($item->persentase, 0) }}%
                                                        </span>
                                                    @endforeach
                                                </div>
                                                @endif
                                                
                                                <!-- Item UAS -->
                                                @if($rubrik->items->where('periode_ujian', 'uas')->count() > 0)
                                                <div class="flex flex-wrap gap-1 items-center">
                                                    <span class="text-xs font-bold text-orange-700 mr-1">UAS:</span>
                                                    @foreach($rubrik->items->where('periode_ujian', 'uas') as $item)
                                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-orange-50 text-orange-700 border border-orange-200">
                                                            {{ $item->nama }}: {{ number_format($item->persentase, 0) }}%
                                                        </span>
                                                    @endforeach
                                                </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    
                                    @if($canManageRubrik)
                                    <div class="flex flex-wrap gap-2 items-start shrink-0">
                                        @if(!$rubrik->is_active && $rubrik->isComplete())
                                            <form action="{{ route('rubrik-penilaian.activate', [$mataKuliah, $rubrik]) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="inline-flex items-center px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded shadow transition-all whitespace-nowrap">
                                                    <i class="fa-solid fa-check mr-1.5"></i> Gunakan
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('rubrik-penilaian.edit', [$mataKuliah, $rubrik]) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded shadow transition-all whitespace-nowrap">
                                            <i class="fa-solid fa-edit mr-1.5"></i> Edit
                                        </a>
                                        <a href="{{ route('rubrik-penilaian.duplicate', [$mataKuliah, $rubrik]) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold rounded shadow transition-all whitespace-nowrap">
                                            <i class="fa-solid fa-copy mr-1.5"></i> Duplikat
                                        </a>
                                        <form action="{{ route('rubrik-penilaian.destroy', [$mataKuliah, $rubrik]) }}" method="POST" class="inline delete-rubrik-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    class="delete-rubrik-btn inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded shadow transition-all whitespace-nowrap"
                                                    data-name="{{ $rubrik->nama }}">
                                                <i class="fa-solid fa-trash mr-1.5"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-16 text-center">
                        <div class="inline-block p-6 rounded-full bg-gray-100 mb-4 border border-gray-200">
                            <i class="fas fa-clipboard-list text-5xl text-gray-300"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Belum Ada Rubrik Penilaian</h3>
                        <p class="text-gray-500 mt-2">Buat rubrik penilaian untuk mata kuliah ini.</p>
                        @if(auth()->user()->isDosen() || auth()->user()->isAdmin())
                        <a href="{{ route('rubrik-penilaian.create', $mataKuliah) }}" 
                           class="inline-flex items-center mt-4 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 rounded-lg font-bold text-white text-sm shadow-lg transition-all">
                            <i class="fa-solid fa-plus mr-2"></i> Buat Rubrik Penilaian
                        </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-rubrik-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const name = this.getAttribute('data-name');
                    const form = this.closest('.delete-rubrik-form');
                    Swal.fire({
                        title: 'Hapus Rubrik?',
                        html: `Anda akan menghapus rubrik <b>"${name}"</b>.`,
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
