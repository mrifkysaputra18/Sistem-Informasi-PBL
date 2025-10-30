<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-white leading-tight flex items-center gap-2">
                    <i class="fas fa-upload"></i>
                    {{ __('Upload Progress Mingguan') }}
                </h2>
                <p class="text-primary-100 text-sm mt-1">
                    Minggu {{ $weekNumber }} - {{ $target->title ?? 'Target Mingguan' }}
                </p>
            </div>
            <a href="{{ route('mahasiswa.dashboard') }}" 
               class="inline-flex items-center px-4 py-2 bg-white text-primary-600 rounded-lg hover:bg-primary-50 transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Info Card -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl p-5 mb-6 shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-blue-500 flex items-center justify-center shadow-lg">
                        <i class="fas fa-info-circle text-white text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Informasi Upload Progress</h3>
                        <div class="space-y-1 text-sm text-gray-700">
                            <p class="flex items-center gap-2">
                                <i class="fas fa-check-circle text-green-600"></i>
                                Upload file dokumentasi progress (opsional)
                            </p>
                            <p class="flex items-center gap-2">
                                <i class="fas fa-check-circle text-green-600"></i>
                                Bisa upload multiple files (maksimal 5 file)
                            </p>
                            <p class="flex items-center gap-2">
                                <i class="fas fa-check-circle text-green-600"></i>
                                Format: PDF, Word, Excel, Gambar (max 5MB per file)
                            </p>
                            <p class="flex items-center gap-2">
                                <i class="fas fa-check-circle text-green-600"></i>
                                Bisa juga hanya centang "Selesai" tanpa upload file
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload Form -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
                <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fas fa-clipboard-list"></i>
                        Form Upload Progress
                    </h3>
                </div>

                <form action="{{ route('weekly-progress.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf

                    <input type="hidden" name="group_id" value="{{ $group->id }}">
                    <input type="hidden" name="week_number" value="{{ $weekNumber }}">
                    <input type="hidden" name="target_id" value="{{ $target->id ?? '' }}">

                    <!-- Title (auto-filled) -->
                    <div>
                        <label for="title" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                            <i class="fas fa-heading text-indigo-500"></i>
                            Judul Progress
                        </label>
                        <input type="text" 
                               name="title" 
                               id="title" 
                               value="{{ old('title', $target->title ?? 'Progress Minggu ' . $weekNumber) }}"
                               required
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all font-medium">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                            <i class="fas fa-align-left text-indigo-500"></i>
                            Deskripsi Progress
                            <span class="text-xs text-gray-500 font-normal">(Opsional)</span>
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4" 
                                  placeholder="Jelaskan apa yang sudah dikerjakan minggu ini..."
                                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 flex items-center gap-1">
                            <i class="fas fa-lightbulb"></i>
                            Tips: Jelaskan aktivitas, hasil yang dicapai, atau kendala yang dihadapi
                        </p>
                    </div>

                    <!-- File Upload Section -->
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 bg-gray-50 hover:bg-gray-100 transition-colors">
                        <label class="block text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-file-upload text-indigo-500"></i>
                            Upload File Dokumentasi
                            <span class="text-xs text-gray-500 font-normal">(Opsional, max 5 files)</span>
                        </label>
                        
                        <div class="space-y-3">
                            <!-- File Input -->
                            <input type="file" 
                                   name="evidence[]" 
                                   id="evidence" 
                                   multiple
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.zip,.rar"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all bg-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-600 file:text-white hover:file:bg-primary-700 cursor-pointer"
                                   onchange="previewFiles(this)">
                            
                            <!-- File Preview -->
                            <div id="filePreview" class="hidden mt-3">
                                <p class="text-sm font-semibold text-gray-700 mb-2">File yang dipilih:</p>
                                <div id="fileList" class="space-y-2"></div>
                            </div>

                            @error('evidence.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mt-3">
                                <p class="text-xs text-blue-800 flex items-start gap-2">
                                    <i class="fas fa-info-circle mt-0.5"></i>
                                    <span>
                                        <strong>Format yang didukung:</strong> PDF, Word, Excel, PowerPoint, Gambar (JPG, PNG), ZIP, RAR<br>
                                        <strong>Ukuran maksimal:</strong> 5MB per file
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Checkbox: Selesai tanpa upload -->
                    <div class="bg-yellow-50 border-2 border-yellow-200 rounded-xl p-5">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" 
                                   name="is_checked_only" 
                                   id="is_checked_only" 
                                   value="1"
                                   class="mt-1 w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
                                   onchange="toggleFileRequirement(this)">
                            <div class="flex-1">
                                <span class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                    <i class="fas fa-check-square text-yellow-600"></i>
                                    Target sudah selesai (tanpa upload file)
                                </span>
                                <p class="text-xs text-gray-600 mt-1">
                                    Centang ini jika target sudah selesai tapi tidak ada file dokumentasi yang perlu diupload
                                </p>
                            </div>
                        </label>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-4 border-t-2 border-gray-200">
                        <a href="{{ route('mahasiswa.dashboard') }}" 
                           class="inline-flex items-center px-5 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-semibold">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </a>
                        
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg transition font-bold shadow-lg hover:shadow-xl">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Submit Progress
                        </button>
                    </div>
                </form>
            </div>

            <!-- Help Section -->
            <div class="mt-6 bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-xl p-5">
                <h4 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <i class="fas fa-question-circle text-purple-600"></i>
                    Butuh Bantuan?
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-700">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-arrow-right text-purple-600 mt-1"></i>
                        <span>File dokumentasi bersifat opsional, tidak wajib</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <i class="fas fa-arrow-right text-purple-600 mt-1"></i>
                        <span>Bisa upload lebih dari 1 file sekaligus</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <i class="fas fa-arrow-right text-purple-600 mt-1"></i>
                        <span>Progress akan direview oleh dosen</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <i class="fas fa-arrow-right text-purple-600 mt-1"></i>
                        <span>Pastikan file tidak corrupt/rusak</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        // Preview selected files
        function previewFiles(input) {
            const filePreview = document.getElementById('filePreview');
            const fileList = document.getElementById('fileList');
            
            if (input.files && input.files.length > 0) {
                filePreview.classList.remove('hidden');
                fileList.innerHTML = '';
                
                Array.from(input.files).forEach((file, index) => {
                    const fileSize = (file.size / 1024 / 1024).toFixed(2); // Convert to MB
                    const fileItem = document.createElement('div');
                    fileItem.className = 'flex items-center justify-between bg-white p-3 rounded-lg border border-gray-200';
                    fileItem.innerHTML = `
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center">
                                <i class="fas fa-file text-primary-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">${file.name}</p>
                                <p class="text-xs text-gray-500">${fileSize} MB</p>
                            </div>
                        </div>
                        <span class="text-xs font-medium ${fileSize > 5 ? 'text-red-600' : 'text-green-600'}">
                            ${fileSize > 5 ? '⚠️ Terlalu besar' : '✓ Valid'}
                        </span>
                    `;
                    fileList.appendChild(fileItem);
                });
            } else {
                filePreview.classList.add('hidden');
            }
        }

        // Toggle file requirement based on checkbox
        function toggleFileRequirement(checkbox) {
            const fileInput = document.getElementById('evidence');
            if (checkbox.checked) {
                fileInput.removeAttribute('required');
                fileInput.disabled = false; // Still allow upload if user wants
            } else {
                fileInput.disabled = false;
            }
        }

        // Prevent form submission if file size exceeds limit
        document.querySelector('form').addEventListener('submit', function(e) {
            const fileInput = document.getElementById('evidence');
            const isCheckedOnly = document.getElementById('is_checked_only').checked;
            
            if (fileInput.files.length > 0) {
                for (let file of fileInput.files) {
                    if (file.size > 5 * 1024 * 1024) { // 5MB
                        e.preventDefault();
                        alert(`File "${file.name}" melebihi batas maksimal 5MB. Silakan pilih file yang lebih kecil.`);
                        return false;
                    }
                }
            }

            if (!isCheckedOnly && fileInput.files.length === 0) {
                const confirm = window.confirm('Anda belum upload file. Apakah yakin ingin melanjutkan tanpa file dokumentasi?');
                if (!confirm) {
                    e.preventDefault();
                    return false;
                }
            }
        });
    </script>
    @endpush
</x-app-layout>

