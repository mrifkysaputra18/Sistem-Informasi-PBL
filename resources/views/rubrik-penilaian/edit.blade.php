<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">EDIT RUBRIK PENILAIAN</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">{{ $mataKuliah->kode }} - {{ $mataKuliah->nama }}</p>
                </div>
                <a href="{{ route('mata-kuliah.show', $mataKuliah) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-bold rounded-lg shadow transition-all">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>

            @if($errors->any())
                <div class="bg-red-100 border-l-8 border-red-600 text-red-800 px-6 py-4 rounded-lg shadow-md mb-8">
                    <div class="flex items-center">
                        <i class="fa-solid fa-exclamation-circle text-2xl mr-4 text-red-600"></i>
                        <div>
                            <span class="font-bold text-lg">Terjadi kesalahan!</span>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden" x-data="rubrikEditForm()">
                <form action="{{ route('rubrik-penilaian.update', [$mataKuliah, $rubrikPenilaian]) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <!-- Nama Rubrik -->
                        <div>
                            <label for="nama" class="block text-sm font-bold text-gray-700 mb-2">Nama Rubrik <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $rubrikPenilaian->nama) }}" 
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
                                        <option value="{{ $periode->id }}" {{ old('periode_akademik_id', $rubrikPenilaian->periode_akademik_id) == $periode->id ? 'selected' : '' }}>
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

                        <!-- Deskripsi -->
                        <div>
                            <label for="deskripsi" class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="2"
                                      class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium">{{ old('deskripsi', $rubrikPenilaian->deskripsi) }}</textarea>
                        </div>

                        <!-- Rubrik Items -->
                        <div class="border-t-2 border-gray-200 pt-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-900">
                                    <i class="fa-solid fa-list-check mr-2 text-indigo-600"></i> Item Penilaian
                                </h3>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm font-bold" :class="totalPersentase == 100 ? 'text-emerald-600' : 'text-red-600'">
                                        Total: <span x-text="totalPersentase"></span>%
                                        <span x-show="totalPersentase == 100" class="ml-1"><i class="fa-solid fa-check-circle"></i></span>
                                        <span x-show="totalPersentase != 100" class="ml-1"><i class="fa-solid fa-exclamation-circle"></i></span>
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-3" id="items-container">
                                <template x-for="(item, index) in items" :key="index">
                                    <div class="flex gap-3 items-start p-4 bg-gray-50 rounded-lg border border-gray-200">
                                        <div class="flex-1 grid grid-cols-1 md:grid-cols-12 gap-3">
                                            <input type="hidden" :name="'items[' + index + '][id]'" x-model="item.id">
                                            <div class="md:col-span-5">
                                                <input type="text" :name="'items[' + index + '][nama]'" x-model="item.nama"
                                                       class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-medium"
                                                       placeholder="Nama (UTS, UAS, Tugas, dll)" required>
                                            </div>
                                            <div class="md:col-span-2">
                                                <div class="relative">
                                                    <input type="number" :name="'items[' + index + '][persentase]'" x-model.number="item.persentase"
                                                           class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-medium"
                                                           placeholder="%" min="0" max="100" step="0.01" required>
                                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-bold">%</span>
                                                </div>
                                            </div>
                                            <div class="md:col-span-4">
                                                <input type="text" :name="'items[' + index + '][deskripsi]'" x-model="item.deskripsi"
                                                       class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                                       placeholder="Deskripsi (opsional)">
                                            </div>
                                            <div class="md:col-span-1 flex items-center justify-center">
                                                <button type="button" @click="removeItem(index)" 
                                                        class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors"
                                                        x-show="items.length > 1">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <button type="button" @click="addItem()" 
                                    class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 font-bold rounded-lg transition-all">
                                <i class="fa-solid fa-plus mr-2"></i> Tambah Item
                            </button>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="mt-8 flex justify-end gap-3 border-t-2 border-gray-200 pt-6">
                        <a href="{{ route('mata-kuliah.show', $mataKuliah) }}" 
                           class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition-all">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow-lg transition-all"
                                :disabled="totalPersentase != 100"
                                :class="totalPersentase != 100 ? 'opacity-50 cursor-not-allowed' : ''">
                            <i class="fa-solid fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function rubrikEditForm() {
            return {
                items: @json($rubrikPenilaian->items->map(fn($item) => [
                    'id' => $item->id,
                    'nama' => $item->nama,
                    'persentase' => floatval($item->persentase),
                    'deskripsi' => $item->deskripsi ?? ''
                ])),
                get totalPersentase() {
                    return this.items.reduce((sum, item) => sum + (parseFloat(item.persentase) || 0), 0);
                },
                addItem() {
                    this.items.push({ id: null, nama: '', persentase: '', deskripsi: '' });
                },
                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
