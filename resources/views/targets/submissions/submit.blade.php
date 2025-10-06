<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('targets.submissions.index') }}" 
               class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Submit Target: ') . $target->title }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Target Info -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Detail Target</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-gray-600">Judul</div>
                        <div class="font-medium text-gray-900">{{ $target->title }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Minggu</div>
                        <div class="font-medium text-gray-900">Minggu {{ $target->week_number }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Deadline</div>
                        <div class="font-medium text-gray-900">
                            @if($target->deadline)
                                {{ $target->deadline->format('d/m/Y H:i') }}
                                @if($target->isOverdue())
                                <span class="text-red-600 font-medium">(Terlambat)</span>
                                @endif
                            @else
                                <span class="text-gray-400">Tidak ada deadline</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Dibuat oleh</div>
                        <div class="font-medium text-gray-900">{{ $target->creator->name ?? 'System' }}</div>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-sm text-gray-600">Deskripsi</div>
                    <div class="text-gray-900 mt-1">{{ $target->description }}</div>
                </div>
            </div>

            <!-- Submission Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Submit Target</h3>
                    
                    <form method="POST" action="{{ route('targets.submissions.store', $target->id) }}" 
                          enctype="multipart/form-data">
                        @csrf

                        <!-- Submission Type -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Cara Submit <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="relative">
                                    <input type="radio" name="submission_type" value="file" 
                                           class="sr-only peer" 
                                           onchange="toggleSubmissionType('file')" checked>
                                    <div class="p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                        <div class="text-center">
                                            <i class="fas fa-file-upload text-2xl mb-2 text-gray-400 peer-checked:text-blue-500"></i>
                                            <div class="font-medium text-gray-900 peer-checked:text-blue-700">Upload File</div>
                                            <div class="text-sm text-gray-500">Upload bukti/tugas</div>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="relative">
                                    <input type="radio" name="submission_type" value="checklist" 
                                           class="sr-only peer"
                                           onchange="toggleSubmissionType('checklist')">
                                    <div class="p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                        <div class="text-center">
                                            <i class="fas fa-check-circle text-2xl mb-2 text-gray-400 peer-checked:text-blue-500"></i>
                                            <div class="font-medium text-gray-900 peer-checked:text-blue-700">Tanpa File</div>
                                            <div class="text-sm text-gray-500">Centang selesai tanpa upload</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- File Upload -->
                        <div id="file-upload-section" class="mb-6">
                            <label for="evidence" class="block text-sm font-medium text-gray-700 mb-2">
                                Upload File Bukti/Tugas
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                                <input type="file" name="evidence[]" id="evidence" multiple
                                       accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                       class="hidden"
                                       onchange="handleFileSelect(this)">
                                <label for="evidence" class="cursor-pointer">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                    <div class="text-sm text-gray-600">
                                        <span class="font-medium text-blue-600 hover:text-blue-500">Klik untuk upload</span>
                                        atau drag & drop file
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        JPG, PNG, PDF, DOC, DOCX (Max 5MB per file)
                                    </div>
                                </label>
                            </div>
                            <div id="file-list" class="mt-2"></div>
                            @error('evidence.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Checklist Option -->
                        <div id="checklist-section" class="mb-6 hidden">
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_checked_only" value="1" 
                                           class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                                    <label class="ml-2 text-sm text-yellow-800">
                                        <strong>Saya menyatakan target ini sudah selesai dikerjakan tanpa perlu upload file</strong>
                                    </label>
                                </div>
                                <p class="text-xs text-yellow-700 mt-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Gunakan opsi ini jika target sudah selesai tapi tidak memerlukan file bukti
                                </p>
                            </div>
                        </div>

                        <!-- Submission Notes -->
                        <div class="mb-6">
                            <label for="submission_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan (Opsional)
                            </label>
                            <textarea name="submission_notes" id="submission_notes" rows="3"
                                      placeholder="Tambahkan catatan atau penjelasan tentang submission Anda..."
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('submission_notes') }}</textarea>
                            @error('submission_notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex gap-2">
                            <a href="{{ route('targets.submissions.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-6 rounded">
                                <i class="fas fa-paper-plane mr-2"></i>Submit Target
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSubmissionType(type) {
            const fileSection = document.getElementById('file-upload-section');
            const checklistSection = document.getElementById('checklist-section');
            const fileInput = document.getElementById('evidence');
            
            if (type === 'file') {
                fileSection.classList.remove('hidden');
                checklistSection.classList.add('hidden');
                fileInput.required = true;
            } else {
                fileSection.classList.add('hidden');
                checklistSection.classList.remove('hidden');
                fileInput.required = false;
            }
        }

        function handleFileSelect(input) {
            const fileList = document.getElementById('file-list');
            const files = input.files;
            
            fileList.innerHTML = '';
            
            if (files.length > 0) {
                fileList.innerHTML = '<div class="text-sm font-medium text-gray-700 mb-2">File yang dipilih:</div>';
                
                Array.from(files).forEach((file, index) => {
                    const fileItem = document.createElement('div');
                    fileItem.className = 'flex items-center justify-between bg-gray-50 rounded-lg p-2 mb-2';
                    fileItem.innerHTML = `
                        <div class="flex items-center">
                            <i class="fas fa-file mr-2 text-gray-500"></i>
                            <span class="text-sm text-gray-700">${file.name}</span>
                            <span class="text-xs text-gray-500 ml-2">(${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
                        </div>
                        <button type="button" onclick="removeFile(${index})" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    fileList.appendChild(fileItem);
                });
            }
        }

        function removeFile(index) {
            const input = document.getElementById('evidence');
            const dt = new DataTransfer();
            
            Array.from(input.files).forEach((file, i) => {
                if (i !== index) {
                    dt.items.add(file);
                }
            });
            
            input.files = dt.files;
            handleFileSelect(input);
        }

        // Set default submission type
        document.addEventListener('DOMContentLoaded', function() {
            toggleSubmissionType('file');
        });
    </script>
</x-app-layout>
