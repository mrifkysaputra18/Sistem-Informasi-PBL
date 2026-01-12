<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- HEADER -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                <div>
                    <nav class="flex items-center flex-wrap gap-2 text-sm bg-white/80 backdrop-blur-sm px-4 py-2.5 rounded-xl shadow-sm border border-gray-100 mb-3">
                        @if(request('from') === 'akademik')
                            <a href="{{ route('penugasan-dosen-matkul.index') }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 font-semibold transition-all duration-200 group">
                                <i class="fa-solid fa-graduation-cap mr-2 text-indigo-500 group-hover:scale-110 transition-transform"></i>
                                Akademik
                            </a>
                            <span class="text-gray-300">
                                <i class="fa-solid fa-chevron-right text-xs"></i>
                            </span>
                            <a href="{{ route('penugasan-dosen-matkul.index') }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 font-semibold transition-all duration-200">
                                Dosen Matkul
                            </a>
                        @else
                            <a href="{{ route('classrooms.index') }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 font-semibold transition-all duration-200 group">
                                <i class="fa-solid fa-school mr-2 text-indigo-500 group-hover:scale-110 transition-transform"></i>
                                Kelas
                            </a>
                        @endif
                        <span class="text-gray-300">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </span>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gray-100 text-gray-700 font-medium">
                            {{ $classRoom->name }}
                        </span>
                        <span class="text-gray-300">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </span>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gradient-to-r from-purple-500 to-pink-500 text-white font-semibold shadow-sm">
                            <i class="fa-solid fa-book-open mr-2"></i>
                            Mata Kuliah Terkait
                        </span>
                    </nav>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">MATA KULIAH TERKAIT PBL</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">
                        Kelola mata kuliah yang terkait dengan proyek PBL kelas <strong>{{ $classRoom->name }}</strong>
                    </p>
                </div>
                @if(request('from') === 'akademik')
                    <a href="{{ route('penugasan-dosen-matkul.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-bold rounded-lg shadow transition-all">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
                    </a>
                @else
                    <a href="{{ route('classrooms.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-bold rounded-lg shadow transition-all">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
                    </a>
                @endif
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

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" class="bg-red-100 border-l-8 border-red-600 text-red-800 px-6 py-4 rounded-lg shadow-md mb-8 flex items-start justify-between">
                    <div class="flex items-center">
                        <i class="fa-solid fa-exclamation-triangle text-2xl mr-4 text-red-600"></i>
                        <span class="font-bold text-lg">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-times text-xl"></i></button>
                </div>
            @endif

            <!-- Info Kelas -->
            <div class="bg-white rounded-xl shadow-md border-l-4 border-indigo-600 p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="h-14 w-14 rounded-lg bg-indigo-600 flex items-center justify-center text-white text-xl font-bold shadow-lg mr-4">
                            {{ strtoupper(substr($classRoom->name, 0, 2)) }}
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-gray-900">{{ $classRoom->name }}</h3>
                            <p class="text-sm text-gray-500">
                                <span class="font-mono bg-gray-100 px-2 py-0.5 rounded">{{ $classRoom->code }}</span>
                                · {{ $classRoom->academicPeriod?->name ?? '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-gray-400 uppercase">Mata Kuliah Terkait</p>
                        <p class="text-3xl font-black text-indigo-600">{{ $kelasMataKuliahs->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Daftar Mata Kuliah Terkait -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="bg-gray-900 px-6 py-4">
                            <h3 class="text-lg font-bold text-white flex items-center">
                                <i class="fa-solid fa-book-open mr-3 text-indigo-400"></i>
                                Daftar Mata Kuliah Terkait
                            </h3>
                        </div>
                        
                        @if($kelasMataKuliahs->count() > 0)
                            <div class="divide-y divide-gray-200">
                                @foreach($kelasMataKuliahs as $km)
                                    <div class="p-6 hover:bg-gray-50 transition-colors" x-data="{ showRubrik: false }">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <div class="h-10 w-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                                                        <i class="fa-solid fa-book"></i>
                                                    </div>
                                                    <div>
                                                        <h4 class="font-bold text-gray-900">{{ $km->mataKuliah->nama }}</h4>
                                                        <p class="text-xs text-gray-500 font-mono">{{ $km->mataKuliah->kode }}</p>
                                                    </div>
                                                </div>
                                                
                                                <!-- Dosen Info -->
                                                <div class="mt-3 grid grid-cols-2 gap-3">
                                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                                        <p class="text-xs font-bold text-blue-700 uppercase">Dosen Sebelum UTS</p>
                                                        <p class="font-semibold text-blue-800">{{ $km->dosenSebelumUts?->name ?? '-' }}</p>
                                                    </div>
                                                    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3">
                                                        <p class="text-xs font-bold text-emerald-700 uppercase">Dosen Sesudah UTS</p>
                                                        <p class="font-semibold text-emerald-800">{{ $km->dosenSesudahUts?->name ?? '-' }}</p>
                                                    </div>
                                                </div>
                                                
                                                <!-- Rubrik Info -->
                                                <div class="mt-3">
                                                    @if($km->rubrikPenilaian)
                                                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                                                            <div class="flex items-center justify-between">
                                                                <div>
                                                                    <p class="text-xs font-bold text-gray-600 uppercase">Rubrik Aktif</p>
                                                                    <p class="font-bold text-gray-800">{{ $km->rubrikPenilaian->nama }}</p>
                                                                    <p class="text-xs text-gray-500">
                                                                        {{ $km->rubrikPenilaian->items->count() }} item · 
                                                                        Total {{ $km->rubrikPenilaian->total_persentase }}%
                                                                    </p>
                                                                </div>
                                                                <button @click="showRubrik = !showRubrik" 
                                                                        class="text-gray-600 hover:text-gray-800 text-sm font-bold">
                                                                    <i class="fa-solid" :class="showRubrik ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                                                </button>
                                                            </div>
                                                            
                                                            <!-- Detail Rubrik Items -->
                                                            <div x-show="showRubrik" x-collapse class="mt-3 pt-3 border-t border-gray-200">
                                                                @foreach($km->rubrikPenilaian->items as $item)
                                                                    <div class="flex justify-between text-sm py-1">
                                                                        <span class="text-gray-700">{{ $item->nama }}</span>
                                                                        <span class="font-bold text-gray-700">{{ $item->persentase }}%</span>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                                            <p class="text-sm text-yellow-700 font-medium">
                                                                <i class="fa-solid fa-exclamation-triangle mr-1"></i>
                                                                Belum ada rubrik dipilih
                                                            </p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- Actions -->
                                            <div class="flex flex-col gap-2 ml-4">
                                                <button type="button" 
                                                        onclick="openDosenModal({{ $km->id }}, '{{ $km->mataKuliah->nama }}', {{ $km->dosen_sebelum_uts_id ?? 'null' }}, {{ $km->dosen_sesudah_uts_id ?? 'null' }})"
                                                        class="px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded shadow transition-all">
                                                    <i class="fa-solid fa-user-pen mr-1"></i> Edit Dosen
                                                </button>
                                                <button type="button" 
                                                        onclick="openRubrikModal({{ $km->id }}, '{{ $km->mataKuliah->nama }}')"
                                                        class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded shadow transition-all">
                                                    <i class="fa-solid fa-list-check mr-1"></i> Pilih Rubrik
                                                </button>
                                                <form action="{{ route('kelas-mata-kuliah.destroy', $km) }}" method="POST" 
                                                      onsubmit="return confirm('Hapus mata kuliah ini dari kelas?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="w-full px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded shadow transition-all">
                                                        <i class="fa-solid fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="py-16 text-center">
                                <div class="inline-block p-5 rounded-full bg-gray-100 mb-4">
                                    <i class="fa-solid fa-book text-4xl text-gray-300"></i>
                                </div>
                                <h4 class="text-lg font-bold text-gray-700">Belum Ada Mata Kuliah</h4>
                                <p class="text-sm text-gray-500 mt-1">Tambahkan mata kuliah terkait proyek PBL kelas ini.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Form Tambah Mata Kuliah -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden sticky top-8">
                        <div class="bg-indigo-600 px-6 py-4">
                            <h3 class="text-lg font-bold text-white flex items-center">
                                <i class="fa-solid fa-plus mr-3"></i>
                                Tambah Mata Kuliah
                            </h3>
                        </div>
                        
                        <form action="{{ route('classrooms.mata-kuliah.store', $classRoom) }}" method="POST" class="p-6">
                            @csrf
                            
                            @if($availableMataKuliahs->count() > 0)
                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Pilih Mata Kuliah</label>
                                    <select name="mata_kuliah_id" required
                                            class="w-full h-12 bg-gray-50 border-2 border-gray-200 rounded-lg text-sm font-semibold focus:border-indigo-600 focus:ring-0">
                                        <option value="">-- Pilih Mata Kuliah --</option>
                                        @foreach($availableMataKuliahs as $mk)
                                            <option value="{{ $mk->id }}">{{ $mk->nama }} ({{ $mk->kode }})</option>
                                        @endforeach
                                    </select>
                                    @error('mata_kuliah_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Dosen Sebelum UTS</label>
                                    <select name="dosen_sebelum_uts_id"
                                            class="w-full h-12 bg-gray-50 border-2 border-gray-200 rounded-lg text-sm font-semibold focus:border-indigo-600 focus:ring-0">
                                        <option value="">-- Pilih Dosen --</option>
                                        @foreach($dosens as $dosen)
                                            <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Dosen Sesudah UTS</label>
                                    <select name="dosen_sesudah_uts_id"
                                            class="w-full h-12 bg-gray-50 border-2 border-gray-200 rounded-lg text-sm font-semibold focus:border-indigo-600 focus:ring-0">
                                        <option value="">-- Pilih Dosen --</option>
                                        @foreach($dosens as $dosen)
                                            <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <button type="submit" 
                                        class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-lg transition-all">
                                    <i class="fa-solid fa-plus mr-2"></i> Tambahkan
                                </button>
                            @else
                                <div class="text-center py-8">
                                    <i class="fa-solid fa-check-circle text-4xl text-emerald-500 mb-3"></i>
                                    <p class="text-sm text-gray-600 font-medium">Semua mata kuliah sudah ditambahkan</p>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pilih Rubrik -->
    <div id="rubrik-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" onclick="closeRubrikModal(event)">
        <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full mx-4 max-h-[80vh] overflow-hidden" onclick="event.stopPropagation()">
            <div class="bg-indigo-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white">
                    <i class="fa-solid fa-list-check mr-2"></i> Pilih Rubrik
                </h3>
                <button onclick="closeRubrikModal()" class="text-white hover:text-indigo-200">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6">
                <p class="text-sm text-gray-600 mb-4">
                    Pilih rubrik untuk mata kuliah: <strong id="modal-matkul-nama"></strong>
                </p>
                
                <form id="select-rubrik-form" method="POST">
                    @csrf
                    <div id="rubrik-list" class="space-y-3 max-h-96 overflow-y-auto">
                        <!-- Diisi oleh JavaScript -->
                        <div class="text-center py-8 text-gray-500">
                            <i class="fa-solid fa-spinner fa-spin text-2xl"></i>
                            <p class="mt-2">Memuat rubrik...</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex gap-3">
                        <button type="button" onclick="closeRubrikModal()" 
                                class="flex-1 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition-all">
                            Batal
                        </button>
                        <button type="submit" id="btn-simpan-rubrik"
                                class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentKelasMataKuliahId = null;

        function openRubrikModal(kelasMataKuliahId, matkulNama) {
            currentKelasMataKuliahId = kelasMataKuliahId;
            document.getElementById('modal-matkul-nama').textContent = matkulNama;
            document.getElementById('rubrik-modal').classList.remove('hidden');
            document.getElementById('rubrik-modal').classList.add('flex');
            document.getElementById('btn-simpan-rubrik').disabled = true;
            
            // Set form action
            document.getElementById('select-rubrik-form').action = `/kelas-mata-kuliah/${kelasMataKuliahId}/select-rubrik`;
            
            // Load rubriks
            fetch(`/kelas-mata-kuliah/${kelasMataKuliahId}/rubriks`)
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('rubrik-list');
                    if (data.rubriks.length === 0) {
                        container.innerHTML = `
                            <div class="text-center py-8 text-gray-500">
                                <i class="fa-solid fa-exclamation-circle text-3xl mb-2"></i>
                                <p>Belum ada rubrik untuk mata kuliah ini.</p>
                                <p class="text-xs mt-1">Buat rubrik terlebih dahulu di menu Mata Kuliah.</p>
                            </div>
                        `;
                        return;
                    }
                    
                    container.innerHTML = data.rubriks.map(rubrik => `
                        <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer hover:border-indigo-400 transition-colors ${rubrik.is_complete ? 'border-gray-200' : 'border-yellow-300 bg-yellow-50'}">
                            <input type="radio" name="rubrik_penilaian_id" value="${rubrik.id}" 
                                   class="mt-1 text-indigo-600" ${!rubrik.is_complete ? 'disabled' : ''}
                                   onchange="document.getElementById('btn-simpan-rubrik').disabled = false">
                            <div class="ml-3 flex-1">
                                <p class="font-bold text-gray-900">${rubrik.nama}</p>
                                <p class="text-xs text-gray-500">Semester ${rubrik.semester} · ${rubrik.periode}</p>
                                <p class="text-xs ${rubrik.is_complete ? 'text-emerald-600' : 'text-yellow-700'} mt-1">
                                    ${rubrik.items.length} item · Total ${rubrik.total_persentase}%
                                    ${!rubrik.is_complete ? '(Belum lengkap)' : ''}
                                </p>
                            </div>
                        </label>
                    `).join('');
                });
        }

        function closeRubrikModal(event) {
            if (event && event.target !== document.getElementById('rubrik-modal')) return;
            document.getElementById('rubrik-modal').classList.add('hidden');
            document.getElementById('rubrik-modal').classList.remove('flex');
        }

        function openDosenModal(kelasMataKuliahId, matkulNama, dosenSebelumId, dosenSesudahId) {
            document.getElementById('dosen-modal-matkul-nama').textContent = matkulNama;
            document.getElementById('dosen-modal').classList.remove('hidden');
            document.getElementById('dosen-modal').classList.add('flex');
            
            // Set form action
            document.getElementById('update-dosen-form').action = `/kelas-mata-kuliah/${kelasMataKuliahId}/update-dosen`;
            
            // Set selected values
            document.getElementById('modal-dosen-sebelum-uts').value = dosenSebelumId || '';
            document.getElementById('modal-dosen-sesudah-uts').value = dosenSesudahId || '';
        }

        function closeDosenModal(event) {
            if (event && event.target !== document.getElementById('dosen-modal')) return;
            document.getElementById('dosen-modal').classList.add('hidden');
            document.getElementById('dosen-modal').classList.remove('flex');
        }
    </script>

    <!-- Modal Edit Dosen -->
    <div id="dosen-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" onclick="closeDosenModal(event)">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 overflow-hidden" onclick="event.stopPropagation()">
            <div class="bg-purple-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white">
                    <i class="fa-solid fa-user-pen mr-2"></i> Edit Dosen
                </h3>
                <button onclick="closeDosenModal()" class="text-white hover:text-purple-200">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6">
                <p class="text-sm text-gray-600 mb-4">
                    Edit dosen untuk mata kuliah: <strong id="dosen-modal-matkul-nama"></strong>
                </p>
                
                <form id="update-dosen-form" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Dosen Sebelum UTS</label>
                        <select name="dosen_sebelum_uts_id" id="modal-dosen-sebelum-uts"
                                class="w-full h-12 bg-gray-50 border-2 border-gray-200 rounded-lg text-sm font-semibold focus:border-purple-600 focus:ring-0">
                            <option value="">-- Tidak ada --</option>
                            @foreach($dosens as $dosen)
                                <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Dosen Sesudah UTS</label>
                        <select name="dosen_sesudah_uts_id" id="modal-dosen-sesudah-uts"
                                class="w-full h-12 bg-gray-50 border-2 border-gray-200 rounded-lg text-sm font-semibold focus:border-purple-600 focus:ring-0">
                            <option value="">-- Tidak ada --</option>
                            @foreach($dosens as $dosen)
                                <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex gap-3">
                        <button type="button" onclick="closeDosenModal()" 
                                class="flex-1 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition-all">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg transition-all">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
