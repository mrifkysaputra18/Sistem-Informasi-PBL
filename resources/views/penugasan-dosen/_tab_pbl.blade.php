<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Daftar Dosen PBL per Kelas -->
    <div class="lg:col-span-2">
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="fa-solid fa-list mr-2 text-indigo-600"></i> Daftar Penugasan Dosen PBL
            </h3>
            
            @if($kelasList->count() > 0)
                <div class="space-y-4">
                    @foreach($kelasList as $kelas)
                        @php
                            $assignedDosens = $dosenPblList[$kelas->id] ?? collect();
                        @endphp
                        <div class="bg-white rounded-lg border border-gray-200 p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold mr-3">
                                        {{ strtoupper(substr($kelas->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900">{{ $kelas->name }}</h4>
                                        <p class="text-xs text-gray-500">{{ $kelas->code }}</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full">
                                    {{ $assignedDosens->count() }} dosen
                                </span>
                            </div>
                            
                            @if($assignedDosens->count() > 0)
                                <div class="space-y-2">
                                    @foreach($assignedDosens as $assignment)
                                        <div class="flex items-center justify-between bg-gray-50 rounded-lg p-2">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-800">{{ $assignment->dosen->name }}</p>
                                                <p class="text-xs text-gray-500">Dosen PBL</p>
                                            </div>
                                            <form action="{{ route('penugasan-dosen.pbl.destroy', $assignment) }}" method="POST"
                                                  id="delete-pbl-{{ $assignment->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        onclick="confirmDelete('Hapus Penugasan?', 'Dosen PBL ini akan dihapus dari kelas.<br><small class=\'text-gray-500\'>Tindakan ini tidak dapat dibatalkan.</small>', document.getElementById('delete-pbl-{{ $assignment->id }}'))"
                                                        class="text-red-500 hover:text-red-700 p-1">
                                                    <i class="fa-solid fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500 italic">Belum ada dosen PBL ditugaskan</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fa-solid fa-folder-open text-4xl mb-2"></i>
                    <p>Belum ada kelas untuk periode ini</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Form Tambah Dosen PBL -->
    <div class="lg:col-span-1">
        <div class="bg-indigo-50 rounded-lg p-4 sticky top-8">
            <h3 class="text-lg font-bold text-indigo-900 mb-4">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Dosen PBL
            </h3>
            
            <form action="{{ route('penugasan-dosen.pbl.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Pilih Kelas</label>
                    <select name="class_room_id" required
                            class="w-full h-11 bg-white border-2 border-gray-200 rounded-lg text-sm font-semibold focus:border-indigo-600 focus:ring-0">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}">{{ $kelas->name }} ({{ $kelas->code }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Pilih Dosen</label>
                    <select name="dosen_id" required
                            class="w-full h-11 bg-white border-2 border-gray-200 rounded-lg text-sm font-semibold focus:border-indigo-600 focus:ring-0">
                        <option value="">-- Pilih Dosen --</option>
                        @foreach($dosens as $dosen)
                            <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Dosen PBL tidak memiliki pembagian periode UTS seperti Dosen Matkul -->
                <!-- Dosen PBL tetap sama sepanjang semester -->
                
                <button type="submit" 
                        class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow transition-all">
                    <i class="fa-solid fa-plus mr-2"></i> Tambahkan
                </button>
            </form>
        </div>
    </div>
</div>
