<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- HEADER -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                <div>
                    <nav class="text-sm mb-2">
                        <a href="{{ route('nilai-rubrik.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Nilai Rubrik</a>
                        <span class="text-gray-400 mx-2">/</span>
                        <span class="text-gray-500 font-semibold">Rekap Nilai</span>
                    </nav>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">REKAP NILAI</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">
                        {{ $kelasMataKuliah->mataKuliah->nama }} - Kelas {{ $kelasMataKuliah->classRoom->name }}
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('nilai-rubrik.input', $kelasMataKuliah) }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg shadow transition-all">
                        <i class="fa-solid fa-edit mr-2"></i> Edit Nilai
                    </a>
                    <a href="{{ route('nilai-rubrik.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-bold rounded-lg shadow transition-all">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>

            <!-- Info Rubrik -->
            <div class="bg-white rounded-xl shadow-md border-l-4 border-emerald-600 p-6 mb-8">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $kelasMataKuliah->rubrikPenilaian->nama }}</h3>
                        <p class="text-sm text-gray-500 mt-1">Komponen penilaian:</p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($rubrikItems as $item)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                    {{ $item->nama }} ({{ $item->persentase }}%)
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-gray-400 uppercase">Mahasiswa Dinilai</p>
                        <p class="text-3xl font-black text-emerald-600">{{ count($rekapNilai) }}</p>
                    </div>
                </div>
            </div>

            <!-- Tabel Rekap -->
            <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="bg-gray-900 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <i class="fa-solid fa-chart-bar mr-3 text-emerald-400"></i>
                        Rekap Nilai Mahasiswa
                    </h3>
                </div>

                @if(count($rekapNilai) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-4 text-center text-xs font-bold text-gray-600 uppercase w-16">Rank</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Mahasiswa</th>
                                    @foreach($rubrikItems as $item)
                                        <th class="px-4 py-4 text-center text-xs font-bold text-gray-600 uppercase min-w-[100px]">
                                            {{ $item->nama }}<br>
                                            <span class="text-indigo-600">({{ $item->persentase }}%)</span>
                                        </th>
                                    @endforeach
                                    <th class="px-4 py-4 text-center text-xs font-bold text-emerald-700 uppercase bg-emerald-50">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @php $rank = 1; @endphp
                                @foreach($rekapNilai as $userId => $data)
                                    <tr class="hover:bg-gray-50 transition-colors {{ $rank <= 3 ? 'bg-yellow-50' : '' }}">
                                        <td class="px-4 py-4 text-center">
                                            @if($rank == 1)
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-400 text-yellow-900 font-black">
                                                    <i class="fa-solid fa-crown"></i>
                                                </span>
                                            @elseif($rank == 2)
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-300 text-gray-700 font-black">2</span>
                                            @elseif($rank == 3)
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-orange-300 text-orange-800 font-black">3</span>
                                            @else
                                                <span class="font-bold text-gray-500">{{ $rank }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold mr-3">
                                                    {{ strtoupper(substr($data['mahasiswa']->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-900">{{ $data['mahasiswa']->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $data['mahasiswa']->nim ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        @foreach($rubrikItems as $item)
                                            @php
                                                $nilaiItem = $data['nilai_per_item']->firstWhere('rubrik_item_id', $item->id);
                                            @endphp
                                            <td class="px-4 py-4 text-center">
                                                @if($nilaiItem)
                                                    <span class="font-bold text-gray-800">{{ number_format($nilaiItem->nilai, 1) }}</span>
                                                    <span class="block text-xs text-gray-400">({{ number_format($nilaiItem->nilai_terbobot, 2) }})</span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="px-4 py-4 text-center bg-emerald-50">
                                            <span class="text-xl font-black {{ $data['total'] >= 80 ? 'text-emerald-700' : ($data['total'] >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                                {{ number_format($data['total'], 2) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @php $rank++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Statistik -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        @php
                            $totalValues = collect($rekapNilai)->pluck('total');
                            $avg = $totalValues->avg();
                            $max = $totalValues->max();
                            $min = $totalValues->min();
                        @endphp
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase">Rata-rata</p>
                                <p class="text-2xl font-black text-indigo-600">{{ number_format($avg, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase">Tertinggi</p>
                                <p class="text-2xl font-black text-emerald-600">{{ number_format($max, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase">Terendah</p>
                                <p class="text-2xl font-black text-red-600">{{ number_format($min, 2) }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="py-16 text-center">
                        <div class="inline-block p-5 rounded-full bg-gray-100 mb-4">
                            <i class="fa-solid fa-chart-bar text-4xl text-gray-300"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-700">Belum Ada Nilai</h4>
                        <p class="text-sm text-gray-500 mt-1">Silakan input nilai terlebih dahulu.</p>
                        <a href="{{ route('nilai-rubrik.input', $kelasMataKuliah) }}" 
                           class="inline-flex items-center mt-4 px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg">
                            <i class="fa-solid fa-edit mr-2"></i> Input Nilai
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
