<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- HEADER -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">NILAI RUBRIK MATA KULIAH</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">Input nilai mahasiswa berdasarkan rubrik penilaian per mata kuliah terkait PBL.</p>
                </div>
            </div>

            <!-- Filter -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
                <form method="GET" action="{{ route('nilai-rubrik.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Filter Kelas</label>
                        <select name="class_room_id" class="w-full h-12 bg-gray-50 border-2 border-gray-200 rounded-lg text-sm font-semibold focus:border-indigo-600">
                            <option value="">Semua Kelas</option>
                            @foreach($classRooms as $cr)
                                <option value="{{ $cr->id }}" {{ request('class_room_id') == $cr->id ? 'selected' : '' }}>
                                    {{ $cr->name }} ({{ $cr->academicPeriod?->name ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="h-12 px-6 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg">
                        <i class="fa-solid fa-filter mr-2"></i> Filter
                    </button>
                </form>
            </div>

            <!-- Daftar Kelas-Mata Kuliah -->
            <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="bg-gray-900 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <i class="fa-solid fa-list-check mr-3 text-indigo-400"></i>
                        Daftar Mata Kuliah untuk Dinilai
                    </h3>
                </div>

                @if($kelasMataKuliahs->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Kelas</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Mata Kuliah</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Rubrik</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase">Progress</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($kelasMataKuliahs as $km)
                                    @php
                                        $totalMahasiswa = $km->classRoom->students->count();
                                        $mahasiswaDinilai = $km->nilaiRubriks()->distinct('user_id')->count('user_id');
                                        $progress = $totalMahasiswa > 0 ? round(($mahasiswaDinilai / $totalMahasiswa) * 100) : 0;
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold mr-3">
                                                    {{ strtoupper(substr($km->classRoom->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-900">{{ $km->classRoom->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $km->classRoom->academicPeriod?->name ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="font-bold text-gray-900">{{ $km->mataKuliah->nama }}</p>
                                            <p class="text-xs text-gray-500 font-mono">{{ $km->mataKuliah->kode }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="font-semibold text-gray-800">{{ $km->rubrikPenilaian->nama }}</p>
                                            <p class="text-xs text-gray-500">{{ $km->rubrikPenilaian->items->count() }} item</p>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="inline-flex flex-col items-center">
                                                <span class="text-lg font-black {{ $progress == 100 ? 'text-emerald-600' : 'text-indigo-600' }}">{{ $progress }}%</span>
                                                <span class="text-xs text-gray-500">{{ $mahasiswaDinilai }}/{{ $totalMahasiswa }} mahasiswa</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('nilai-rubrik.input', $km) }}" 
                                                   class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded shadow transition-all">
                                                    <i class="fa-solid fa-edit mr-1"></i> Input Nilai
                                                </a>
                                                <a href="{{ route('nilai-rubrik.rekap', $km) }}" 
                                                   class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-xs font-bold rounded shadow transition-all">
                                                    <i class="fa-solid fa-chart-bar mr-1"></i> Rekap
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="py-16 text-center">
                        <div class="inline-block p-5 rounded-full bg-gray-100 mb-4">
                            <i class="fa-solid fa-clipboard-list text-4xl text-gray-300"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-700">Belum Ada Data</h4>
                        <p class="text-sm text-gray-500 mt-1">
                            @if(auth()->user()->isDosen())
                                Tidak ada mata kuliah yang terkait dengan Anda, atau rubrik belum dipilih.
                            @else
                                Belum ada kelas-mata kuliah dengan rubrik yang dipilih.
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
