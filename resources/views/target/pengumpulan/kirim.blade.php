<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('targets.submissions.index') }}" 
               class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
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
                                @if($target->grace_period_minutes > 0)
                                <span class="text-blue-600 text-xs">(+{{ $target->grace_period_minutes }} menit grace)</span>
                                @endif
                                @if($target->isInGracePeriod())
                                <span class="text-orange-600 font-medium">(Grace Period!)</span>
                                @elseif($target->isPastFinalDeadline())
                                <span class="text-red-600 font-medium">(Sudah Ditutup)</span>
                                @elseif($target->isOverdue())
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

            <!-- Deadline Warning -->
            @if($target->deadline)
                @if($target->isInGracePeriod())
                <div class="bg-orange-50 border-l-4 border-orange-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-orange-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-orange-700">
                                <strong>Masa Tenggang (Grace Period)!</strong><br>
                                Deadline sudah lewat, tapi Anda masih bisa submit sampai 
                                <strong>{{ $target->getFinalDeadline()->format('d/m/Y H:i') }}</strong>
                                ({{ $target->grace_period_minutes }} menit grace period).
                                Submit akan tetap dihitung sebagai <strong>"Terlambat"</strong>.
                            </p>
                        </div>
                    </div>
                </div>
                @elseif($target->isOverdue() && !$target->isPastFinalDeadline())
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Perhatian!</strong><br>
                                Deadline sudah lewat. Submission ini akan ditandai sebagai <strong>"Terlambat"</strong> 
                                dan akan mempengaruhi skor Ketepatan Waktu Anda.
                            </p>
                        </div>
                    </div>
                </div>
                @endif
            @endif

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
                                    <div class="p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-primary-500 peer-checked:bg-primary-50">
                                        <div class="text-center">
                                            <i class="fas fa-file-upload text-2xl mb-2 text-gray-400 peer-checked:text-primary-500"></i>
                                            <div class="font-medium text-gray-900 peer-checked:text-primary-700">Upload File</div>
                                            <div class="text-sm text-gray-500">Upload bukti/tugas</div>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="relative">
                                    <input type="radio" name="submission_type" value="checklist" 
                                           class="sr-only peer"
                                           onchange="toggleSubmissionType('checklist')">
                                    <div class="p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-primary-500 peer-checked:bg-primary-50">
                                        <div class="text-center">
                                            <i class="fas fa-check-circle text-2xl mb-2 text-gray-400 peer-checked:text-primary-500"></i>
                                            <div class="font-medium text-gray-900 peer-checked:text-primary-700">Tanpa File</div>
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
                            <div id="drop-zone" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary-400 transition-colors">
                                <input type="file" name="evidence[]" id="evidence" multiple
                                       accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                                       class="hidden"
                                       onchange="handleFileSelect(this)">
                                <label for="evidence" class="cursor-pointer block">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                    <div class="text-sm text-gray-600">
                                        <span class="font-medium text-primary-600 hover:text-primary-500">Klik untuk upload</span>
                                        atau drag & drop file di sini
                                    </div>
                                    <div class="text-xs text-gray-500 mt-2">
                                        JPG, PNG, PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX (Max 10MB per file)
                                    </div>
                                </label>
                            </div>
                            <div id="file-list" class="mt-3"></div>
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
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500">{{ old('submission_notes') }}</textarea>
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
        // Store all selected files
        let selectedFiles = [];

        function toggleSubmissionType(type) {
            const fileSection = document.getElementById('file-upload-section');
            const checklistSection = document.getElementById('checklist-section');
            
            if (type === 'file') {
                fileSection.classList.remove('hidden');
                checklistSection.classList.add('hidden');
            } else {
                fileSection.classList.add('hidden');
                checklistSection.classList.remove('hidden');
            }
        }

        function getFileIcon(filename) {
            const ext = filename.split('.').pop().toLowerCase();
            let iconClass = 'fa-file';
            let iconColor = 'text-gray-500';
            
            if (['pdf'].includes(ext)) {
                iconClass = 'fa-file-pdf';
                iconColor = 'text-red-600';
            } else if (['doc', 'docx'].includes(ext)) {
                iconClass = 'fa-file-word';
                iconColor = 'text-blue-600';
            } else if (['xls', 'xlsx'].includes(ext)) {
                iconClass = 'fa-file-excel';
                iconColor = 'text-green-600';
            } else if (['ppt', 'pptx'].includes(ext)) {
                iconClass = 'fa-file-powerpoint';
                iconColor = 'text-orange-600';
            } else if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
                iconClass = 'fa-file-image';
                iconColor = 'text-purple-600';
            }
            
            return { iconClass, iconColor };
        }

        function handleFileSelect(input) {
            // Add new files to selectedFiles array (avoid duplicates by name)
            Array.from(input.files).forEach(file => {
                const exists = selectedFiles.some(f => f.name === file.name && f.size === file.size);
                if (!exists) {
                    selectedFiles.push(file);
                }
            });
            
            updateFileList();
            updateFileInput();
        }

        function updateFileList() {
            const fileList = document.getElementById('file-list');
            fileList.innerHTML = '';
            
            if (selectedFiles.length > 0) {
                fileList.innerHTML = `<div class="text-sm font-medium text-gray-700 mb-2">File yang dipilih: (${selectedFiles.length} file)</div>`;
                
                selectedFiles.forEach((file, index) => {
                    const { iconClass, iconColor } = getFileIcon(file.name);
                    
                    const fileItem = document.createElement('div');
                    fileItem.className = 'flex items-center justify-between bg-gray-50 rounded-lg p-3 mb-2 border border-gray-200 hover:border-primary-300 transition-colors';
                    fileItem.innerHTML = `
                        <div class="flex items-center min-w-0 flex-1">
                            <i class="fas ${iconClass} ${iconColor} mr-3 text-lg flex-shrink-0"></i>
                            <div class="min-w-0 flex-1">
                                <div class="text-sm text-gray-700 font-medium truncate">${file.name}</div>
                                <div class="text-xs text-gray-500">${(file.size / 1024 / 1024).toFixed(2)} MB</div>
                            </div>
                        </div>
                        <button type="button" onclick="removeFile(${index})" class="ml-3 text-red-500 hover:text-red-700 transition-colors flex-shrink-0">
                            <i class="fas fa-times-circle text-lg"></i>
                        </button>
                    `;
                    fileList.appendChild(fileItem);
                });
            }
        }

        function updateFileInput() {
            const input = document.getElementById('evidence');
            const dt = new DataTransfer();
            
            selectedFiles.forEach(file => {
                dt.items.add(file);
            });
            
            input.files = dt.files;
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            updateFileList();
            updateFileInput();
        }

        function clearAllFiles() {
            selectedFiles = [];
            updateFileList();
            updateFileInput();
        }

        // Drag and Drop functionality
        function setupDragAndDrop() {
            const dropZone = document.getElementById('drop-zone');
            const fileInput = document.getElementById('evidence');
            
            if (!dropZone || !fileInput) return;
            
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
                document.body.addEventListener(eventName, preventDefaults, false);
            });
            
            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, highlight, false);
            });
            
            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, unhighlight, false);
            });
            
            dropZone.addEventListener('drop', handleDrop, false);
            
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            function highlight(e) {
                dropZone.classList.add('border-primary-500', 'bg-primary-50');
                dropZone.classList.remove('border-gray-300');
            }
            
            function unhighlight(e) {
                dropZone.classList.remove('border-primary-500', 'bg-primary-50');
                dropZone.classList.add('border-gray-300');
            }
            
            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                
                // Add dropped files to selectedFiles
                Array.from(files).forEach(file => {
                    const exists = selectedFiles.some(f => f.name === file.name && f.size === file.size);
                    if (!exists) {
                        selectedFiles.push(file);
                    }
                });
                
                updateFileList();
                updateFileInput();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleSubmissionType('file');
            setupDragAndDrop();
        });
    </script>
</x-app-layout>
