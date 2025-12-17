<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- HEADER -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                <div>
                    <nav class="text-sm mb-2">
                        <a href="{{ route('nilai-rubrik.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Nilai Rubrik</a>
                        <span class="text-gray-400 mx-2">/</span>
                        <span class="text-gray-500 font-semibold">Input Nilai</span>
                    </nav>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">INPUT NILAI MAHASISWA</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">
                        {{ $kelasMataKuliah->mataKuliah->nama }} - Kelas {{ $kelasMataKuliah->classRoom->name }}
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('nilai-rubrik.rekap', $kelasMataKuliah) }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-bold rounded-lg shadow transition-all">
                        <i class="fa-solid fa-chart-bar mr-2"></i> Lihat Rekap
                    </a>
                    <a href="{{ route('nilai-rubrik.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-bold rounded-lg shadow transition-all">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
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

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" class="bg-red-100 border-l-8 border-red-600 text-red-800 px-6 py-4 rounded-lg shadow-md mb-8 flex items-start justify-between">
                    <div class="flex items-center">
                        <i class="fa-solid fa-exclamation-triangle text-2xl mr-4 text-red-600"></i>
                        <span class="font-bold text-lg">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-times text-xl"></i></button>
                </div>
            @endif

            <!-- Info Rubrik dengan Bobot UTS/UAS -->
            @php
                $rubrik = $kelasMataKuliah->rubrikPenilaian;
                $bobotUts = $rubrik->bobot_uts ?? 50;
                $bobotUas = $rubrik->bobot_uas ?? 50;
                $itemsUts = $rubrikItems->where('periode_ujian', 'uts');
                $itemsUas = $rubrikItems->where('periode_ujian', 'uas');
                
                // Akses kontrol dosen
                $isAdmin = auth()->user()->isAdmin();
                $canInputUts = $isAdmin || $kelasMataKuliah->canInputNilaiUts(auth()->id());
                $canInputUas = $isAdmin || $kelasMataKuliah->canInputNilaiUas(auth()->id());
                $isDosenUts = $kelasMataKuliah->isDosenUts(auth()->id());
                $isDosenUas = $kelasMataKuliah->isDosenUas(auth()->id());
            @endphp
            
            <!-- Info Status Dosen -->
            @if(!$isAdmin && ($isDosenUts || $isDosenUas))
            <div class="mb-6 p-4 rounded-lg {{ $isDosenUts && $isDosenUas ? 'bg-purple-100 border border-purple-300' : ($isDosenUts ? 'bg-blue-100 border border-blue-300' : 'bg-orange-100 border border-orange-300') }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-info-circle text-xl mr-3 {{ $isDosenUts && $isDosenUas ? 'text-purple-600' : ($isDosenUts ? 'text-blue-600' : 'text-orange-600') }}"></i>
                    <div>
                        <p class="font-bold {{ $isDosenUts && $isDosenUas ? 'text-purple-800' : ($isDosenUts ? 'text-blue-800' : 'text-orange-800') }}">
                            @if($isDosenUts && $isDosenUas)
                                Anda adalah Dosen UTS dan UAS
                            @elseif($isDosenUts)
                                Anda adalah Dosen UTS (Sebelum UTS)
                            @else
                                Anda adalah Dosen UAS (Sesudah UTS)
                            @endif
                        </p>
                        <p class="text-sm {{ $isDosenUts && $isDosenUas ? 'text-purple-600' : ($isDosenUts ? 'text-blue-600' : 'text-orange-600') }}">
                            @if($isDosenUts && $isDosenUas)
                                Anda dapat menginput nilai untuk semua item penilaian.
                            @elseif($isDosenUts)
                                Anda hanya dapat menginput nilai untuk item <strong>UTS</strong>.
                            @else
                                Anda hanya dapat menginput nilai untuk item <strong>UAS</strong>.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            @endif
            
            <div class="bg-white rounded-xl shadow-md border-l-4 border-indigo-600 p-6 mb-8">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900">{{ $rubrik->nama }}</h3>
                        
                        <!-- Bobot UTS/UAS -->
                        <div class="flex flex-wrap gap-3 mt-3 mb-3">
                            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                <i class="fa-solid fa-scale-balanced mr-2"></i> Bobot UTS: {{ $bobotUts }}%
                            </span>
                            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold bg-orange-100 text-orange-800 border border-orange-200">
                                <i class="fa-solid fa-scale-balanced mr-2"></i> Bobot UAS: {{ $bobotUas }}%
                            </span>
                        </div>
                        
                        <!-- Item UTS -->
                        @if($itemsUts->count() > 0)
                        <div class="mb-2">
                            <span class="text-xs font-bold text-blue-700">Item UTS:</span>
                            <div class="flex flex-wrap gap-1 mt-1">
                                @foreach($itemsUts as $item)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ $item->nama }} ({{ $item->persentase }}%)
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <!-- Item UAS -->
                        @if($itemsUas->count() > 0)
                        <div>
                            <span class="text-xs font-bold text-orange-700">Item UAS:</span>
                            <div class="flex flex-wrap gap-1 mt-1">
                                @foreach($itemsUas as $item)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-orange-50 text-orange-700 border border-orange-200">
                                        {{ $item->nama }} ({{ $item->persentase }}%)
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-gray-400 uppercase">Total Mahasiswa</p>
                        <p class="text-3xl font-black text-indigo-600">{{ $mahasiswas->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Input Nilai -->
            <form action="{{ route('nilai-rubrik.store', $kelasMataKuliah) }}" method="POST">
                @csrf
                
                <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="bg-gray-900 px-6 py-4 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white flex items-center">
                            <i class="fa-solid fa-users mr-3 text-indigo-400"></i>
                            Input Nilai Mahasiswa
                        </h3>
                        <button type="submit" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow transition-all">
                            <i class="fa-solid fa-save mr-2"></i> Simpan Semua
                        </button>
                    </div>

                    @if($mahasiswas->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full border-collapse">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase sticky left-0 bg-gray-50 z-10">Mahasiswa</th>
                                        
                                        <!-- Header Item UTS -->
                                        @foreach($itemsUts as $item)
                                            <th class="px-4 py-4 text-center text-xs font-bold text-blue-700 uppercase min-w-[120px] bg-blue-50">
                                                {{ $item->nama }}<br>
                                                <span class="text-blue-500">({{ $item->persentase }}%)</span>
                                            </th>
                                        @endforeach
                                        
                                        <!-- Subtotal UTS -->
                                        <th class="px-4 py-4 text-center text-xs font-bold text-blue-800 uppercase bg-blue-100 border-l-2 border-blue-300">
                                            UTS<br><span class="text-blue-600">({{ $bobotUts }}%)</span>
                                        </th>
                                        
                                        <!-- Header Item UAS -->
                                        @foreach($itemsUas as $item)
                                            <th class="px-4 py-4 text-center text-xs font-bold text-orange-700 uppercase min-w-[120px] bg-orange-50">
                                                {{ $item->nama }}<br>
                                                <span class="text-orange-500">({{ $item->persentase }}%)</span>
                                            </th>
                                        @endforeach
                                        
                                        <!-- Subtotal UAS -->
                                        <th class="px-4 py-4 text-center text-xs font-bold text-orange-800 uppercase bg-orange-100 border-l-2 border-orange-300">
                                            UAS<br><span class="text-orange-600">({{ $bobotUas }}%)</span>
                                        </th>
                                        
                                        <!-- Total Akhir -->
                                        <th class="px-4 py-4 text-center text-xs font-bold text-emerald-800 uppercase bg-emerald-100 border-l-2 border-emerald-300">
                                            TOTAL<br><span class="text-emerald-600">(100%)</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($mahasiswas as $mahasiswa)
                                        @php
                                            $nilaiMhs = $nilaiExisting[$mahasiswa->id] ?? collect();
                                        @endphp
                                        <tr class="hover:bg-gray-50 transition-colors" x-data="{ 
                                            nilaiUts: { 
                                                @foreach($itemsUts as $item)
                                                    '{{ $item->id }}': {{ $nilaiMhs->firstWhere('rubrik_item_id', $item->id)?->nilai ?? 0 }},
                                                @endforeach
                                            },
                                            nilaiUas: { 
                                                @foreach($itemsUas as $item)
                                                    '{{ $item->id }}': {{ $nilaiMhs->firstWhere('rubrik_item_id', $item->id)?->nilai ?? 0 }},
                                                @endforeach
                                            },
                                            itemsUts: [
                                                @foreach($itemsUts as $item)
                                                    { id: '{{ $item->id }}', persentase: {{ $item->persentase }} },
                                                @endforeach
                                            ],
                                            itemsUas: [
                                                @foreach($itemsUas as $item)
                                                    { id: '{{ $item->id }}', persentase: {{ $item->persentase }} },
                                                @endforeach
                                            ],
                                            bobotUts: {{ $bobotUts }},
                                            bobotUas: {{ $bobotUas }},
                                            get totalUts() {
                                                let sum = 0;
                                                this.itemsUts.forEach(item => {
                                                    sum += (parseFloat(this.nilaiUts[item.id]) || 0) * item.persentase / 100;
                                                });
                                                return sum;
                                            },
                                            get totalUas() {
                                                let sum = 0;
                                                this.itemsUas.forEach(item => {
                                                    sum += (parseFloat(this.nilaiUas[item.id]) || 0) * item.persentase / 100;
                                                });
                                                return sum;
                                            },
                                            get totalAkhir() {
                                                return (this.totalUts * this.bobotUts / 100) + (this.totalUas * this.bobotUas / 100);
                                            }
                                        }">
                                            <td class="px-6 py-4 sticky left-0 bg-white z-10">
                                                <div class="flex items-center">
                                                    <div class="h-10 w-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold mr-3">
                                                        {{ strtoupper(substr($mahasiswa->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-gray-900">{{ $mahasiswa->name }}</p>
                                                        <p class="text-xs text-gray-500">{{ $mahasiswa->nim ?? '-' }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <!-- Input Nilai UTS -->
                                            @foreach($itemsUts as $item)
                                                <td class="px-4 py-4 text-center {{ $canInputUts ? 'bg-blue-50/30' : 'bg-gray-100' }}">
                                                    @if($canInputUts)
                                                        <input type="number" 
                                                               name="nilai[{{ $mahasiswa->id }}][{{ $item->id }}]" 
                                                               x-model="nilaiUts['{{ $item->id }}']"
                                                               min="0" max="100" step="0.01"
                                                               class="w-20 h-10 text-center border-2 border-blue-200 rounded-lg font-bold focus:border-blue-500 focus:ring-0"
                                                               placeholder="0">
                                                    @else
                                                        <span class="text-lg font-bold text-gray-500">{{ $nilaiMhs->firstWhere('rubrik_item_id', $item->id)?->nilai ?? '-' }}</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                            
                                            <!-- Subtotal UTS -->
                                            <td class="px-4 py-4 text-center bg-blue-100 border-l-2 border-blue-300">
                                                <span class="text-lg font-black text-blue-700" x-text="totalUts.toFixed(2)"></span>
                                            </td>
                                            
                                            <!-- Input Nilai UAS -->
                                            @foreach($itemsUas as $item)
                                                <td class="px-4 py-4 text-center {{ $canInputUas ? 'bg-orange-50/30' : 'bg-gray-100' }}">
                                                    @if($canInputUas)
                                                        <input type="number" 
                                                               name="nilai[{{ $mahasiswa->id }}][{{ $item->id }}]" 
                                                               x-model="nilaiUas['{{ $item->id }}']"
                                                               min="0" max="100" step="0.01"
                                                               class="w-20 h-10 text-center border-2 border-orange-200 rounded-lg font-bold focus:border-orange-500 focus:ring-0"
                                                               placeholder="0">
                                                    @else
                                                        <span class="text-lg font-bold text-gray-500">{{ $nilaiMhs->firstWhere('rubrik_item_id', $item->id)?->nilai ?? '-' }}</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                            
                                            <!-- Subtotal UAS -->
                                            <td class="px-4 py-4 text-center bg-orange-100 border-l-2 border-orange-300">
                                                <span class="text-lg font-black text-orange-700" x-text="totalUas.toFixed(2)"></span>
                                            </td>
                                            
                                            <!-- Total Akhir -->
                                            <td class="px-4 py-4 text-center bg-emerald-100 border-l-2 border-emerald-300">
                                                <span class="text-xl font-black text-emerald-700" x-text="totalAkhir.toFixed(2)"></span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                            <button type="submit" class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow-lg transition-all">
                                <i class="fa-solid fa-save mr-2"></i> Simpan Semua Nilai
                            </button>
                        </div>
                    @else
                        <div class="py-16 text-center">
                            <div class="inline-block p-5 rounded-full bg-gray-100 mb-4">
                                <i class="fa-solid fa-users text-4xl text-gray-300"></i>
                            </div>
                            <h4 class="text-lg font-bold text-gray-700">Tidak Ada Mahasiswa</h4>
                            <p class="text-sm text-gray-500 mt-1">Belum ada mahasiswa terdaftar di kelas ini.</p>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
