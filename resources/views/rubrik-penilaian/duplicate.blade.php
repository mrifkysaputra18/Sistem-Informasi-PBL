<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">DUPLIKAT RUBRIK PENILAIAN</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">{{ $mataKuliah->kode }} - {{ $mataKuliah->nama }}</p>
                </div>
                <a href="{{ route('mata-kuliah.show', $mataKuliah) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-bold rounded-lg shadow transition-all">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>

            <!-- Source Rubrik Info -->
            <div class="bg-orange-50 border border-orange-200 rounded-xl p-6 mb-8">
                <h3 class="text-lg font-bold text-orange-800 mb-3">
                    <i class="fa-solid fa-copy mr-2"></i> Rubrik Sumber
                </h3>
                <div class="text-sm text-orange-700">
                    <p><strong>{{ $rubrikPenilaian->nama }}</strong></p>
                    <p>{{ $rubrikPenilaian->periodeAkademik->name ?? '-' }} - Semester {{ $rubrikPenilaian->semester }}</p>
                </div>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach($rubrikPenilaian->items as $item)
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-orange-100 text-orange-800 border border-orange-200">
                            {{ $item->nama }}: {{ number_format($item->persentase, 0) }}%
                        </span>
                    @endforeach
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
                <form action="{{ route('rubrik-penilaian.store-duplicate', [$mataKuliah, $rubrikPenilaian]) }}" method="POST" class="p-8">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Nama Rubrik Baru -->
                        <div>
                            <label for="nama" class="block text-sm font-bold text-gray-700 mb-2">Nama Rubrik Baru <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" id="nama" 
                                   value="{{ old('nama', $rubrikPenilaian->nama . ' (Copy)') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium"
                                   required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Periode Akademik -->
                            <div>
                                <label for="periode_akademik_id" class="block text-sm font-bold text-gray-700 mb-2">Periode Akademik <span class="text-red-500">*</span></label>
                                <select name="periode_akademik_id" id="periode_akademik_id" 
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium" required>
                                    @foreach($periodeAkademiks as $periode)
                                        <option value="{{ $periode->id }}" {{ old('periode_akademik_id') == $periode->id ? 'selected' : '' }}>
                                            {{ $periode->name }} ({{ $periode->academic_year }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Semester -->
                            <div>
                                <label for="semester" class="block text-sm font-bold text-gray-700 mb-2">Semester <span class="text-red-500">*</span></label>
                                <select name="semester" id="semester" 
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium" required>
                                    @for($i = 1; $i <= 8; $i++)
                                        <option value="{{ $i }}" {{ old('semester', $rubrikPenilaian->semester) == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="mt-8 flex justify-end gap-3">
                        <a href="{{ route('mata-kuliah.show', $mataKuliah) }}" 
                           class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition-all">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-lg shadow-lg transition-all">
                            <i class="fa-solid fa-copy mr-2"></i> Duplikat Rubrik
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
