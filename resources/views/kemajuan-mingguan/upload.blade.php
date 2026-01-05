<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">UPLOAD KEMAJUAN MINGGUAN</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">Upload kemajuan progress mingguan kelompok Anda</p>
                </div>
                <a href="{{ route('targets.submissions.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-bold rounded-lg shadow transition-all">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>

            @if(session('error'))
                <div class="bg-red-100 border-l-8 border-red-600 text-red-800 px-6 py-4 rounded-lg shadow-md mb-8">
                    <i class="fa-solid fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if(session('ok'))
                <div class="bg-emerald-100 border-l-8 border-emerald-600 text-emerald-800 px-6 py-4 rounded-lg shadow-md mb-8">
                    <i class="fa-solid fa-check-circle mr-2"></i>
                    {{ session('ok') }}
                </div>
            @endif

            <!-- Group Info -->
            <div class="bg-white rounded-xl shadow-md border-l-4 border-indigo-600 p-6 mb-8">
                <div class="flex items-center">
                    <div class="h-12 w-12 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold mr-4">
                        <i class="fa-solid fa-users text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $group->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $group->classRoom->name ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Upload -->
            @if($activeTargets->count() > 0)
                <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
                    <form action="{{ route('weekly-progress.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                        @csrf
                        
                        <div class="space-y-6">
                            <!-- Pilih Target -->
                            <div>
                                <label for="target_id" class="block text-sm font-bold text-gray-700 mb-2">
                                    Pilih Target Mingguan <span class="text-red-500">*</span>
                                </label>
                                <select name="target_id" id="target_id" 
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium" required>
                                    <option value="">Pilih target...</option>
                                    @foreach($activeTargets as $target)
                                        <option value="{{ $target->id }}">
                                            Minggu {{ $target->minggu_ke }} - {{ $target->judul ?? 'Target Mingguan' }}
                                            @if($target->deadline)
                                                (Deadline: {{ $target->deadline->format('d M Y H:i') }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Upload Files -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    Upload File (Max 10MB per file)
                                </label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-indigo-500 transition-colors">
                                    <input type="file" name="files[]" multiple 
                                           class="w-full" 
                                           accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar,.jpg,.jpeg,.png">
                                    <p class="text-sm text-gray-500 mt-2">
                                        <i class="fa-solid fa-cloud-upload-alt mr-1"></i>
                                        Drag & drop atau klik untuk memilih file
                                    </p>
                                </div>
                            </div>

                            <!-- Catatan -->
                            <div>
                                <label for="catatan" class="block text-sm font-bold text-gray-700 mb-2">
                                    Catatan (Opsional)
                                </label>
                                <textarea name="catatan" id="catatan" rows="4"
                                          class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                          placeholder="Tambahkan catatan atau keterangan..."></textarea>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="mt-8 flex justify-end gap-3">
                            <a href="{{ route('targets.submissions.index') }}" 
                               class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition-all">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow-lg transition-all">
                                <i class="fa-solid fa-upload mr-2"></i> Upload Kemajuan
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-white rounded-xl shadow-md p-12 text-center">
                    <div class="inline-block p-6 rounded-full bg-gray-100 mb-4">
                        <i class="fa-solid fa-calendar-xmark text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700">Tidak Ada Target Aktif</h3>
                    <p class="text-gray-500 mt-2">Belum ada target mingguan yang aktif untuk kelompok Anda.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
