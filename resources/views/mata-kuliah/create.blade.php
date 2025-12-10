<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">TAMBAH MATA KULIAH</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">Buat mata kuliah baru.</p>
                </div>
                <a href="{{ route('mata-kuliah.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-bold rounded-lg shadow transition-all">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
                <form action="{{ route('mata-kuliah.store') }}" method="POST" class="p-8">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Kode -->
                        <div>
                            <label for="kode" class="block text-sm font-bold text-gray-700 mb-2">Kode Mata Kuliah <span class="text-red-500">*</span></label>
                            <input type="text" name="kode" id="kode" value="{{ old('kode') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium @error('kode') border-red-500 @enderror"
                                   placeholder="Contoh: TI-301" required>
                            @error('kode')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama -->
                        <div>
                            <label for="nama" class="block text-sm font-bold text-gray-700 mb-2">Nama Mata Kuliah <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium @error('nama') border-red-500 @enderror"
                                   placeholder="Contoh: Project Based Learning" required>
                            @error('nama')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- SKS -->
                        <div>
                            <label for="sks" class="block text-sm font-bold text-gray-700 mb-2">SKS <span class="text-red-500">*</span></label>
                            <input type="number" name="sks" id="sks" value="{{ old('sks', 3) }}" min="1" max="6"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium @error('sks') border-red-500 @enderror"
                                   required>
                            @error('sks')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="deskripsi" class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="4"
                                      class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium @error('deskripsi') border-red-500 @enderror"
                                      placeholder="Deskripsi mata kuliah (opsional)">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dosen Sebelum UTS -->
                        <div>
                            <label for="dosen_sebelum_uts_id" class="block text-sm font-bold text-gray-700 mb-2">
                                <i class="fa-solid fa-calendar text-blue-500 mr-1"></i>
                                Dosen Pengampu (Sebelum UTS - Minggu 1-8)
                            </label>
                            <select name="dosen_sebelum_uts_id" id="dosen_sebelum_uts_id"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium @error('dosen_sebelum_uts_id') border-red-500 @enderror">
                                <option value="">-- Pilih Dosen (Optional) --</option>
                                @foreach($dosens as $dosen)
                                    <option value="{{ $dosen->id }}" {{ old('dosen_sebelum_uts_id') == $dosen->id ? 'selected' : '' }}>
                                        {{ $dosen->name }} - {{ $dosen->email }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Dosen yang mengampu minggu 1-8 (sebelum UTS)</p>
                            @error('dosen_sebelum_uts_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dosen Sesudah UTS -->
                        <div>
                            <label for="dosen_sesudah_uts_id" class="block text-sm font-bold text-gray-700 mb-2">
                                <i class="fa-solid fa-calendar-check text-green-500 mr-1"></i>
                                Dosen Pengampu (Sesudah UTS - Minggu 9-16)
                            </label>
                            <select name="dosen_sesudah_uts_id" id="dosen_sesudah_uts_id"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 font-medium @error('dosen_sesudah_uts_id') border-red-500 @enderror">
                                <option value="">-- Pilih Dosen (Optional) --</option>
                                @foreach($dosens as $dosen)
                                    <option value="{{ $dosen->id }}" {{ old('dosen_sesudah_uts_id') == $dosen->id ? 'selected' : '' }}>
                                        {{ $dosen->name }} - {{ $dosen->email }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Dosen yang mengampu minggu 9-16 (sesudah UTS)</p>
                            @error('dosen_sesudah_uts_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="mt-8 flex justify-end gap-3">
                        <a href="{{ route('mata-kuliah.index') }}" 
                           class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition-all">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-lg transition-all">
                            <i class="fa-solid fa-save mr-2"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
