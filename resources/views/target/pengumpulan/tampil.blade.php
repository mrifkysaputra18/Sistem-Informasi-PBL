<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('targets.submissions.index') }}" 
               class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Detail Target: ') . $target->title }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Target Info -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Informasi Target</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="text-sm text-gray-600">Judul</div>
                        <div class="font-medium text-gray-900">{{ $target->title }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Minggu</div>
                        <div class="font-medium text-gray-900">Minggu {{ $target->week_number }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Dibuat oleh</div>
                        <div class="font-medium text-gray-900">{{ $target->creator->name ?? 'System' }}</div>
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
                        @if($target->isClosed())
                        <div class="mt-2">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-lock mr-1"></i>Target Tertutup
                            </span>
                            <p class="text-xs text-gray-500 mt-1">{{ $target->getClosureReason() }}</p>
                        </div>
                        @endif
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Status</div>
                        @php
                            $color = match($target->submission_status) {
                                'pending' => 'bg-gray-100 text-gray-800',
                                'submitted' => 'bg-primary-100 text-primary-800',
                                'late' => 'bg-orange-100 text-orange-800',
                                'approved' => 'bg-green-100 text-green-800',
                                'revision' => 'bg-yellow-100 text-yellow-800',
                                default => 'bg-gray-100 text-gray-800',
                            };
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $color }}">
                            {{ $target->getStatusLabel() }}
                        </span>
                    </div>
                </div>
                <div class="mt-6">
                    <div class="text-sm text-gray-600">Deskripsi</div>
                    <div class="text-gray-900 mt-1">{{ $target->description }}</div>
                </div>
            </div>

            <!-- My Submission -->
            @if($target->isSubmitted())
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Submission Saya</h3>
                    @if($target->canCancelSubmission())
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        <i class="fas fa-clock mr-1"></i>Dapat dibatalkan
                    </span>
                    @elseif($target->isReviewed())
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i>Sudah direview
                    </span>
                    @else
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        <i class="fas fa-lock mr-1"></i>Tidak dapat dibatalkan
                    </span>
                    @endif
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="text-sm text-gray-600">Tanggal Submit</div>
                        <div class="font-medium text-gray-900">
                            {{ $target->completed_at->format('d/m/Y H:i') }}
                            @if($target->isLate())
                            <span class="text-orange-600 font-medium">(Terlambat)</span>
                            @endif
                        </div>
                    </div>
                    @if($target->submission_notes)
                    <div class="md:col-span-2">
                        <div class="text-sm text-gray-600">Catatan Saya</div>
                        <div class="text-gray-900 mt-1 bg-gray-50 p-3 rounded">{{ $target->submission_notes }}</div>
                    </div>
                    @endif
                    @if($target->evidence_files && count($target->evidence_files) > 0)
                    <div class="md:col-span-2">
                        <div class="text-sm text-gray-600">File yang Saya Upload</div>
                        <div class="mt-2 space-y-2">
                            @foreach($target->evidence_files as $file)
                            <div class="flex items-center justify-between bg-gray-50 p-3 rounded">
                                <div class="flex items-center">
                                    <i class="fas fa-file mr-2 text-gray-500"></i>
                                    <span class="text-sm text-gray-700">{{ $file['file_name'] ?? 'File' }}</span>
                                </div>
                                @if(isset($file['local_path']))
                                <a href="{{ route('targets.download', [$target->id, $file['local_path']]) }}" 
                                   class="text-primary-600 hover:text-primary-800 text-sm">
                                    <i class="fas fa-download mr-1"></i>Download
                                </a>
                                @elseif(isset($file['file_url']))
                                <a href="{{ $file['file_url'] }}" 
                                   target="_blank"
                                   class="text-primary-600 hover:text-primary-800 text-sm">
                                    <i class="fas fa-external-link-alt mr-1"></i>Buka
                                </a>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @elseif($target->is_checked_only)
                    <div class="md:col-span-2">
                        <div class="text-sm text-gray-600">Tipe Submission</div>
                        <div class="text-gray-900 mt-1">
                            <i class="fas fa-check-circle text-green-500 mr-1"></i>
                            Submit tanpa file (checklist)
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Review dari Dosen -->
            @if($target->isReviewed())
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Review Dosen</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="text-sm text-gray-600">Direview oleh</div>
                        <div class="font-medium text-gray-900">{{ $target->reviewer->name ?? 'Dosen' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Tanggal Review</div>
                        <div class="font-medium text-gray-900">{{ $target->reviewed_at->format('d/m/Y H:i') }}</div>
                    </div>
                    @if($target->review_notes)
                    <div class="md:col-span-2">
                        <div class="text-sm text-gray-600">Catatan Review</div>
                        <div class="text-gray-900 mt-1 bg-gray-50 p-3 rounded">{{ $target->review_notes }}</div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <a href="{{ route('targets.submissions.index') }}" 
                       class="inline-flex items-center px-6 py-2.5 bg-gray-500 hover:bg-gray-700 text-white font-semibold rounded-lg transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    
                    <div class="flex flex-wrap gap-2">
                        @if($target->canAcceptSubmission())
                            @if($target->isPending())
                            <a href="{{ route('targets.submissions.submit', $target->id) }}" 
                               class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all">
                                <i class="fas fa-upload mr-2"></i>Submit Target
                            </a>
                            @elseif($target->submission_status === 'revision')
                            <a href="{{ route('targets.submissions.edit', $target->id) }}" 
                               class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all">
                                <i class="fas fa-edit mr-2"></i>Revisi Submission
                            </a>
                            @elseif($target->isSubmitted() && !$target->isReviewed())
                            <a href="{{ route('targets.submissions.edit', $target->id) }}" 
                               class="inline-flex items-center px-6 py-2.5 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all">
                                <i class="fas fa-edit mr-2"></i>Edit
                            </a>
                            @endif
                        @endif
                        
                        <!-- Cancel Submission Button (if can cancel) - ENHANCED -->
                        @if($target->canCancelSubmission())
                        <form action="{{ route('targets.submissions.cancel', $target->id) }}" method="POST" class="inline"
                              id="cancel-form-bottom">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    onclick="cancelSubmission('{{ addslashes($target->title) }}', 'cancel-form-bottom')"
                                    class="inline-flex items-center px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all">
                                <i class="fas fa-times-circle mr-2"></i>Batalkan
                            </button>
                        </form>
                        @endif
                        
                        @if(!$target->canAcceptSubmission() && !$target->canCancelSubmission())
                            @if($target->isClosed())
                            <div class="inline-flex items-center text-red-600 text-sm bg-red-50 px-4 py-2 rounded-lg border border-red-200">
                                <i class="fas fa-lock mr-2"></i>Target sudah tertutup. Tidak dapat mensubmit lagi.
                            </div>
                            @elseif($target->isReviewed())
                            <div class="inline-flex items-center text-gray-600 text-sm bg-gray-50 px-4 py-2 rounded-lg border border-gray-200">
                                <i class="fas fa-check-circle mr-2"></i>Target sudah direview dosen.
                            </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function cancelSubmission(targetTitle, formId) {
            const form = document.getElementById(formId);
            
            Swal.fire({
                title: 'Batalkan Submission?',
                text: 'File yang diupload akan dihapus dan Anda harus upload ulang.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>
