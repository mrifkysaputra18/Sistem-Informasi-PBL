<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">EDIT MATA KULIAH</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">{{ $mataKuliah->kode }} - {{ $mataKuliah->nama }}</p>
                </div>
                <a href="{{ route('mata-kuliah.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-bold rounded-lg shadow transition-all">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
                <form action="{{ route('mata-kuliah.update', $mataKuliah) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <!-- Kode -->
                        <div>
                            <label for="kode" class="block text-sm font-bold text-gray-700 mb-2">Kode Mata Kuliah <span class="text-red-500">*</span></label>
                            <input type="text" name="kode" id="kode" value="{{ old('kode', $mataKuliah->kode) }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium @error('kode') border-red-500 @enderror"
                                   required>
                            @error('kode')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama -->
                        <div>
                            <label for="nama" class="block text-sm font-bold text-gray-700 mb-2">Nama Mata Kuliah <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $mataKuliah->nama) }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium @error('nama') border-red-500 @enderror"
                                   required>
                            @error('nama')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- SKS -->
                        <div>
                            <label for="sks" class="block text-sm font-bold text-gray-700 mb-2">SKS <span class="text-red-500">*</span></label>
                            <input type="number" name="sks" id="sks" value="{{ old('sks', $mataKuliah->sks) }}" min="1" max="6"
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
                                      class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $mataKuliah->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" name="is_active" value="1"
                                       class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                       {{ old('is_active', $mataKuliah->is_active) ? 'checked' : '' }}>
                                <span class="text-sm font-bold text-gray-700">Aktif</span>
                            </label>
                    </div>

                    <!-- Submit -->
                    <div class="mt-8 flex justify-end gap-3">
                        <a href="{{ route('mata-kuliah.index') }}" 
                           class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition-all">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-lg transition-all">
                            <i class="fa-solid fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
