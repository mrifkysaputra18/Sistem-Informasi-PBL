<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Import Mahasiswa dari Excel') }}
            </h2>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white font-semibold rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Success Message -->
            @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            <!-- Warning Message -->
            @if(session('warning'))
            <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-xl mr-3"></i>
                    <p class="text-yellow-800 font-medium">{{ session('warning') }}</p>
                </div>
            </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-times-circle text-red-500 text-xl mr-3"></i>
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            <!-- File Results (Multiple Files) -->
            @if(session('file_results'))
            <div class="mb-6 bg-white border-2 border-gray-200 rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-3">
                    <h3 class="text-white font-semibold flex items-center">
                        <i class="fas fa-list-check mr-2"></i>
                        Hasil Import Per File ({{ count(session('file_results')) }} files)
                    </h3>
                </div>
                <div class="p-4 space-y-3 max-h-96 overflow-y-auto">
                    @foreach(session('file_results') as $index => $result)
                    <div class="border rounded-lg p-4 
                        {{ $result['status'] === 'success' ? 'bg-green-50 border-green-200' : '' }}
                        {{ $result['status'] === 'warning' ? 'bg-yellow-50 border-yellow-200' : '' }}
                        {{ $result['status'] === 'error' ? 'bg-red-50 border-red-200' : '' }}">
                        
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <span class="flex items-center justify-center w-6 h-6 rounded-full 
                                    {{ $result['status'] === 'success' ? 'bg-green-500' : '' }}
                                    {{ $result['status'] === 'warning' ? 'bg-yellow-500' : '' }}
                                    {{ $result['status'] === 'error' ? 'bg-red-500' : '' }}
                                    text-white text-xs font-bold">{{ $index + 1 }}</span>
                                <i class="fas fa-file-excel text-emerald-600 text-lg"></i>
                                <p class="font-medium text-gray-800">{{ $result['filename'] }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $result['status'] === 'success' ? 'bg-green-200 text-green-800' : '' }}
                                {{ $result['status'] === 'warning' ? 'bg-yellow-200 text-yellow-800' : '' }}
                                {{ $result['status'] === 'error' ? 'bg-red-200 text-red-800' : '' }}">
                                {{ $result['status'] === 'success' ? '✅ Sukses' : '' }}
                                {{ $result['status'] === 'warning' ? '⚠️ Warning' : '' }}
                                {{ $result['status'] === 'error' ? '❌ Error' : '' }}
                            </span>
                        </div>
                        
                        <div class="ml-8 mt-2 grid grid-cols-2 gap-3">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-check-circle text-green-600"></i>
                                <span class="text-sm text-gray-700">
                                    <strong>Berhasil:</strong> {{ $result['imported'] }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                                <span class="text-sm text-gray-700">
                                    <strong>Dilewati:</strong> {{ $result['skipped'] }}
                                </span>
                            </div>
                        </div>
                        
                        @if(!empty($result['errors']))
                        <div class="ml-8 mt-3 bg-white rounded p-2 border border-red-200">
                            <p class="text-xs font-semibold text-red-700 mb-1">Error Detail:</p>
                            <div class="max-h-24 overflow-y-auto space-y-1">
                                @foreach($result['errors'] as $error)
                                <p class="text-xs text-red-600">• {{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Import Errors (Legacy) -->
            @if(session('import_errors') && !session('file_results'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3 mt-1"></i>
                    <div class="flex-1">
                        <p class="text-red-800 font-semibold mb-2">Detail Error Import:</p>
                        <div class="max-h-48 overflow-y-auto bg-white rounded p-3 space-y-1">
                            @foreach(session('import_errors') as $error)
                            <p class="text-sm text-red-700">• {{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Main Card -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="p-8">
                    
                    <!-- Header Icon -->
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full mb-4">
                            <i class="fas fa-file-excel text-white text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">Import Data Mahasiswa</h3>
                        <p class="text-gray-600 mt-2">Upload file Excel untuk menambahkan banyak mahasiswa sekaligus</p>
                    </div>

                    <!-- Download Template Section -->
                    <div class="mb-8 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border-2 border-blue-200">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <i class="fas fa-download text-blue-600 text-3xl"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-800 mb-2">
                                    Langkah 1: Download Template
                                </h4>
                                <p class="text-sm text-gray-700 mb-4">
                                    Download template Excel terlebih dahulu, isi data mahasiswa sesuai format, lalu upload kembali.
                                </p>
                                <a href="{{ route('users.download-template') }}" 
                                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                                    <i class="fas fa-download mr-2"></i>
                                    Download Template Excel
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Form Section -->
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-upload text-green-600 mr-2"></i>
                            Langkah 2: Upload File Excel
                        </h4>
                        
                        <form method="POST" action="{{ route('users.import') }}" enctype="multipart/form-data" id="importForm">
                            @csrf
                            
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Pilih File Excel <span class="text-red-500">*</span>
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

                            <!-- Buttons: UX Laws Applied -->
                            <div class="flex items-center justify-end gap-4 pt-4">
                                <!-- Secondary Action (Batal) - Low Visual Weight -->
                                <a href="{{ route('admin.users.index') }}" 
                                   class="inline-flex items-center justify-center min-w-[120px] px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 font-medium rounded-lg border-2 border-gray-300 hover:border-gray-400 transition-all duration-200 shadow-sm hover:shadow">
                                    <i class="fas fa-times mr-2"></i>
                                    Batal
                                </a>
                                
                                <!-- Primary Action (Import) - High Visual Weight -->
                                <button type="submit" 
                                        id="submit-btn"
                                        class="inline-flex items-center justify-center min-w-[180px] px-8 py-3.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 active:from-emerald-700 active:to-emerald-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                                    <i class="fas fa-file-import mr-2 text-lg"></i>
                                    <span id="submit-text">Import Data</span>
                                    <i class="fas fa-spinner fa-spin ml-2 hidden" id="loading-icon"></i>
                                </button>
                            </div>
                            
                            <!-- Visual Hierarchy Hint -->
                            <div class="mt-3 text-right">
                                <p class="text-xs text-gray-500 italic">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Pastikan file sudah sesuai template
                                </p>
                            </div>
                        </form>
                    </div>

                    <!-- Information Section -->
                    <div class="p-6 bg-yellow-50 rounded-lg border border-yellow-200">
                        <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-info-circle text-yellow-600 mr-2"></i>
                            Informasi Penting
                        </h4>
                        <ul class="list-disc list-inside text-sm text-gray-700 space-y-2">
                            <li><strong>Email SSO:</strong> Harus menggunakan domain <code class="bg-yellow-100 px-1 rounded">@mhs.politala.ac.id</code></li>
                            <li><strong>Kelas:</strong> Kode kelas harus sudah terdaftar di sistem (contoh: TI-3A, TI-3B)</li>
                            <li><strong>Sinkronisasi:</strong> Mahasiswa akan otomatis terdaftar di kelas yang dipilih</li>
                            <li><strong>Login SSO:</strong> Mahasiswa langsung bisa login menggunakan akun SSO Politala</li>
                            <li><strong>Data Duplikat:</strong> NIM dan Email tidak boleh duplikat dengan data yang sudah ada</li>
                            <li><strong>Password:</strong> Tidak perlu diisi karena menggunakan SSO (Single Sign-On)</li>
                        </ul>
                    </div>

                </div>
            </div>

            <!-- Statistics (if available) -->
            @if(session('last_import_stats'))
            <div class="mt-6 bg-white overflow-hidden shadow-lg rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Statistik Import Terakhir</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                        <p class="text-sm text-gray-600">Berhasil</p>
                        <p class="text-2xl font-bold text-green-600">{{ session('last_import_stats')['success'] ?? 0 }}</p>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                        <p class="text-sm text-gray-600">Dilewati</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ session('last_import_stats')['skipped'] ?? 0 }}</p>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <p class="text-sm text-gray-600">Total</p>
                        <p class="text-2xl font-bold text-blue-600">{{ session('last_import_stats')['total'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

    @push('scripts')
    <script>
        let selectedFiles = [];

        function handleMultipleFilesSelect(input) {
            const placeholder = document.getElementById('upload-placeholder');
            const filesList = document.getElementById('files-list');
            const filesContainer = document.getElementById('files-container');
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

        // Handle form submission
        document.getElementById('importForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submit-btn');
            const submitText = document.getElementById('submit-text');
            const loadingIcon = document.getElementById('loading-icon');
            
            if (selectedFiles.length === 0) {
                e.preventDefault();
                alert('⚠️ Silakan pilih minimal 1 file Excel!');
                return;
            }
            
            if (selectedFiles.length > 10) {
                e.preventDefault();
                alert('⚠️ Maksimal 10 file dapat diupload sekaligus!');
                return;
            }
            
            // Disable button and show loading
            submitBtn.disabled = true;
            submitText.textContent = `Mengimport ${selectedFiles.length} file...`;
            loadingIcon.classList.remove('hidden');
        });

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
