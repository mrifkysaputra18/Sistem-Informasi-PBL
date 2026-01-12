<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('mahasiswa.dashboard') }}" 
               class="back-btn mr-4"><i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Upload Progress Mingguan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8">
                    <form action="{{ route('weekly-progress.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <input type="hidden" name="group_id" value="{{ request('group_id') }}">

                        <!-- Minggu Ke -->
                        <div class="mb-6">
                            <label for="week_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Minggu Ke <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="week_number" 
                                   id="week_number" 
                                   value="{{ old('week_number') }}"
                                   min="1"
                                   required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500">
                        </div>

                        <!-- Judul Progress -->
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Progress <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   value="{{ old('title') }}"
                                   placeholder="Contoh: Implementasi Fitur Login"
                                   required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500">
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="4"
                                      placeholder="Jelaskan progress yang telah dicapai..."
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                        </div>

                        <!-- Upload Bukti atau Checkbox -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Bukti Progress
                            </label>
                            
                            <div class="space-y-4">
                                <!-- Checkbox: Hanya Centang (Tanpa Bukti) -->
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           name="is_checked_only" 
                                           id="is_checked_only" 
                                           value="1"
                                           {{ old('is_checked_only') ? 'checked' : '' }}
                                           onchange="toggleEvidenceUpload()"
                                           class="h-4 w-4 text-primary-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="is_checked_only" class="ml-2 block text-sm text-gray-900">
                                        Tandai selesai tanpa upload bukti (hanya centang)
                                    </label>
                                </div>

                                <!-- Upload File -->
                                <div id="evidence_upload" class="{{ old('is_checked_only') ? 'opacity-50 pointer-events-none' : '' }}">
                                    <label for="evidence" class="block text-sm text-gray-600 mb-2">
                                        Upload Bukti (Opsional jika tidak dicentang)
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
                                                  file:bg-primary-50 file:text-primary-700
                                                  hover:file:bg-primary-100">
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
                                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-secondary-600 hover:bg-secondary-700">
                                <i class="fas fa-upload mr-2"></i>Upload Progress
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
            } else {
                uploadDiv.classList.remove('opacity-50', 'pointer-events-none');
            }
        }
    </script>
</x-app-layout>