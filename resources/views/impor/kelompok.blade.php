<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('classrooms.index') }}" 
               class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Import Kelompok dari Excel') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
            @endif

            @if(session('warning'))
            <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('warning') }}
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-8">
                            <form action="{{ route('import.groups.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <!-- Pilih Kelas -->
                                <div class="mb-6">
                                    <label for="class_room_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Pilih Kelas Tujuan <span class="text-red-500">*</span>
                                    </label>
                                    <select name="class_room_id" 
                                            id="class_room_id" 
                                            required
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500">
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($classRooms as $classRoom)
                                        <option value="{{ $classRoom->id }}">
                                            {{ $classRoom->name }} - {{ $classRoom->program_studi }} (Semester {{ $classRoom->semester }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Upload File - MULTIPLE FILES SUPPORT -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        File Excel <span class="text-red-500">*</span>
                                        <span class="text-xs text-gray-500 font-normal ml-2">(Bisa upload banyak file sekaligus, max 10 files)</span>
                                    </label>
                                    
                                    <div class="flex items-center justify-center w-full">
                                        <label for="file-upload" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6" id="upload-placeholder">
                                                <i class="fas fa-cloud-upload-alt text-gray-400 text-5xl mb-3"></i>
                                                <p class="mb-2 text-sm text-gray-500">
                                                    <span class="font-semibold">Klik untuk upload</span> atau drag & drop
                                                </p>
                                                <p class="text-xs text-gray-500">Excel (.xlsx, .xls) atau CSV</p>
                                                <p class="text-xs text-emerald-600 font-medium mt-2">
                                                    <i class="fas fa-info-circle"></i>
                                                    Bisa pilih banyak file sekaligus (max 10 files × 5MB)
                                                </p>
                                            </div>
                                            <input id="file-upload" 
                                                   name="files[]" 
                                                   type="file" 
                                                   class="hidden" 
                                                   accept=".xlsx,.xls,.csv"
                                                   required
                                                   multiple
                                                   onchange="handleMultipleFilesSelect(this)">
                                        </label>
                                    </div>

                                    <!-- Selected Files List -->
                                    <div id="files-list" class="hidden mt-4 space-y-3">
                                        <div class="flex items-center justify-between mb-2">
                                            <p class="text-sm font-semibold text-gray-700">
                                                <i class="fas fa-list mr-2"></i>
                                                File yang dipilih: <span id="files-count" class="text-emerald-600">0</span>
                                            </p>
                                            <button type="button" onclick="clearFiles()" class="text-xs text-red-600 hover:text-red-800 font-medium">
                                                <i class="fas fa-trash mr-1"></i>Hapus Semua
                                            </button>
                                        </div>
                                        <div id="files-container" class="max-h-48 overflow-y-auto space-y-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                            <!-- Files will be listed here -->
                                        </div>
                                        
                                        <!-- Add More Files Button -->
                                        <div class="flex items-center justify-center">
                                            <label for="add-more-files" class="inline-flex items-center px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 font-medium rounded-lg border-2 border-dashed border-gray-300 hover:border-emerald-400 cursor-pointer transition-all duration-200">
                                                <i class="fas fa-plus-circle text-emerald-600 mr-2"></i>
                                                Tambah File Lagi
                                                <span class="ml-2 text-xs text-gray-500">(Max 10 files)</span>
                                            </label>
                                            <input id="add-more-files" 
                                                   type="file" 
                                                   class="hidden" 
                                                   accept=".xlsx,.xls,.csv"
                                                   multiple
                                                   onchange="addMoreFiles(this)">
                                        </div>
                                    </div>

                                    @error('files')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    @error('files.*')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Action Buttons: UX Laws Applied -->
                                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                                    <!-- Secondary Action (Batal) - Low Visual Weight -->
                                    <a href="{{ route('classrooms.index') }}" 
                                       class="inline-flex items-center justify-center min-w-[120px] px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 font-medium rounded-lg border-2 border-gray-300 hover:border-gray-400 transition-all duration-200 shadow-sm hover:shadow">
                                        <i class="fas fa-times mr-2"></i>
                                        Batal
                                    </a>
                                    
                                    <!-- Primary Action (Import) - High Visual Weight -->
                                    <button type="submit" 
                                            class="inline-flex items-center justify-center min-w-[180px] px-8 py-3.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 active:from-emerald-700 active:to-emerald-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
                                        <i class="fas fa-file-import mr-2 text-lg"></i>
                                        Import Data
                                    </button>
                                </div>
                                
                                <!-- Visual Hierarchy Hint -->
                                <div class="mt-3 text-right">
                                    <p class="text-xs text-gray-500 italic">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Pastikan file Excel sudah sesuai template
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Download Template -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">
                                <i class="fas fa-download text-primary-600 mr-2"></i>
                                Download Template
                            </h3>
                            <p class="text-sm text-gray-600 mb-4">
                                Download template Excel untuk import kelompok
                            </p>
                            <a href="{{ route('import.groups.template') }}" 
                               class="block w-full text-center bg-primary-500 hover:bg-primary-600 text-white py-2 px-4 rounded-lg">
                                <i class="fas fa-download mr-2"></i>Download Template
                            </a>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">
                                <i class="fas fa-question-circle text-secondary-600 mr-2"></i>
                                Format Excel
                            </h3>
                            <div class="text-sm text-gray-600 space-y-2">
                                <p class="font-medium">Kolom yang diperlukan:</p>
                                <ol class="list-decimal list-inside space-y-1 text-xs">
                                    <li><strong>nama_kelompok</strong> (wajib)</li>
                                    <li><strong>ketua_nim_atau_email</strong> (wajib)</li>
                                    <li>anggota_1_nim_atau_email</li>
                                    <li>anggota_2_nim_atau_email</li>
                                    <li>anggota_3_nim_atau_email</li>
                                    <li>anggota_4_nim_atau_email</li>
                                </ol>
                                <p class="text-xs text-gray-500 mt-2">
                                    ℹ️ Bisa gunakan <strong>NIM</strong> atau <strong>Email</strong>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Important Notes -->
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-semibold text-yellow-800 mb-2">Penting!</h4>
                                <ul class="text-xs text-yellow-700 space-y-1">
                                    <li>• Mahasiswa harus dari kelas yang dipilih</li>
                                    <li>• Mahasiswa belum boleh punya kelompok</li>
                                    <li>• Bisa pakai NIM atau Email mahasiswa</li>
                                    <li>• Max 5 anggota (1 ketua + 4 anggota)</li>
                                    <li>• Nama kelompok harus unik</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let selectedFiles = [];

        function handleMultipleFilesSelect(input) {
            const placeholder = document.getElementById('upload-placeholder');
            const filesList = document.getElementById('files-list');
            const filesCount = document.getElementById('files-count');
            
            if (input.files && input.files.length > 0) {
                selectedFiles = Array.from(input.files);
                
                // Hide placeholder, show files list
                placeholder.classList.add('hidden');
                filesList.classList.remove('hidden');
                
                // Update count
                filesCount.textContent = selectedFiles.length;
                
                // Display files
                displayFiles();
            }
        }

        function addMoreFiles(input) {
            if (input.files && input.files.length > 0) {
                const newFiles = Array.from(input.files);
                
                // Add new files to existing selection
                newFiles.forEach(file => {
                    // Check if file already exists
                    const isDuplicate = selectedFiles.some(existing => 
                        existing.name === file.name && existing.size === file.size
                    );
                    
                    if (!isDuplicate && selectedFiles.length < 10) {
                        selectedFiles.push(file);
                    }
                });
                
                // Update the main file input
                updateMainFileInput();
                
                // Update display
                document.getElementById('files-count').textContent = selectedFiles.length;
                displayFiles();
                
                // Clear the add-more input
                input.value = '';
                
                // Show alert if reached max
                if (selectedFiles.length >= 10) {
                    alert('⚠️ Maksimal 10 file sudah tercapai!');
                }
            }
        }

        function updateMainFileInput() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });
            document.getElementById('file-upload').files = dataTransfer.files;
        }

        function displayFiles() {
            const filesContainer = document.getElementById('files-container');
            filesContainer.innerHTML = '';
            
            selectedFiles.forEach((file, index) => {
                const sizeInMB = (file.size / 1024 / 1024).toFixed(2);
                const sizeColor = file.size > 5 * 1024 * 1024 ? 'text-red-600' : 'text-gray-600';
                const fileNumber = index + 1;
                
                const fileElement = document.createElement('div');
                fileElement.className = 'flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200 hover:border-emerald-300 transition-colors';
                fileElement.innerHTML = `
                    <div class="flex items-center gap-3 flex-1">
                        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                            ${fileNumber}
                        </span>
                        <i class="fas fa-file-excel text-emerald-600 text-2xl"></i>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate" title="${file.name}">${file.name}</p>
                            <p class="text-xs ${sizeColor}">${sizeInMB} MB</p>
                        </div>
                    </div>
                    <button type="button" 
                            onclick="removeFile(${index})" 
                            class="ml-2 px-2 py-1 text-red-500 hover:text-white hover:bg-red-500 rounded transition-colors">
                        <i class="fas fa-times-circle text-lg"></i>
                    </button>
                `;
                filesContainer.appendChild(fileElement);
            });
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            
            // Update main file input
            updateMainFileInput();
            
            // Update display
            if (selectedFiles.length === 0) {
                clearFiles();
            } else {
                document.getElementById('files-count').textContent = selectedFiles.length;
                displayFiles();
            }
        }

        function clearFiles() {
            selectedFiles = [];
            document.getElementById('file-upload').value = '';
            document.getElementById('upload-placeholder').classList.remove('hidden');
            document.getElementById('files-list').classList.add('hidden');
        }

        // Drag & Drop support
        const dropZone = document.querySelector('[for="file-upload"]');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.add('border-emerald-500', 'bg-emerald-50');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.remove('border-emerald-500', 'bg-emerald-50');
            }, false);
        });

        dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            document.getElementById('file-upload').files = files;
            handleMultipleFilesSelect(document.getElementById('file-upload'));
        }, false);
    </script>
    @endpush
</x-app-layout>
