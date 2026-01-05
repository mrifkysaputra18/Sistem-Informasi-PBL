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

            <!-- Info Rubrik dengan Kategori -->
            @php
                $rubrik = $kelasMataKuliah->rubrikPenilaian;
                $kategoris = $rubrik->kategoris ?? collect();
                
                // Akses kontrol dosen - untuk sekarang, semua dosen bisa input semua kategori
                $isAdmin = auth()->user()->isAdmin();
                $canInput = $isAdmin || $kelasMataKuliah->isDosenAssigned(auth()->id());
                
                // Warna untuk kategori (cycling through colors)
                $kategoriColors = [
                    ['bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'text' => 'text-blue-700', 'header-bg' => 'bg-blue-100', 'header-text' => 'text-blue-800'],
                    ['bg' => 'bg-orange-50', 'border' => 'border-orange-200', 'text' => 'text-orange-700', 'header-bg' => 'bg-orange-100', 'header-text' => 'text-orange-800'],
                    ['bg' => 'bg-purple-50', 'border' => 'border-purple-200', 'text' => 'text-purple-700', 'header-bg' => 'bg-purple-100', 'header-text' => 'text-purple-800'],
                    ['bg' => 'bg-teal-50', 'border' => 'border-teal-200', 'text' => 'text-teal-700', 'header-bg' => 'bg-teal-100', 'header-text' => 'text-teal-800'],
                    ['bg' => 'bg-pink-50', 'border' => 'border-pink-200', 'text' => 'text-pink-700', 'header-bg' => 'bg-pink-100', 'header-text' => 'text-pink-800'],
                ];
            @endphp
            
            <div class="bg-white rounded-xl shadow-md border-l-4 border-indigo-600 p-6 mb-8">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900">{{ $rubrik->nama }}</h3>
                        
                        <!-- Kategori dengan Bobot -->
                        <div class="flex flex-wrap gap-3 mt-3 mb-3">
                            @foreach($kategoris as $kIndex => $kategori)
                                @php $color = $kategoriColors[$kIndex % count($kategoriColors)]; @endphp
                                <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold {{ $color['bg'] }} {{ $color['text'] }} {{ $color['border'] }} border">
                                    <i class="fa-solid fa-scale-balanced mr-2"></i> {{ $kategori->nama }}: {{ $kategori->bobot }}%
                                </span>
                            @endforeach
                        </div>
                        
                        <!-- Item per Kategori -->
                        @foreach($kategoris as $kIndex => $kategori)
                            @php 
                                $color = $kategoriColors[$kIndex % count($kategoriColors)];
                                $items = $kategori->items;
                            @endphp
                            @if($items->count() > 0)
                            <div class="mb-2">
                                <span class="text-xs font-bold {{ $color['text'] }}">Item {{ $kategori->nama }}:</span>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @foreach($items as $item)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $color['bg'] }} {{ $color['text'] }} {{ $color['border'] }} border">
                                            {{ $item->nama }} ({{ $item->persentase }}%)
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        @endforeach
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
                                        
                                        @foreach($kategoris as $kIndex => $kategori)
                                            @php 
                                                $color = $kategoriColors[$kIndex % count($kategoriColors)];
                                                $items = $kategori->items;
                                            @endphp
                                            
                                            <!-- Header Item per Kategori -->
                                            @foreach($items as $item)
                                                <th class="px-4 py-4 text-center text-xs font-bold {{ $color['text'] }} uppercase min-w-[120px] {{ $color['bg'] }}">
                                                    {{ $item->nama }}<br>
                                                    <span class="{{ $color['text'] }}">({{ $item->persentase }}%)</span>
                                                </th>
                                            @endforeach
                                            
                                            <!-- Subtotal Kategori -->
                                            <th class="px-4 py-4 text-center text-xs font-bold {{ $color['header-text'] }} uppercase {{ $color['header-bg'] }} border-l-2 {{ $color['border'] }}">
                                                {{ $kategori->nama }}<br><span class="{{ $color['text'] }}">({{ $kategori->bobot }}%)</span>
                                            </th>
                                        @endforeach
                                        
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
                                            
                                            // Build data untuk Alpine.js
                                            $alpineData = [
                                                'kategoris' => [],
                                            ];
                                            
                                            foreach($kategoris as $kategori) {
                                                $kategoriData = [
                                                    'bobot' => $kategori->bobot,
                                                    'items' => [],
                                                ];
                                                foreach($kategori->items as $item) {
                                                    $nilai = $nilaiMhs->firstWhere('rubrik_item_id', $item->id)?->nilai ?? 0;
                                                    $kategoriData['items'][$item->id] = [
                                                        'nilai' => floatval($nilai),
                                                        'persentase' => floatval($item->persentase),
                                                    ];
                                                }
                                                $alpineData['kategoris'][$kategori->id] = $kategoriData;
                                            }
                                        @endphp
                                        <tr class="hover:bg-gray-50 transition-colors" x-data='@json($alpineData)'>
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
                                            
                                            @foreach($kategoris as $kIndex => $kategori)
                                                @php 
                                                    $color = $kategoriColors[$kIndex % count($kategoriColors)];
                                                    $items = $kategori->items;
                                                @endphp
                                                
                                                <!-- Input Nilai per Item -->
                                                @foreach($items as $item)
                                                    <td class="px-4 py-4 text-center {{ $canInput ? $color['bg'] . '/30' : 'bg-gray-100' }}">
                                                        @if($canInput)
                                                            <input type="number" 
                                                                   name="nilai[{{ $mahasiswa->id }}][{{ $item->id }}]" 
                                                                   x-model="kategoris[{{ $kategori->id }}].items[{{ $item->id }}].nilai"
                                                                   min="0" max="100" step="0.01"
                                                                   class="w-20 h-10 text-center border-2 {{ $color['border'] }} rounded-lg font-bold focus:border-indigo-500 focus:ring-0"
                                                                   placeholder="0">
                                                        @else
                                                            <span class="text-lg font-bold text-gray-500">{{ $nilaiMhs->firstWhere('rubrik_item_id', $item->id)?->nilai ?? '-' }}</span>
                                                        @endif
                                                    </td>
                                                @endforeach
                                                
                                                <!-- Subtotal Kategori -->
                                                <td class="px-4 py-4 text-center {{ $color['header-bg'] }} border-l-2 {{ $color['border'] }}">
                                                    <span class="text-lg font-black {{ $color['header-text'] }}" 
                                                          x-text="(() => {
                                                              let sum = 0;
                                                              for (let itemId in kategoris[{{ $kategori->id }}].items) {
                                                                  let item = kategoris[{{ $kategori->id }}].items[itemId];
                                                                  sum += (parseFloat(item.nilai) || 0) * item.persentase / 100;
                                                              }
                                                              return sum.toFixed(2);
                                                          })()"></span>
                                                </td>
                                            @endforeach
                                            
                                            <!-- Total Akhir -->
                                            <td class="px-4 py-4 text-center bg-emerald-100 border-l-2 border-emerald-300">
                                                <span class="text-xl font-black text-emerald-700" 
                                                      x-text="(() => {
                                                          let total = 0;
                                                          for (let katId in kategoris) {
                                                              let kat = kategoris[katId];
                                                              let subtotal = 0;
                                                              for (let itemId in kat.items) {
                                                                  let item = kat.items[itemId];
                                                                  subtotal += (parseFloat(item.nilai) || 0) * item.persentase / 100;
                                                              }
                                                              total += subtotal * kat.bobot / 100;
                                                          }
                                                          return total.toFixed(2);
                                                      })()"></span>
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
