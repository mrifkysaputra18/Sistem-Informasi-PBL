<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                <div>
                    <nav class="flex items-center flex-wrap gap-2 text-sm bg-white/80 backdrop-blur-sm px-4 py-2.5 rounded-xl shadow-sm border border-gray-100 mb-3">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gray-100 text-gray-700 font-medium">
                            <i class="fa-solid fa-graduation-cap mr-2 text-gray-500"></i>
                            Akademik
                        </span>
                        <span class="text-gray-300">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </span>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gradient-to-r from-indigo-500 to-blue-500 text-white font-semibold shadow-sm">
                            <i class="fa-solid fa-chalkboard-user mr-2"></i>
                            Dosen Matkul
                        </span>
                        @if($selectedClassRoom)
                            <span class="text-gray-300">
                                <i class="fa-solid fa-chevron-right text-xs"></i>
                            </span>
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-purple-100 text-purple-700 font-semibold">
                                <i class="fa-solid fa-school mr-2"></i>
                                {{ $selectedClassRoom->name }}
                            </span>
                        @endif
                    </nav>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">DOSEN MATA KULIAH</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">Kelola penugasan dosen mata kuliah untuk setiap kelas.</p>
                </div>
                @if($selectedClassRoom)
                    <a href="{{ route('penugasan-dosen-matkul.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-bold rounded-lg shadow transition-all">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
                    </a>
                @endif
            </div>

            <!-- Alert Messages -->
            @if(session('success') || session('ok'))
                <div x-data="{ show: true }" x-show="show" class="bg-emerald-100 border-l-8 border-emerald-600 text-emerald-800 px-6 py-4 rounded-lg shadow-md mb-8 flex items-start justify-between">
                    <div class="flex items-center">
                        <i class="fa-solid fa-check-circle text-2xl mr-4 text-emerald-600"></i>
                        <span class="font-bold text-lg">{{ session('success') ?? session('ok') }}</span>
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

            <!-- Pilih Kelas -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200 mb-8 p-6">
                <label for="selectClass" class="block text-sm font-bold text-gray-700 mb-2">
                    <i class="fa-solid fa-school mr-2 text-indigo-600"></i> Pilih Kelas
                </label>
                <select id="selectClass" 
                        class="w-full md:w-96 px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium"
                        onchange="if(this.value) window.location.href='{{ route('penugasan-dosen-matkul.index') }}?kelas=' + this.value">
                    <option value="">-- Pilih Kelas untuk Mengelola --</option>
                    @foreach($classRooms as $room)
                        <option value="{{ $room->id }}" {{ $selectedClassRoom && $selectedClassRoom->id == $room->id ? 'selected' : '' }}>
                            {{ $room->name }} ({{ $room->kelasMataKuliahs->count() }} mata kuliah)
                        </option>
                    @endforeach
                </select>
            </div>

            @if($selectedClassRoom)
                <!-- Info Kelas yang Dipilih -->
                <div class="bg-white rounded-xl shadow-md border-l-4 border-indigo-600 p-6 mb-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="h-14 w-14 rounded-lg bg-indigo-600 flex items-center justify-center text-white text-xl font-bold shadow-lg mr-4">
                                {{ strtoupper(substr($selectedClassRoom->name, 0, 2)) }}
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-gray-900">{{ $selectedClassRoom->name }}</h3>
                                <p class="text-sm text-gray-500">
                                    <span class="font-mono bg-gray-100 px-2 py-0.5 rounded">{{ $selectedClassRoom->code }}</span>
                                    · {{ $selectedClassRoom->academicPeriod?->name ?? '-' }}
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
                                        <div class="p-6 hover:bg-gray-50 transition-colors">
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
                                                        <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                                            <p class="text-xs font-bold text-green-700 uppercase">Dosen Sesudah UTS</p>
                                                            <p class="font-semibold text-green-800">{{ $km->dosenSesudahUts?->name ?? '-' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Action Buttons -->
                                                <div class="flex flex-col gap-2 ml-4">
                                                    <button type="button" 
                                                            onclick="openDosenModal({{ $km->id }}, '{{ addslashes($km->mataKuliah->nama) }}', {{ $km->dosen_sebelum_uts_id ?? 'null' }}, {{ $km->dosen_sesudah_uts_id ?? 'null' }})"
                                                            class="inline-flex items-center px-3 py-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 text-xs font-bold rounded-lg transition-all">
                                                        <i class="fa-solid fa-user-edit mr-1"></i> Edit Dosen
                                                    </button>
                                                    <form action="{{ route('kelas-mata-kuliah.destroy', $km) }}" method="POST" onsubmit="return confirm('Hapus mata kuliah ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="redirect_to" value="{{ route('penugasan-dosen-matkul.index', ['kelas' => $selectedClassRoom->id]) }}">
                                                        <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-bold rounded-lg transition-all w-full">
                                                            <i class="fa-solid fa-trash mr-1"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-12 text-center">
                                    <div class="inline-block p-4 rounded-full bg-gray-100 mb-4">
                                        <i class="fa-solid fa-book-open text-4xl text-gray-300"></i>
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-800 mb-1">Belum Ada Mata Kuliah</h4>
                                    <p class="text-gray-500 text-sm">Tambahkan mata kuliah terkait proyek PBL kelas ini.</p>
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
                            
                            <form action="{{ route('classrooms.mata-kuliah.store', $selectedClassRoom) }}" method="POST" class="p-6">
                                @csrf
                                <input type="hidden" name="redirect_to" value="{{ route('penugasan-dosen-matkul.index', ['kelas' => $selectedClassRoom->id]) }}">
                                
                                <div class="space-y-4">
                                    <!-- Pilih Mata Kuliah -->
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Pilih Mata Kuliah</label>
                                        <select name="mata_kuliah_id" required 
                                                class="w-full px-3 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-medium">
                                            <option value="">-- Pilih Mata Kuliah --</option>
                                            @foreach($availableMataKuliahs as $mk)
                                                <option value="{{ $mk->id }}">{{ $mk->kode }} - {{ $mk->nama }}</option>
                                            @endforeach
                                        </select>
                                        @if($availableMataKuliahs->isEmpty())
                                            <p class="text-xs text-gray-500 mt-1 italic">Semua mata kuliah sudah ditambahkan.</p>
                                        @endif
                                    </div>

                                    <!-- Dosen Sebelum UTS -->
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Dosen Sebelum UTS</label>
                                        <select name="dosen_sebelum_uts_id" 
                                                class="w-full px-3 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-medium">
                                            <option value="">-- Pilih Dosen --</option>
                                            @foreach($dosens as $dosen)
                                                <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Dosen Sesudah UTS -->
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Dosen Sesudah UTS</label>
                                        <select name="dosen_sesudah_uts_id" 
                                                class="w-full px-3 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-medium">
                                            <option value="">-- Pilih Dosen --</option>
                                            @foreach($dosens as $dosen)
                                                <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <button type="submit" 
                                            class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-lg transition-all"
                                            {{ $availableMataKuliahs->isEmpty() ? 'disabled' : '' }}>
                                        <i class="fa-solid fa-plus mr-2"></i> Tambah Mata Kuliah
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            @else
                <!-- Tampilan Overview semua kelas -->
                @forelse($classRooms as $classRoom)
                    <div class="bg-white rounded-xl shadow-md border border-gray-200 mb-6 overflow-hidden">
                        <!-- Header Kelas -->
                        <div class="bg-gray-900 px-6 py-4 flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center mr-4">
                                    <span class="text-white font-bold text-sm">{{ strtoupper(substr($classRoom->name, 0, 2)) }}</span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">{{ $classRoom->name }}</h3>
                                    <p class="text-xs text-gray-400">{{ $classRoom->kelasMataKuliahs->count() }} mata kuliah</p>
                                </div>
                            </div>
                            <a href="{{ route('penugasan-dosen-matkul.index', ['kelas' => $classRoom->id]) }}" 
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg transition-all">
                                <i class="fa-solid fa-edit mr-2"></i> Kelola
                            </a>
                        </div>
                        
                        <!-- Daftar Mata Kuliah + Dosen -->
                        @if($classRoom->kelasMataKuliahs->count() > 0)
                            <div class="divide-y divide-gray-100">
                                @foreach($classRoom->kelasMataKuliahs as $kmk)
                                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                            <div class="flex items-center gap-4 min-w-0">
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-indigo-100 text-indigo-800 shrink-0">
                                                    {{ $kmk->mataKuliah->kode ?? '-' }}
                                                </span>
                                                <span class="font-bold text-gray-900 truncate">{{ $kmk->mataKuliah->nama ?? '-' }}</span>
                                            </div>
                                            <div class="flex flex-wrap gap-4 shrink-0">
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-flex items-center px-2 py-1 rounded bg-blue-100 text-blue-700 text-xs font-bold">1-8</span>
                                                    <span class="text-sm {{ $kmk->dosenSebelumUts ? 'text-gray-700 font-medium' : 'text-gray-400 italic' }}">
                                                        {{ $kmk->dosenSebelumUts?->name ?? 'Belum ditentukan' }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-flex items-center px-2 py-1 rounded bg-green-100 text-green-700 text-xs font-bold">9-16</span>
                                                    <span class="text-sm {{ $kmk->dosenSesudahUts ? 'text-gray-700 font-medium' : 'text-gray-400 italic' }}">
                                                        {{ $kmk->dosenSesudahUts?->name ?? 'Belum ditentukan' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="px-6 py-8 text-center text-gray-500">
                                <i class="fa-solid fa-book-open text-4xl text-gray-300 mb-2"></i>
                                <p>Belum ada mata kuliah untuk kelas ini.</p>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="bg-white rounded-xl shadow-md py-16 text-center">
                        <i class="fa-solid fa-school text-5xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-800">Belum Ada Kelas</h3>
                    </div>
                @endforelse
            @endif
        </div>
    </div>

    <!-- Modal Edit Dosen -->
    @if($selectedClassRoom)
    <div id="dosenModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/60 transition-opacity" onclick="closeDosenModal()"></div>
            <div class="relative bg-white rounded-xl shadow-2xl max-w-md w-full z-10">
                <div class="bg-indigo-600 px-6 py-4 rounded-t-xl">
                    <h3 class="text-lg font-bold text-white">
                        <i class="fa-solid fa-user-edit mr-2"></i> Edit Dosen
                    </h3>
                    <p class="text-sm text-indigo-200 mt-1" id="dosen-modal-matkul-nama"></p>
                </div>
                <form id="dosenForm" method="POST" class="p-6">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="redirect_to" value="{{ route('penugasan-dosen-matkul.index', ['kelas' => $selectedClassRoom->id]) }}">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Dosen Sebelum UTS (Minggu 1-8)</label>
                            <select name="dosen_sebelum_uts_id" id="modal_dosen_sebelum" 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">-- Pilih Dosen --</option>
                                @foreach($dosens as $dosen)
                                    <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Dosen Sesudah UTS (Minggu 9-16)</label>
                            <select name="dosen_sesudah_uts_id" id="modal_dosen_sesudah" 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">-- Pilih Dosen --</option>
                                @foreach($dosens as $dosen)
                                    <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex gap-3">
                        <button type="button" onclick="closeDosenModal()" 
                                class="flex-1 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition-all">
                            Batal
                        </button>
                        <button type="submit" 
                                class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-lg transition-all">
                            <i class="fa-solid fa-save mr-2"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openDosenModal(kelasMataKuliahId, matkulNama, dosenSebelumId, dosenSesudahId) {
            document.getElementById('dosen-modal-matkul-nama').textContent = matkulNama;
            document.getElementById('dosenForm').action = '/kelas-mata-kuliah/' + kelasMataKuliahId + '/update-dosen';
            document.getElementById('modal_dosen_sebelum').value = dosenSebelumId || '';
            document.getElementById('modal_dosen_sesudah').value = dosenSesudahId || '';
            document.getElementById('dosenModal').classList.remove('hidden');
        }
        
        function closeDosenModal() {
            document.getElementById('dosenModal').classList.add('hidden');
        }
    </script>
    @endif
</x-app-layout>
