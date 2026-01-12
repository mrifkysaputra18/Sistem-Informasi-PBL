<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">BUAT RUBRIK PENILAIAN</h2>
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
                <form action="{{ route('rubrik-penilaian.store', $mataKuliah) }}" method="POST" class="p-8">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Nama Rubrik -->
                        <div>
                            <label for="nama" class="block text-sm font-bold text-gray-700 mb-2">Nama Rubrik <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}" 
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
                                    <option value="{{ $periode->id }}" {{ old('periode_akademik_id') == $periode->id ? 'selected' : '' }}>
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
                                      placeholder="Deskripsi rubrik (opsional)">{{ old('deskripsi') }}</textarea>
                        </div>

                        <!-- Total Bobot Kategori -->
                        <div class="border-t-2 border-gray-200 pt-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-900">
                                    <i class="fa-solid fa-layer-group mr-2 text-indigo-600"></i> Kategori Penilaian
                                </h3>
                                <div class="flex items-center gap-4">
                                    <div class="text-center px-4 py-2 rounded-lg" 
                                         :class="totalBobotKategori == 100 ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800'">
                                        <span class="text-xs font-semibold uppercase">Total Bobot</span>
                                        <span class="ml-1 text-lg font-black" x-text="totalBobotKategori"></span>%
                                        <span x-show="totalBobotKategori == 100" class="ml-1"><i class="fa-solid fa-check-circle"></i></span>
                                        <span x-show="totalBobotKategori != 100" class="ml-1"><i class="fa-solid fa-exclamation-circle"></i></span>
                                    </div>
                                </div>
                            </div>

                            <p class="text-sm text-gray-500 mb-4">
                                <i class="fa-solid fa-info-circle mr-1"></i>
                                Tambahkan kategori penilaian. Total bobot = 100%, total item per kategori = 100%. Mendukung 3 level: Item → Sub-Item → Sub-Sub-Item.
                            </p>

                            <!-- Daftar Kategori -->
                            <div class="space-y-4">
                                <template x-for="(kategori, kIndex) in kategoris" :key="'kat-' + kIndex">
                                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl border-2 border-indigo-200 overflow-hidden">
                                        <!-- Header Kategori -->
                                        <div class="bg-indigo-100 px-4 py-3 flex items-center justify-between">
                                            <div class="flex items-center gap-3 flex-1">
                                                <button type="button" @click="kategori.expanded = !kategori.expanded" 
                                                        class="p-1 text-indigo-600 hover:bg-indigo-200 rounded transition-colors">
                                                    <i class="fa-solid" :class="kategori.expanded ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                                </button>
                                                <div class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-3">
                                                    <div class="md:col-span-2">
                                                        <input type="text" :name="'kategoris[' + kIndex + '][nama]'" x-model="kategori.nama"
                                                               class="w-full px-3 py-2 border-2 border-indigo-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-bold"
                                                               placeholder="Nama Kategori (UTS, Quiz, dll)" required>
                                                    </div>
                                                    <div>
                                                        <div class="relative">
                                                            <input type="number" :name="'kategoris[' + kIndex + '][bobot]'" x-model.number="kategori.bobot"
                                                                   class="w-full px-3 py-2 border-2 border-indigo-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-bold"
                                                                   placeholder="Bobot" min="0" max="100" step="1" required>
                                                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-indigo-600 font-bold">%</span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <input type="text" :name="'kategoris[' + kIndex + '][deskripsi]'" x-model="kategori.deskripsi"
                                                               class="w-full px-3 py-2 border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                                               placeholder="Deskripsi (opsional)">
                                                        <input type="hidden" :name="'kategoris[' + kIndex + '][kode]'" :value="kategori.kode">
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" @click="removeKategori(kIndex)" 
                                                    class="ml-2 p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors"
                                                    x-show="kategoris.length > 1">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Items dalam Kategori -->
                                        <div x-show="kategori.expanded" x-collapse class="p-4">
                                            <div class="flex items-center justify-between mb-3">
                                                <span class="text-sm font-bold text-indigo-700">
                                                    <i class="fa-solid fa-list-check mr-1"></i> Item Penilaian
                                                </span>
                                                <span class="text-sm font-bold" 
                                                      :class="getItemsTotal(kategori.items) == 100 ? 'text-emerald-600' : 'text-red-600'">
                                                    Total: <span x-text="getItemsTotal(kategori.items).toFixed(2)"></span>%
                                                </span>
                                            </div>

                                            <div class="space-y-3" x-data="{ renderItems(items, kIndex, prefix, level) { return items; } }">
                                                <!-- Level 0: Items -->
                                                <template x-for="(item, iIndex) in kategori.items" :key="'item-' + kIndex + '-' + iIndex">
                                                    <div class="bg-white rounded-lg border border-indigo-200 overflow-hidden">
                                                        <div class="flex gap-3 items-start p-4">
                                                            <div class="flex-1 grid grid-cols-1 md:grid-cols-12 gap-3">
                                                                <div class="md:col-span-5">
                                                                    <input type="text" :name="'kategoris[' + kIndex + '][items][' + iIndex + '][nama]'" x-model="item.nama"
                                                                           class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm font-medium"
                                                                           placeholder="Nama Item" required>
                                                                </div>
                                                                <div class="md:col-span-2">
                                                                    <div class="relative">
                                                                        <input type="number" :name="'kategoris[' + kIndex + '][items][' + iIndex + '][persentase]'" x-model.number="item.persentase"
                                                                               class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm font-medium"
                                                                               placeholder="%" min="0" max="100" step="0.01" required>
                                                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-bold">%</span>
                                                                    </div>
                                                                </div>
                                                                <div class="md:col-span-3">
                                                                    <input type="text" :name="'kategoris[' + kIndex + '][items][' + iIndex + '][deskripsi]'" x-model="item.deskripsi"
                                                                           class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm"
                                                                           placeholder="Deskripsi">
                                                                </div>
                                                                <div class="md:col-span-2 flex items-center justify-center gap-1">
                                                                    <button type="button" @click="addSubItem(kIndex, iIndex)" 
                                                                            class="p-2 text-indigo-600 hover:bg-indigo-100 rounded-lg transition-colors" title="Tambah Sub-Item">
                                                                        <i class="fa-solid fa-plus"></i>
                                                                    </button>
                                                                    <button type="button" @click="item.expanded = !item.expanded" 
                                                                            class="p-2 text-emerald-600 hover:bg-emerald-100 rounded-lg transition-colors" title="Toggle Sub-Item"
                                                                            x-show="item.subItems?.length > 0">
                                                                        <i class="fa-solid fa-layer-group"></i>
                                                                    </button>
                                                                    <button type="button" @click="removeItem(kIndex, iIndex)" 
                                                                            class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors" x-show="kategori.items.length > 1">
                                                                        <i class="fa-solid fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Level 1: Sub-Items -->
                                                        <template x-if="item.subItems && item.subItems.length > 0">
                                                            <div x-show="item.expanded" x-collapse class="border-t border-indigo-200 bg-blue-50/50 p-4">
                                                                <div class="flex items-center justify-between mb-3">
                                                                    <span class="text-xs font-bold text-blue-700 uppercase"><i class="fa-solid fa-layer-group mr-1"></i> Sub-Items</span>
                                                                    <span class="text-xs font-bold" :class="getSubItemsTotal(item.subItems) == 100 ? 'text-emerald-600' : 'text-red-600'">
                                                                        Total: <span x-text="getSubItemsTotal(item.subItems).toFixed(2)"></span>%
                                                                    </span>
                                                                </div>
                                                                <div class="space-y-2 ml-4 border-l-4 border-blue-300 pl-4">
                                                                    <template x-for="(subItem, sIndex) in item.subItems" :key="'sub-' + kIndex + '-' + iIndex + '-' + sIndex">
                                                                        <div class="bg-white rounded-lg border border-blue-200 overflow-hidden">
                                                                            <div class="flex gap-2 items-center p-3">
                                                                                <div class="flex-1 grid grid-cols-1 md:grid-cols-12 gap-2">
                                                                                    <div class="md:col-span-4">
                                                                                        <input type="text" :name="'kategoris[' + kIndex + '][items][' + iIndex + '][sub_items][' + sIndex + '][nama]'" 
                                                                                               x-model="subItem.nama"
                                                                                               class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs" placeholder="Nama sub-item" required>
                                                                                    </div>
                                                                                    <div class="md:col-span-2">
                                                                                        <div class="relative">
                                                                                            <input type="number" :name="'kategoris[' + kIndex + '][items][' + iIndex + '][sub_items][' + sIndex + '][persentase]'" 
                                                                                                   x-model.number="subItem.persentase"
                                                                                                   class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs" placeholder="%" min="0" max="100" step="0.01" required>
                                                                                            <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs">%</span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="md:col-span-3">
                                                                                        <input type="text" :name="'kategoris[' + kIndex + '][items][' + iIndex + '][sub_items][' + sIndex + '][deskripsi]'" 
                                                                                               x-model="subItem.deskripsi"
                                                                                               class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs" placeholder="Deskripsi">
                                                                                    </div>
                                                                                    <div class="md:col-span-3 flex items-center justify-center gap-1">
                                                                                        <button type="button" @click="addSubSubItem(kIndex, iIndex, sIndex)" 
                                                                                                class="p-1 text-purple-600 hover:bg-purple-100 rounded transition-colors" title="Tambah Sub-Sub-Item">
                                                                                            <i class="fa-solid fa-plus text-xs"></i>
                                                                                        </button>
                                                                                        <button type="button" @click="subItem.expanded = !subItem.expanded" 
                                                                                                class="p-1 text-purple-600 hover:bg-purple-100 rounded transition-colors" title="Toggle Sub-Sub-Item"
                                                                                                x-show="subItem.subItems?.length > 0">
                                                                                            <i class="fa-solid fa-sitemap text-xs"></i>
                                                                                        </button>
                                                                                        <button type="button" @click="removeSubItem(kIndex, iIndex, sIndex)" 
                                                                                                class="p-1 text-red-500 hover:bg-red-100 rounded transition-colors">
                                                                                            <i class="fa-solid fa-times text-xs"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <!-- Level 2: Sub-Sub-Items -->
                                                                            <template x-if="subItem.subItems && subItem.subItems.length > 0">
                                                                                <div x-show="subItem.expanded" x-collapse class="border-t border-purple-200 bg-purple-50/50 p-3">
                                                                                    <div class="flex items-center justify-between mb-2">
                                                                                        <span class="text-xs font-bold text-purple-700 uppercase"><i class="fa-solid fa-sitemap mr-1"></i> Sub-Sub-Items</span>
                                                                                        <span class="text-xs font-bold" :class="getSubItemsTotal(subItem.subItems) == 100 ? 'text-emerald-600' : 'text-red-600'">
                                                                                            Total: <span x-text="getSubItemsTotal(subItem.subItems).toFixed(2)"></span>%
                                                                                        </span>
                                                                                    </div>
                                                                                    <div class="space-y-1 ml-3 border-l-4 border-purple-300 pl-3">
                                                                                        <template x-for="(ssItem, ssIndex) in subItem.subItems" :key="'subsub-' + kIndex + '-' + iIndex + '-' + sIndex + '-' + ssIndex">
                                                                                            <div class="flex gap-2 items-center p-2 bg-white rounded border border-purple-200">
                                                                                                <div class="flex-1 grid grid-cols-1 md:grid-cols-12 gap-2">
                                                                                                    <div class="md:col-span-5">
                                                                                                        <input type="text" :name="'kategoris[' + kIndex + '][items][' + iIndex + '][sub_items][' + sIndex + '][sub_items][' + ssIndex + '][nama]'" 
                                                                                                               x-model="ssItem.nama"
                                                                                                               class="w-full px-2 py-1 border border-gray-300 rounded text-xs" placeholder="Nama" required>
                                                                                                    </div>
                                                                                                    <div class="md:col-span-2">
                                                                                                        <div class="relative">
                                                                                                            <input type="number" :name="'kategoris[' + kIndex + '][items][' + iIndex + '][sub_items][' + sIndex + '][sub_items][' + ssIndex + '][persentase]'" 
                                                                                                                   x-model.number="ssItem.persentase"
                                                                                                                   class="w-full px-2 py-1 border border-gray-300 rounded text-xs" placeholder="%" min="0" max="100" step="0.01" required>
                                                                                                            <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs">%</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="md:col-span-4">
                                                                                                        <input type="text" :name="'kategoris[' + kIndex + '][items][' + iIndex + '][sub_items][' + sIndex + '][sub_items][' + ssIndex + '][deskripsi]'" 
                                                                                                               x-model="ssItem.deskripsi"
                                                                                                               class="w-full px-2 py-1 border border-gray-300 rounded text-xs" placeholder="Deskripsi">
                                                                                                    </div>
                                                                                                    <div class="md:col-span-1 flex items-center justify-center">
                                                                                                        <button type="button" @click="removeSubSubItem(kIndex, iIndex, sIndex, ssIndex)" 
                                                                                                                class="p-1 text-red-500 hover:bg-red-100 rounded transition-colors">
                                                                                                            <i class="fa-solid fa-times text-xs"></i>
                                                                                                        </button>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </template>
                                                                                        <button type="button" @click="addSubSubItem(kIndex, iIndex, sIndex)" 
                                                                                                class="inline-flex items-center px-2 py-1 bg-purple-100 hover:bg-purple-200 text-purple-700 text-xs font-bold rounded transition-all">
                                                                                            <i class="fa-solid fa-plus mr-1"></i> Tambah Sub-Sub-Item
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </template>
                                                                        </div>
                                                                    </template>
                                                                    <button type="button" @click="addSubItem(kIndex, iIndex)" 
                                                                            class="inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-bold rounded transition-all">
                                                                        <i class="fa-solid fa-plus mr-1"></i> Tambah Sub-Item
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </template>

                                                <button type="button" @click="addItem(kIndex)" 
                                                        class="inline-flex items-center px-4 py-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 font-bold rounded-lg transition-all">
                                                    <i class="fa-solid fa-plus mr-2"></i> Tambah Item
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <button type="button" @click="addKategori()" 
                                        class="w-full py-4 border-2 border-dashed border-indigo-300 rounded-xl text-indigo-600 font-bold hover:bg-indigo-50 transition-colors">
                                    <i class="fa-solid fa-plus mr-2"></i> Tambah Kategori Penilaian
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
                            <i class="fa-solid fa-save mr-2"></i> Simpan Rubrik
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function rubrikForm() {
            return {
                kategoris: [
                    { 
                        nama: 'UTS', bobot: 50, deskripsi: 'Ujian Tengah Semester', kode: 'uts', expanded: true, 
                        items: [{ nama: '', persentase: '', deskripsi: '', expanded: true, subItems: [] }] 
                    },
                    { 
                        nama: 'UAS', bobot: 50, deskripsi: 'Ujian Akhir Semester', kode: 'uas', expanded: true, 
                        items: [{ nama: '', persentase: '', deskripsi: '', expanded: true, subItems: [] }] 
                    }
                ],
                
                get totalBobotKategori() {
                    return this.kategoris.reduce((sum, k) => sum + (parseFloat(k.bobot) || 0), 0);
                },
                
                getItemsTotal(items) {
                    if (!items || items.length === 0) return 0;
                    return items.reduce((sum, item) => sum + (parseFloat(item.persentase) || 0), 0);
                },
                
                getSubItemsTotal(subItems) {
                    if (!subItems || subItems.length === 0) return 0;
                    return subItems.reduce((sum, item) => sum + (parseFloat(item.persentase) || 0), 0);
                },
                
                areAllItemsValid() {
                    for (let kategori of this.kategoris) {
                        if (this.getItemsTotal(kategori.items) !== 100) return false;
                        for (let item of kategori.items) {
                            if (item.subItems && item.subItems.length > 0) {
                                if (this.getSubItemsTotal(item.subItems) !== 100) return false;
                                for (let subItem of item.subItems) {
                                    if (subItem.subItems && subItem.subItems.length > 0) {
                                        if (this.getSubItemsTotal(subItem.subItems) !== 100) return false;
                                    }
                                }
                            }
                        }
                    }
                    return true;
                },
                
                get isFormValid() {
                    return this.totalBobotKategori == 100 && this.areAllItemsValid();
                },
                
                addKategori() {
                    this.kategoris.push({ nama: '', bobot: '', deskripsi: '', kode: '', expanded: true, 
                        items: [{ nama: '', persentase: '', deskripsi: '', expanded: true, subItems: [] }] 
                    });
                },
                
                removeKategori(index) {
                    if (this.kategoris.length > 1) this.kategoris.splice(index, 1);
                },
                
                addItem(kategoriIndex) {
                    this.kategoris[kategoriIndex].items.push({ nama: '', persentase: '', deskripsi: '', expanded: true, subItems: [] });
                },
                
                removeItem(kategoriIndex, itemIndex) {
                    if (this.kategoris[kategoriIndex].items.length > 1) {
                        this.kategoris[kategoriIndex].items.splice(itemIndex, 1);
                    }
                },
                
                addSubItem(kategoriIndex, itemIndex) {
                    if (!this.kategoris[kategoriIndex].items[itemIndex].subItems) {
                        this.kategoris[kategoriIndex].items[itemIndex].subItems = [];
                    }
                    this.kategoris[kategoriIndex].items[itemIndex].subItems.push({ nama: '', persentase: '', deskripsi: '', expanded: true, subItems: [] });
                    this.kategoris[kategoriIndex].items[itemIndex].expanded = true;
                },
                
                removeSubItem(kategoriIndex, itemIndex, subIndex) {
                    this.kategoris[kategoriIndex].items[itemIndex].subItems.splice(subIndex, 1);
                },
                
                addSubSubItem(kategoriIndex, itemIndex, subIndex) {
                    if (!this.kategoris[kategoriIndex].items[itemIndex].subItems[subIndex].subItems) {
                        this.kategoris[kategoriIndex].items[itemIndex].subItems[subIndex].subItems = [];
                    }
                    this.kategoris[kategoriIndex].items[itemIndex].subItems[subIndex].subItems.push({ nama: '', persentase: '', deskripsi: '' });
                    this.kategoris[kategoriIndex].items[itemIndex].subItems[subIndex].expanded = true;
                },
                
                removeSubSubItem(kategoriIndex, itemIndex, subIndex, ssIndex) {
                    this.kategoris[kategoriIndex].items[itemIndex].subItems[subIndex].subItems.splice(ssIndex, 1);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
