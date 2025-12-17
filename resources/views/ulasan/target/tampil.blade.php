<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('targets.index') }}" 
                   class="mr-4 bg-white/20 hover:bg-white/30 text-white px-3 py-2 rounded-lg flex items-center gap-2 transition">
                    <i class="fas fa-arrow-left"></i>
                    <span class="text-sm font-medium">Target Mingguan</span>
                </a>
                <h2 class="font-semibold text-xl text-white leading-tight">
                    {{ __('Detail Review Target') }}
                </h2>
            </div>
            <a href="{{ route('target-reviews.edit', $target) }}" 
               class="text-white text-sm px-4 py-2 rounded hover:opacity-90 transition"
               style="background-color: #800000;">
                <i class="fas fa-edit mr-1"></i>Edit Review
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Target Info (Left) -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6">
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

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Diselesaikan</label>
                                <p class="text-xs text-gray-900">{{ $target->completed_at->format('d/m/Y H:i') }}</p>
                                <p class="text-xs text-gray-500">{{ $target->completed_at->diffForHumans() }}</p>
                            </div>

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

                <!-- Review Details (Right) -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="font-semibold text-gray-800 mb-6">
                            <i class="fas fa-star mr-2 text-yellow-500"></i>Review & Penilaian
                        </h3>

                        @if($target->review)
                        <div class="space-y-6">
                            <!-- Score & Status -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-primary-50 rounded-lg p-4">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Nilai</label>
                                    <p class="text-3xl font-bold text-primary-600">{{ number_format($target->review->score, 2) }}</p>
                                    <p class="text-xs text-gray-500">dari 100</p>
                                </div>
                                <div class="rounded-lg p-4 {{ $target->review->status == 'approved' ? 'bg-green-50' : ($target->review->status == 'needs_revision' ? 'bg-yellow-50' : 'bg-red-50') }}">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                                    <p class="text-sm font-semibold {{ $target->review->status == 'approved' ? 'text-green-700' : ($target->review->status == 'needs_revision' ? 'text-yellow-700' : 'text-red-700') }}">
                                        @if($target->review->status == 'approved')
                                            <i class="fas fa-check-circle mr-1"></i>Diterima
                                        @elseif($target->review->status == 'needs_revision')
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Revisi
                                        @else
                                            <i class="fas fa-times-circle mr-1"></i>Ditolak
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Feedback -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Feedback</label>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $target->review->feedback }}</p>
                                </div>
                            </div>

                            <!-- Suggestions -->
                            @if($target->review->suggestions)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Saran & Rekomendasi</label>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $target->review->suggestions }}</p>
                                </div>
                            </div>
                            @endif

                            <!-- Reviewer & Date -->
                            <div class="pt-4 border-t border-gray-200">
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <div>
                                        <i class="fas fa-user mr-1"></i>
                                        Direview oleh: <span class="font-medium">{{ $target->review->reviewer->name }}</span>
                                    </div>
                                    <div>
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $target->review->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="text-center py-8">
                            <i class="fas fa-star text-gray-400 text-4xl mb-3"></i>
                            <p class="text-gray-600">Belum ada review untuk target ini</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

