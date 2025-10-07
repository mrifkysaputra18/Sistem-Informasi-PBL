<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('targets.index') }}" 
               class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Review & Nilai Target Mingguan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Target Info (Left) -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                        <h3 class="font-semibold text-gray-800 mb-4">
                            <i class="fas fa-info-circle mr-2 text-blue-600"></i>Info Target
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
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
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

                            <!-- Deadline -->
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Deadline</label>
                                <p class="text-xs text-gray-900">{{ $target->deadline->format('d/m/Y H:i') }}</p>
                            </div>

                            <!-- Completed Info -->
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Diselesaikan</label>
                                <p class="text-xs text-gray-900">{{ $target->completed_at->format('d/m/Y H:i') }}</p>
                                <p class="text-xs text-gray-500">{{ $target->completed_at->diffForHumans() }}</p>
                            </div>

                            <!-- Completed By -->
                            @if($target->completedByUser)
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Disubmit oleh</label>
                                <p class="text-xs text-gray-900">{{ $target->completedByUser->name }}</p>
                            </div>
                            @endif

                            <!-- Submission Notes -->
                            @if($target->submission_notes)
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Catatan Mahasiswa</label>
                                <p class="text-xs text-gray-600 bg-gray-50 p-2 rounded">{{ $target->submission_notes }}</p>
                            </div>
                            @endif

                            <!-- Evidence Files -->
                            @if($target->evidence_files && count($target->evidence_files) > 0)
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">
                                    Bukti ({{ count($target->evidence_files) }} file)
                                </label>
                                <div class="space-y-1">
                                    @foreach($target->evidence_files as $file)
                                    <a href="{{ asset('storage/' . $file['local_path']) }}" 
                                       target="_blank"
                                       download="{{ $file['file_name'] ?? basename($file['local_path']) }}"
                                       class="flex items-center text-xs text-blue-600 hover:bg-blue-50 p-2 rounded transition">
                                        <i class="fas fa-file-download mr-2"></i>
                                        <span class="truncate">{{ $file['file_name'] ?? basename($file['local_path']) }}</span>
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Group Members -->
                            @if($target->group && $target->group->members)
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">
                                    Anggota Kelompok ({{ count($target->group->members) }})
                                </label>
                                <div class="space-y-1">
                                    @foreach($target->group->members as $member)
                                    <div class="flex items-center text-xs">
                                        @if($member->role === 'leader')
                                        <i class="fas fa-crown text-yellow-500 mr-2"></i>
                                        @else
                                        <i class="fas fa-user text-gray-400 mr-2"></i>
                                        @endif
                                        <span class="text-gray-700">{{ $member->user->name }}</span>
                                        @if($member->role === 'leader')
                                        <span class="ml-2 text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded">Leader</span>
                                        @endif
                                    </div>
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

                        @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">
                            <div class="font-bold mb-2">
                                <i class="fas fa-exclamation-circle mr-2"></i>Terjadi Kesalahan!
                            </div>
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form action="{{ route('targets.review.store', $target->id) }}" method="POST">
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
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('score') border-red-500 @enderror">
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
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('status') border-red-500 @enderror">
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
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('feedback') border-red-500 @enderror">{{ old('feedback') }}</textarea>
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
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('suggestions') border-red-500 @enderror">{{ old('suggestions') }}</textarea>
                                @error('suggestions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                                <a href="{{ route('targets.index') }}" 
                                   class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                                    <i class="fas fa-times mr-2"></i>Batal
                                </a>
                                <button type="submit" 
                                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-green-600 hover:bg-green-700 transition">
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

