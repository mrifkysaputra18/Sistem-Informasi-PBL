<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
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
            <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden" x-data="rubrikForm()">
                <form action="{{ route('rubrik-penilaian.update', [$mataKuliah, $rubrikPenilaian]) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <!-- Nama Rubrik -->
                        <div>
                            <label for="nama" class="block text-sm font-bold text-gray-700 mb-2">Nama Rubrik <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $rubrikPenilaian->nama) }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium"
                                   placeholder="Contoh: Rubrik PBL Semester 3 - 2024/2025" required>
                        </div>

                        <!-- Periode Akademik -->
                        <div>
                            <label for="periode_akademik_id" class="block text-sm font-bold text-gray-700 mb-2">Periode Akademik <span class="text-red-500">*</span></label>
                            <select name="periode_akademik_id" id="periode_akademik_id" 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium" required>
                                <option value="">Pilih Periode</option>
                                @foreach($periodeAkademiks as $periode)
                                    <option value="{{ $periode->id }}" {{ old('periode_akademik_id', $rubrikPenilaian->periode_akademik_id) == $periode->id ? 'selected' : '' }}>
                                        {{ $periode->name }} ({{ $periode->academic_year }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="deskripsi" class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="2"
                                      class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium"
                                      placeholder="Deskripsi rubrik (opsional)">{{ old('deskripsi', $rubrikPenilaian->deskripsi) }}</textarea>
                        </div>

                        <!-- Bobot UTS dan UAS -->
                        <div class="border-t-2 border-gray-200 pt-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">
                                <i class="fa-solid fa-scale-balanced mr-2 text-indigo-600"></i> Bobot Penilaian
                            </h3>
                            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                    <div>
                                        <label class="block text-sm font-bold text-indigo-800 mb-2">Bobot UTS <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <input type="number" name="bobot_uts" x-model.number="bobotUts"
                                                   class="w-full px-4 py-3 border-2 border-indigo-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-bold text-lg"
                                                   min="0" max="100" step="1" required>
                                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-indigo-600 font-bold">%</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-indigo-800 mb-2">Bobot UAS <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <input type="number" name="bobot_uas" x-model.number="bobotUas"
                                                   class="w-full px-4 py-3 border-2 border-indigo-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-bold text-lg"
                                                   min="0" max="100" step="1" required>
                                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-indigo-600 font-bold">%</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-center">
                                        <div class="text-center px-6 py-4 rounded-lg" 
                                             :class="(bobotUts + bobotUas) == 100 ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800'">
                                            <span class="block text-xs font-semibold uppercase">Total Bobot</span>
                                            <span class="text-2xl font-black" x-text="bobotUts + bobotUas"></span>%
                                            <span x-show="(bobotUts + bobotUas) == 100" class="ml-1"><i class="fa-solid fa-check-circle"></i></span>
                                            <span x-show="(bobotUts + bobotUas) != 100" class="ml-1"><i class="fa-solid fa-exclamation-circle"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Item UTS -->
                        <div class="border-t-2 border-gray-200 pt-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-blue-900">
                                    <i class="fa-solid fa-file-lines mr-2 text-blue-600"></i> Item Penilaian UTS
                                    <span class="ml-2 text-sm font-normal text-blue-600">(Minggu 1-8)</span>
                                </h3>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm font-bold" :class="totalUts == 100 ? 'text-emerald-600' : 'text-red-600'">
                                        Total: <span x-text="totalUts"></span>%
                                        <span x-show="totalUts == 100" class="ml-1"><i class="fa-solid fa-check-circle"></i></span>
                                        <span x-show="totalUts != 100" class="ml-1"><i class="fa-solid fa-exclamation-circle"></i></span>
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-3 bg-blue-50 p-4 rounded-lg border border-blue-200">
                                <template x-for="(item, index) in itemsUts" :key="'uts-' + index">
                                    <div class="flex gap-3 items-start p-4 bg-white rounded-lg border border-blue-300">
                                        <div class="flex-1 grid grid-cols-1 md:grid-cols-12 gap-3">
                                            <input type="hidden" :name="'items_uts[' + index + '][id]'" :value="item.id">
                                            <div class="md:col-span-5">
                                                <input type="text" :name="'items_uts[' + index + '][nama]'" x-model="item.nama"
                                                       class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-medium"
                                                       placeholder="Nama (Pemahaman Konsep, dll)" required>
                                            </div>
                                            <div class="md:col-span-2">
                                                <div class="relative">
                                                    <input type="number" :name="'items_uts[' + index + '][persentase]'" x-model.number="item.persentase"
                                                           class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-medium"
                                                           placeholder="%" min="0" max="100" step="0.01" required>
                                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-bold">%</span>
                                                </div>
                                            </div>
                                            <div class="md:col-span-4">
                                                <input type="text" :name="'items_uts[' + index + '][deskripsi]'" x-model="item.deskripsi"
                                                       class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                                       placeholder="Deskripsi (opsional)">
                                            </div>
                                            <div class="md:col-span-1 flex items-center justify-center">
                                                <button type="button" @click="removeItemUts(index)" 
                                                        class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors"
                                                        x-show="itemsUts.length > 1">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <button type="button" @click="addItemUts()" 
                                        class="inline-flex items-center px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 font-bold rounded-lg transition-all">
                                    <i class="fa-solid fa-plus mr-2"></i> Tambah Item UTS
                                </button>
                            </div>
                        </div>

                        <!-- Item UAS -->
                        <div class="border-t-2 border-gray-200 pt-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-orange-900">
                                    <i class="fa-solid fa-file-lines mr-2 text-orange-600"></i> Item Penilaian UAS
                                    <span class="ml-2 text-sm font-normal text-orange-600">(Minggu 9-16)</span>
                                </h3>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm font-bold" :class="totalUas == 100 ? 'text-emerald-600' : 'text-red-600'">
                                        Total: <span x-text="totalUas"></span>%
                                        <span x-show="totalUas == 100" class="ml-1"><i class="fa-solid fa-check-circle"></i></span>
                                        <span x-show="totalUas != 100" class="ml-1"><i class="fa-solid fa-exclamation-circle"></i></span>
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-3 bg-orange-50 p-4 rounded-lg border border-orange-200">
                                <template x-for="(item, index) in itemsUas" :key="'uas-' + index">
                                    <div class="flex gap-3 items-start p-4 bg-white rounded-lg border border-orange-300">
                                        <div class="flex-1 grid grid-cols-1 md:grid-cols-12 gap-3">
                                            <input type="hidden" :name="'items_uas[' + index + '][id]'" :value="item.id">
                                            <div class="md:col-span-5">
                                                <input type="text" :name="'items_uas[' + index + '][nama]'" x-model="item.nama"
                                                       class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm font-medium"
                                                       placeholder="Nama (Implementasi, dll)" required>
                                            </div>
                                            <div class="md:col-span-2">
                                                <div class="relative">
                                                    <input type="number" :name="'items_uas[' + index + '][persentase]'" x-model.number="item.persentase"
                                                           class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm font-medium"
                                                           placeholder="%" min="0" max="100" step="0.01" required>
                                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-bold">%</span>
                                                </div>
                                            </div>
                                            <div class="md:col-span-4">
                                                <input type="text" :name="'items_uas[' + index + '][deskripsi]'" x-model="item.deskripsi"
                                                       class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm"
                                                       placeholder="Deskripsi (opsional)">
                                            </div>
                                            <div class="md:col-span-1 flex items-center justify-center">
                                                <button type="button" @click="removeItemUas(index)" 
                                                        class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors"
                                                        x-show="itemsUas.length > 1">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <button type="button" @click="addItemUas()" 
                                        class="inline-flex items-center px-4 py-2 bg-orange-100 hover:bg-orange-200 text-orange-700 font-bold rounded-lg transition-all">
                                    <i class="fa-solid fa-plus mr-2"></i> Tambah Item UAS
                                </button>
                            </div>
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
                                :disabled="!isFormValid"
                                :class="!isFormValid ? 'opacity-50 cursor-not-allowed' : ''">
                            <i class="fa-solid fa-save mr-2"></i> Update Rubrik
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @php
        // Prepare items data for JavaScript
        $itemsUtsData = $rubrikPenilaian->items->where('periode_ujian', 'uts')->values()->map(function($i) {
            return [
                'id' => $i->id,
                'nama' => $i->nama,
                'persentase' => floatval($i->persentase),
                'deskripsi' => $i->deskripsi ?? ''
            ];
        })->toArray();
        
        $itemsUasData = $rubrikPenilaian->items->where('periode_ujian', 'uas')->values()->map(function($i) {
            return [
                'id' => $i->id,
                'nama' => $i->nama,
                'persentase' => floatval($i->persentase),
                'deskripsi' => $i->deskripsi ?? ''
            ];
        })->toArray();
        
        // Ensure at least one empty item if no items exist
        if (empty($itemsUtsData)) {
            $itemsUtsData = [['id' => null, 'nama' => '', 'persentase' => '', 'deskripsi' => '']];
        }
        if (empty($itemsUasData)) {
            $itemsUasData = [['id' => null, 'nama' => '', 'persentase' => '', 'deskripsi' => '']];
        }
    @endphp

    @push('scripts')
    <script>
        function rubrikForm() {
            return {
                bobotUts: {{ old('bobot_uts', $rubrikPenilaian->bobot_uts ?? 50) }},
                bobotUas: {{ old('bobot_uas', $rubrikPenilaian->bobot_uas ?? 50) }},
                itemsUts: @json($itemsUtsData),
                itemsUas: @json($itemsUasData),
                get totalUts() {
                    return this.itemsUts.reduce((sum, item) => sum + (parseFloat(item.persentase) || 0), 0);
                },
                get totalUas() {
                    return this.itemsUas.reduce((sum, item) => sum + (parseFloat(item.persentase) || 0), 0);
                },
                get isFormValid() {
                    return (this.bobotUts + this.bobotUas) == 100 && 
                           this.totalUts == 100 && 
                           this.totalUas == 100;
                },
                addItemUts() {
                    this.itemsUts.push({ id: null, nama: '', persentase: '', deskripsi: '' });
                },
                removeItemUts(index) {
                    if (this.itemsUts.length > 1) {
                        this.itemsUts.splice(index, 1);
                    }
                },
                addItemUas() {
                    this.itemsUas.push({ id: null, nama: '', persentase: '', deskripsi: '' });
                },
                removeItemUas(index) {
                    if (this.itemsUas.length > 1) {
                        this.itemsUas.splice(index, 1);
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
