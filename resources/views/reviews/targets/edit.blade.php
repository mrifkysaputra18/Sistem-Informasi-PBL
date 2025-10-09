<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('target-reviews.show', $target) }}" 
               class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Edit Review Target') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Target Info (Left) -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                        <h3 class="font-semibold text-gray-800 mb-4">
                            <i class="fas fa-info-circle mr-2 text-primary-600"></i>Info Target
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Kelompok</label>
                                <p class="text-sm font-semibold text-gray-900">{{ $target->group->name }}</p>
                                <p class="text-xs text-gray-500">{{ $target->group->classRoom->name ?? '-' }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Minggu</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                    Minggu {{ $target->week_number }}
                                </span>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Target</label>
                                <p class="text-sm text-gray-900">{{ $target->title }}</p>
                            </div>

                            @if($target->description)
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Deskripsi</label>
                                <p class="text-xs text-gray-600">{{ $target->description }}</p>
                            </div>
                            @endif

                            @if($target->evidence_files && count($target->evidence_files) > 0)
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Bukti ({{ count($target->evidence_files) }} file)</label>
                                <div class="space-y-1">
                                    @foreach($target->evidence_files as $file)
                                    <a href="{{ asset('storage/' . $file['local_path']) }}" 
                                       target="_blank"
                                       class="text-xs text-primary-600 hover:underline block">
                                        <i class="fas fa-file mr-1"></i>{{ $file['file_name'] }}
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Edit Review Form (Right) -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="font-semibold text-gray-800 mb-6">
                            <i class="fas fa-edit mr-2 text-primary-600"></i>Edit Penilaian
                        </h3>

                        <form action="{{ route('target-reviews.update', $target) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Score -->
                            <div class="mb-6">
                                <label for="score" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nilai (0-100) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       name="score" 
                                       id="score" 
                                       value="{{ old('score', $target->review->score) }}"
                                       min="0"
                                       max="100"
                                       step="0.01"
                                       required
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('score') border-red-500 @enderror">
                                @error('score')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="mb-6">
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                    Status Review <span class="text-red-500">*</span>
                                </label>
                                <select name="status" 
                                        id="status" 
                                        required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                                    <option value="">Pilih Status</option>
                                    <option value="approved" {{ old('status', $target->review->status) == 'approved' ? 'selected' : '' }}>✓ Diterima (Approved)</option>
                                    <option value="needs_revision" {{ old('status', $target->review->status) == 'needs_revision' ? 'selected' : '' }}>⚠ Perlu Revisi</option>
                                    <option value="rejected" {{ old('status', $target->review->status) == 'rejected' ? 'selected' : '' }}>✗ Ditolak (Rejected)</option>
                                </select>
                                @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Feedback -->
                            <div class="mb-6">
                                <label for="feedback" class="block text-sm font-medium text-gray-700 mb-2">
                                    Feedback <span class="text-red-500">*</span>
                                </label>
                                <textarea name="feedback" 
                                          id="feedback" 
                                          rows="5"
                                          required
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('feedback') border-red-500 @enderror">{{ old('feedback', $target->review->feedback) }}</textarea>
                                @error('feedback')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Suggestions -->
                            <div class="mb-6">
                                <label for="suggestions" class="block text-sm font-medium text-gray-700 mb-2">
                                    Saran & Rekomendasi (Opsional)
                                </label>
                                <textarea name="suggestions" 
                                          id="suggestions" 
                                          rows="4"
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('suggestions') border-red-500 @enderror">{{ old('suggestions', $target->review->suggestions) }}</textarea>
                                @error('suggestions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                                <a href="{{ route('target-reviews.show', $target) }}" 
                                   class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="fas fa-times mr-2"></i>Batal
                                </a>
                                <button type="submit" 
                                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-primary-600 hover:bg-primary-700">
                                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

