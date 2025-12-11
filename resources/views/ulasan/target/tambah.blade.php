<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ url('/targets/week/' . $target->week_number . '/class/' . $target->group->class_room_id . '/info') }}" 
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
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-xs font-medium text-gray-500">Bukti ({{ count($target->evidence_files) }} file)</label>
                                    <a href="{{ route('target-reviews.download-all', $target->id) }}" 
                                       class="inline-flex items-center px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded transition-colors">
                                        <i class="fas fa-download mr-1"></i>Download All (ZIP)
                                    </a>
                                </div>
                                <div class="space-y-2 bg-gray-50 p-3 rounded-lg border border-gray-200">
                                    @foreach($target->evidence_files as $index => $file)
                                    <div class="flex items-center justify-between bg-white p-2 rounded border border-gray-200 hover:border-blue-300 transition-colors">
                                        <div class="flex items-center min-w-0 flex-1">
                                            @php
                                                $ext = isset($file['file_name']) ? strtolower(pathinfo($file['file_name'], PATHINFO_EXTENSION)) : '';
                                                $icon = match($ext) {
                                                    'pdf' => 'fa-file-pdf text-red-600',
                                                    'doc', 'docx' => 'fa-file-word text-blue-600',
                                                    'xls', 'xlsx' => 'fa-file-excel text-green-600',
                                                    'jpg', 'jpeg', 'png', 'gif' => 'fa-file-image text-purple-600',
                                                    'zip', 'rar' => 'fa-file-archive text-yellow-600',
                                                    default => 'fa-file text-gray-600',
                                                };
                                            @endphp
                                            <i class="fas {{ $icon }} mr-2 flex-shrink-0"></i>
                                            <span class="text-xs text-gray-900 truncate font-medium">{{ $file['file_name'] ?? 'File ' . ($index + 1) }}</span>
                                            @if(isset($file['storage_type']))
                                            <span class="ml-2 px-1.5 py-0.5 bg-gray-100 text-gray-600 text-xs rounded">
                                                {{ $file['storage_type'] === 'google_drive' ? '☁️ Drive' : '💾 Local' }}
                                            </span>
                                            @endif
                                        </div>
                                        <a href="{{ route('target-reviews.download-file', [$target->id, $index]) }}" 
                                           class="ml-2 inline-flex items-center px-2 py-1 bg-green-600 hover:bg-green-700 text-white text-xs rounded transition-colors flex-shrink-0">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @elseif($target->is_checked_only)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                <div class="flex items-center text-xs text-yellow-800">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    <span class="font-medium">Target diselesaikan tanpa upload file (checklist only)</span>
                                </div>
                            </div>
                            @else
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                                <div class="flex items-center text-xs text-gray-600">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <span>Tidak ada file bukti yang diupload</span>
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

                            <!-- Todo List Verification (if exists) -->
                            @if($target->hasTodoItems())
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    <i class="fa-solid fa-list-check mr-1 text-indigo-600"></i>
                                    Verifikasi Todo List <span class="text-red-500">*</span>
                                </label>
                                <p class="text-xs text-gray-500 mb-3">
                                    Centang todo yang benar-benar sudah diselesaikan mahasiswa.
                                </p>

                                <div class="space-y-2 bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    @foreach($target->todoItems as $index => $todo)
                                    <label class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-all
                                                  {{ $todo->is_completed_by_student ? 'bg-blue-50 border-blue-200' : 'bg-white border-gray-200' }}"
                                           id="todo-label-{{ $todo->id }}">
                                        <input type="checkbox" 
                                               name="verified_todos[]" 
                                               value="{{ $todo->id }}"
                                               {{ $todo->is_verified_by_reviewer ? 'checked' : '' }}
                                               onchange="updateScorePreview()"
                                               class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                                    {{ $index + 1 }}
                                                </span>
                                                <span class="font-medium text-gray-900 text-sm">{{ $todo->title }}</span>
                                            </div>
                                            @if($todo->description)
                                            <p class="text-xs text-gray-500 mt-1 ml-7">{{ $todo->description }}</p>
                                            @endif
                                        </div>
                                        @if($todo->is_completed_by_student)
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded">
                                            <i class="fa-solid fa-user mr-1"></i>Claimed
                                        </span>
                                        @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-500 text-xs font-medium rounded">
                                            <i class="fa-solid fa-times mr-1"></i>Not claimed
                                        </span>
                                        @endif
                                    </label>
                                    @endforeach
                                </div>

                                <div class="mt-3 flex items-center gap-4 text-sm">
                                    <span class="text-gray-600">
                                        Verified: <strong id="verified-count">{{ $target->getVerifiedTodoCount() }}</strong>/{{ $target->getTotalTodoCount() }} todo
                                    </span>
                                    <span class="text-indigo-600">
                                        = <strong id="verified-percent">{{ number_format($target->getVerifiedPercentage(), 0) }}%</strong>
                                    </span>
                                </div>
                            </div>
                            @endif

                            <!-- Quality Score -->
                            <div class="mb-6">
                                <label for="quality_score" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nilai Kualitas (0-100) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       name="quality_score" 
                                       id="quality_score" 
                                       value="{{ old('quality_score', 100) }}"
                                       min="0"
                                       max="100"
                                       step="1"
                                       required
                                       oninput="updateScorePreview()"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('quality_score') border-red-500 @enderror">
                                @error('quality_score')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Berikan nilai kualitas pengerjaan (0-100)</p>
                            </div>

                            <!-- Score Preview -->
                            @if($target->hasTodoItems())
                            <div class="mb-6 p-4 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-lg border border-indigo-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-sm font-medium text-gray-700">Nilai Akhir (Auto-calculate)</span>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Formula: (<span id="formula-verified">0</span>/<span id="formula-total">{{ $target->getTotalTodoCount() }}</span>) × <span id="formula-quality">100</span>
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-3xl font-black text-indigo-600" id="final-score-preview">0</span>
                                        <span class="text-lg text-gray-500">/100</span>
                                    </div>
                                </div>
                            </div>
                            @endif

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

    <script>
        const totalTodos = {{ $target->hasTodoItems() ? $target->getTotalTodoCount() : 0 }};

        function updateScorePreview() {
            if (totalTodos === 0) return;

            // Get verified checkboxes count
            const verifiedCheckboxes = document.querySelectorAll('input[name="verified_todos[]"]:checked');
            const verifiedCount = verifiedCheckboxes.length;
            
            // Get quality score
            const qualityScore = parseFloat(document.getElementById('quality_score').value) || 0;
            
            // Calculate final score: (verified/total) × quality_score
            const finalScore = (verifiedCount / totalTodos) * qualityScore;
            
            // Update UI elements
            document.getElementById('verified-count').textContent = verifiedCount;
            document.getElementById('verified-percent').textContent = Math.round((verifiedCount / totalTodos) * 100) + '%';
            document.getElementById('formula-verified').textContent = verifiedCount;
            document.getElementById('formula-quality').textContent = qualityScore;
            document.getElementById('final-score-preview').textContent = finalScore.toFixed(1);
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateScorePreview();
        });
    </script>
</x-app-layout>

