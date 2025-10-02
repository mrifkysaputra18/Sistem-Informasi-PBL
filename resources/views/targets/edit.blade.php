<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('mahasiswa.dashboard') }}" 
               class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Target Mingguan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8">
                    <form action="{{ route('weekly-targets.update', $target) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Minggu Ke -->
                        <div class="mb-6">
                            <label for="week_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Minggu Ke <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="week_number" 
                                   id="week_number" 
                                   value="{{ old('week_number', $target->week_number) }}"
                                   min="1"
                                   required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('week_number') border-red-500 @enderror">
                            @error('week_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Judul Target -->
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Target <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   value="{{ old('title', $target->title) }}"
                                   placeholder="Contoh: Menyelesaikan desain database"
                                   required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror">
                            @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="4"
                                      placeholder="Jelaskan detail target yang ingin dicapai..."
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $target->description) }}</textarea>
                            @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Upload Bukti atau Checkbox -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Bukti Target
                            </label>
                            
                            <!-- Existing Evidence Files -->
                            @if($target->evidence_files && count($target->evidence_files) > 0)
                            <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-700 mb-2 font-medium">File yang sudah diupload:</p>
                                <div class="space-y-2">
                                    @foreach($target->evidence_files as $file)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-file mr-2"></i>
                                        <a href="{{ asset('storage/' . $file) }}" target="_blank" class="text-blue-600 hover:underline">
                                            {{ basename($file) }}
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            <div class="space-y-4">
                                <!-- Checkbox: Hanya Centang (Tanpa Bukti) -->
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           name="is_checked_only" 
                                           id="is_checked_only" 
                                           value="1"
                                           {{ old('is_checked_only', $target->is_checked_only) ? 'checked' : '' }}
                                           onchange="toggleEvidenceUpload()"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="is_checked_only" class="ml-2 block text-sm text-gray-900">
                                        Tandai target tanpa upload bukti (hanya centang)
                                    </label>
                                </div>

                                <!-- Upload File -->
                                <div id="evidence_upload" class="{{ old('is_checked_only', $target->is_checked_only) ? 'opacity-50 pointer-events-none' : '' }}">
                                    <label for="evidence" class="block text-sm text-gray-600 mb-2">
                                        Upload Bukti Tambahan (Opsional)
                                    </label>
                                    <input type="file" 
                                           name="evidence[]" 
                                           id="evidence" 
                                           multiple
                                           accept="image/*,.pdf,.doc,.docx"
                                           class="w-full text-sm text-gray-500
                                                  file:mr-4 file:py-2 file:px-4
                                                  file:rounded-md file:border-0
                                                  file:text-sm file:font-semibold
                                                  file:bg-blue-50 file:text-blue-700
                                                  hover:file:bg-blue-100">
                                    <p class="mt-1 text-xs text-gray-500">
                                        Format: JPG, PNG, PDF, DOC, DOCX (Max 5 file, masing-masing max 2MB)
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('mahasiswa.dashboard') }}" 
                               class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                                <i class="fas fa-save mr-2"></i>Update Target
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleEvidenceUpload() {
            const checkbox = document.getElementById('is_checked_only');
            const uploadDiv = document.getElementById('evidence_upload');
            const fileInput = document.getElementById('evidence');
            
            if (checkbox.checked) {
                uploadDiv.classList.add('opacity-50', 'pointer-events-none');
                fileInput.value = '';
                fileInput.disabled = true;
            } else {
                uploadDiv.classList.remove('opacity-50', 'pointer-events-none');
                fileInput.disabled = false;
            }
        }

        // Auto-uncheck checkbox when user selects files
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('evidence');
            const checkbox = document.getElementById('is_checked_only');
            
            fileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    checkbox.checked = false;
                    toggleEvidenceUpload();
                }
            });
        });
    </script>
</x-app-layout>