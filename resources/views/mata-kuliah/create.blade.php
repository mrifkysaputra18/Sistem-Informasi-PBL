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

                        <!-- Dosen -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Dosen Pengampu</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-60 overflow-y-auto border-2 border-gray-200 rounded-lg p-4">
                                @foreach($dosens as $dosen)
                                    <label class="flex items-center space-x-3 p-2 rounded hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="dosen_ids[]" value="{{ $dosen->id }}"
                                               class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                               {{ in_array($dosen->id, old('dosen_ids', [])) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-gray-700">{{ $dosen->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('dosen_ids')
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
