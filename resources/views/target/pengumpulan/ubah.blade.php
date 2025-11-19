<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('targets.submissions.index') }}" 
               class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Edit Submission: ') . $target->title }}
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
                        <div class="text-sm text-gray-600">Status</div>
                        <div>
                            @if($target->submission_status === 'submitted')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-paper-plane mr-1"></i>Sudah Submit
                                </span>
                            @elseif($target->submission_status === 'approved')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Diterima
                                </span>
                            @elseif($target->submission_status === 'needs_revision')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-exclamation-circle mr-1"></i>Perlu Revisi
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-sm text-gray-600">Deskripsi</div>
                    <div class="text-gray-900 mt-1">{{ $target->description }}</div>
                </div>

                @if($target->submission_notes)
                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="text-sm font-medium text-blue-800 mb-1">Catatan Submission Sebelumnya:</div>
                    <div class="text-sm text-blue-700">{{ $target->submission_notes }}</div>
                </div>
                @endif
            </div>

            <!-- Edit Submission Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Edit Submission</h3>
                    
                    @if($target->submission_status === 'approved')
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                            <div>
                                <div class="font-medium text-yellow-800">Perhatian!</div>
                                <div class="text-sm text-yellow-700">Target ini sudah diterima dosen. Mengedit submission akan mengubah status menjadi "Submitted" dan memerlukan review ulang.</div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <form method="POST" action="{{ route('targets.submissions.update', $target->id) }}" 
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Submission Notes -->
                        <div class="mb-6">
                            <label for="submission_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan Submission
                            </label>
                            <textarea 
                                name="submission_notes" 
                                id="submission_notes" 
                                rows="3"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                placeholder="Opsional: Tambahkan catatan atau keterangan tentang submission Anda...">{{ old('submission_notes', $target->submission_notes) }}</textarea>
                            @error('submission_notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Files -->
                        @if($target->evidence_files && count($target->evidence_files) > 0)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                File Saat Ini ({{ count($target->evidence_files) }})
                            </label>
                            <div class="space-y-2">
                                @foreach($target->evidence_files as $index => $file)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex items-center min-w-0 flex-1">
                                        <i class="fas fa-file text-gray-500 mr-3"></i>
                                        <span class="text-sm text-gray-900 truncate">{{ $file['file_name'] ?? 'File ' . ($index + 1) }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2 ml-4">
                                        @if(isset($file['local_path']))
                                        <a href="{{ asset('storage/' . $file['local_path']) }}" 
                                           target="_blank"
                                           class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <p class="mt-2 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Upload file baru di bawah untuk mengganti file yang ada
                            </p>
                        </div>
                        @endif

                        <!-- New File Upload -->
                        <div class="mb-6">
                            <label for="evidence" class="block text-sm font-medium text-gray-700 mb-2">
                                Upload File Baru (Opsional)
                            </label>
                            <input 
                                type="file" 
                                name="evidence[]" 
                                id="evidence" 
                                multiple
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip,.rar"
                                class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-primary-50 file:text-primary-700
                                    hover:file:bg-primary-100">
                            <p class="mt-2 text-xs text-gray-500">
                                Format: PDF, DOC, DOCX, JPG, PNG, ZIP, RAR. Max: 10MB per file. 
                                <strong>Kosongkan jika tidak ingin mengganti file.</strong>
                            </p>
                            @error('evidence')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('evidence.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-between pt-4 border-t">
                            <a href="{{ route('targets.submissions.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition-colors">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                            
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-bold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105">
                                <i class="fas fa-save mr-2"></i>Update Submission
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
