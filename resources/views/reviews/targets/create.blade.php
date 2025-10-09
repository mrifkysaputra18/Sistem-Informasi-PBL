<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('target-reviews.index') }}" 
               class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Review & Nilai Target Mingguan') }}
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
                            <!-- Group -->
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Kelompok</label>
                                <p class="text-sm font-semibold text-gray-900">{{ $target->group->name }}</p>
                                <p class="text-xs text-gray-500">{{ $target->group->classRoom->name ?? '-' }}</p>
                            </div>

                            <!-- Week -->
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Minggu</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                    Minggu {{ $target->week_number }}
                                </span>
                            </div>

                            <!-- Title -->
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Target</label>
                                <p class="text-sm text-gray-900">{{ $target->title }}</p>
                            </div>

                            <!-- Description -->
                            @if($target->description)
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Deskripsi</label>
                                <p class="text-xs text-gray-600">{{ $target->description }}</p>
                            </div>
                            @endif

                            <!-- Completed Info -->
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Diselesaikan</label>
                                <p class="text-xs text-gray-900">{{ $target->completed_at->format('d/m/Y H:i') }}</p>
                                <p class="text-xs text-gray-500">{{ $target->completed_at->diffForHumans() }}</p>
                            </div>

                            <!-- Evidence Files -->
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

                <!-- Review Form (Right) -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="font-semibold text-gray-800 mb-6">
                            <i class="fas fa-star mr-2 text-yellow-500"></i>Berikan Penilaian
                        </h3>

                        <form action="{{ route('target-reviews.store', $target) }}" method="POST">
                            @csrf

                            <!-- Score -->
                            <div class="mb-6">
                                <label for="score" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nilai (0-100) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       name="score" 
                                       id="score" 
                                       value="{{ old('score') }}"
                                       min="0"
                                       max="100"
                                       step="0.01"
                                       required
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('score') border-red-500 @enderror">
                                @error('score')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Berikan nilai berdasarkan kualitas dan ketepatan waktu penyelesaian target</p>
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
                                    <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>✓ Diterima (Approved)</option>
                                    <option value="needs_revision" {{ old('status') == 'needs_revision' ? 'selected' : '' }}>⚠ Perlu Revisi</option>
                                    <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>✗ Ditolak (Rejected)</option>
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
                                          placeholder="Berikan feedback konstruktif kepada mahasiswa mengenai target yang telah diselesaikan..."
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('feedback') border-red-500 @enderror">{{ old('feedback') }}</textarea>
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
                                          placeholder="Berikan saran atau rekomendasi untuk improvement..."
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('suggestions') border-red-500 @enderror">{{ old('suggestions') }}</textarea>
                                @error('suggestions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                                <a href="{{ route('target-reviews.index') }}" 
                                   class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="fas fa-times mr-2"></i>Batal
                                </a>
                                <button type="submit" 
                                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-green-600 hover:bg-green-700">
                                    <i class="fas fa-check mr-2"></i>Simpan Review
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

