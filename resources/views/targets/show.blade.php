<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('targets.index') }}" 
               class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
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
                        <div class="text-sm text-gray-600">Kelompok</div>
                        <div class="font-medium text-gray-900">{{ $target->group->name }}</div>
                        <div class="text-sm text-gray-500">{{ $target->group->classRoom->name }}</div>
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
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Status</div>
                        @php
                            $color = match($target->submission_status) {
                                'pending' => 'bg-gray-100 text-gray-800',
                                'submitted' => 'bg-blue-100 text-blue-800',
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

            <!-- Submission Info -->
            @if($target->isSubmitted())
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Informasi Submission</h3>
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
                    <div>
                        <div class="text-sm text-gray-600">Submit oleh</div>
                        <div class="font-medium text-gray-900">{{ $target->completedBy->name ?? 'Mahasiswa' }}</div>
                    </div>
                    @if($target->submission_notes)
                    <div class="md:col-span-2">
                        <div class="text-sm text-gray-600">Catatan Mahasiswa</div>
                        <div class="text-gray-900 mt-1 bg-gray-50 p-3 rounded">{{ $target->submission_notes }}</div>
                    </div>
                    @endif
                    @if($target->evidence_files && count($target->evidence_files) > 0)
                    <div class="md:col-span-2">
                        <div class="text-sm text-gray-600">File Bukti</div>
                        <div class="mt-2 space-y-2">
                            @foreach($target->evidence_files as $file)
                            <div class="flex items-center justify-between bg-gray-50 p-3 rounded">
                                <div class="flex items-center">
                                    <i class="fas fa-file mr-2 text-gray-500"></i>
                                    <span class="text-sm text-gray-700">{{ $file['file_name'] }}</span>
                                </div>
                                <a href="{{ route('targets.download', ['target' => $target->id, 'file' => $file['local_path'] }) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="fas fa-download mr-1"></i>Download
                                </a>
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

            <!-- Review Info -->
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
                <div class="flex justify-between items-center">
                    <a href="{{ route('targets.index') }}" 
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                        Kembali
                    </a>
                    
                    <div class="flex space-x-2">
                        @if($target->created_by === auth()->id() || auth()->user()->isAdmin())
                        @if($target->isPending())
                        <a href="{{ route('targets.edit', $target->id) }}" 
                           class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-6 rounded">
                            <i class="fas fa-edit mr-2"></i>Edit Target
                        </a>
                        @endif
                        @endif
                        
                        @if($target->isSubmitted() && !$target->isReviewed())
                        <a href="{{ route('targets.review', $target->id) }}" 
                           class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-6 rounded">
                            <i class="fas fa-check-circle mr-2"></i>Review Submission
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
