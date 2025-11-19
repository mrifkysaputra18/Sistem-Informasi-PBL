<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('targets.index') }}" 
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
                        @if($target->isClosed())
                        <div class="mt-2">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-lock mr-1"></i>Target Tertutup
                            </span>
                            <p class="text-xs text-gray-500 mt-1">{{ $target->getClosureReason() }}</p>
                            @if($target->reopened_at)
                            <p class="text-xs text-primary-600 mt-1">
                                <i class="fas fa-history mr-1"></i>
                                Pernah dibuka kembali oleh {{ $target->reopener->name ?? 'Dosen' }} pada {{ $target->reopened_at->format('d/m/Y H:i') }}
                            </p>
                            @endif
                        </div>
                        @else
                        <div class="mt-2">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-unlock mr-1"></i>Target Terbuka
                            </span>
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
                        <div class="font-medium text-gray-900">{{ $target->completedByUser->name ?? 'Mahasiswa' }}</div>
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
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <a href="{{ route('targets.index') }}" 
                       class="inline-flex items-center px-6 py-2.5 bg-gray-500 hover:bg-gray-700 text-white font-semibold rounded-lg transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    
                    <div class="flex flex-wrap gap-2">
                        <!-- Edit Button (Dosen can edit targets in their class, Admin can edit all) -->
                        @php
                            $canEdit = auth()->user()->isAdmin() 
                                    || $target->created_by === auth()->id()
                                    || (auth()->user()->isDosen() && $target->group->classRoom->dosen_id === auth()->id());
                        @endphp
                        
                        @if($canEdit)
                        <a href="{{ route('targets.edit', $target->id) }}" 
                           class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white font-bold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105">
                            <i class="fas fa-edit mr-2"></i>Edit Target
                        </a>
                        
                        <!-- Delete Button (Same permission as edit) -->
                        @php
                            $deleteMessage = "⚠️ PERHATIAN!\n\nYakin ingin menghapus target ini?\n\n";
                            $deleteMessage .= "Target: {$target->title}\n";
                            $deleteMessage .= "Kelompok: {$target->group->name}\n";
                            $deleteMessage .= "Status: {$target->getStatusLabel()}\n\n";
                            if ($target->isSubmitted()) {
                                $deleteMessage .= "⚠️ Target ini sudah disubmit mahasiswa!\n\n";
                            }
                            $deleteMessage .= "Lanjutkan hapus?";
                        @endphp
                        <form action="{{ route('targets.destroy', $target->id) }}" 
                              method="POST" 
                              class="inline"
                              onsubmit="return confirm({{ json_encode($deleteMessage) }})">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105">
                                <i class="fas fa-trash mr-2"></i>Hapus Target
                            </button>
                        </form>
                        @endif
                        
                        <!-- Review Button (If submitted and not reviewed) -->
                        @if($target->isSubmitted() && !$target->isReviewed())
                        <a href="{{ route('target-reviews.show', $target->id) }}" 
                           class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105">
                            <i class="fas fa-check-circle mr-2"></i>Review Submission
                        </a>
                        @endif
                        
                        <!-- Reopen/Close Target Buttons (Dosen has full control) -->
                        @if(in_array(auth()->user()->role, ['dosen', 'admin', 'koordinator']))
                            @if($target->isClosed())
                                <!-- Reopen Button (Always available for dosen) -->
                                @php
                                    $reopenMessage = "⚠️ PERHATIAN!\n\nYakin ingin membuka kembali target ini?\n\n";
                                    $reopenMessage .= "Target: {$target->title}\n";
                                    $reopenMessage .= "Kelompok: {$target->group->name}\n";
                                    $reopenMessage .= "Status: " . ($target->isClosed() ? 'Tertutup' : 'Terbuka') . "\n\n";
                                    if ($target->isReviewed()) {
                                        $reopenMessage .= "⚠️ TARGET INI SUDAH DIREVIEW!\n\n";
                                        $reopenMessage .= "Membuka kembali target yang sudah direview akan memungkinkan mahasiswa untuk:\n";
                                        $reopenMessage .= "• Submit ulang progress mereka\n";
                                        $reopenMessage .= "• Mengubah file yang sudah direview\n\n";
                                    }
                                    $reopenMessage .= "Mahasiswa akan dapat mensubmit ulang setelah dibuka.\n\n";
                                    $reopenMessage .= "Lanjutkan buka target?";
                                @endphp
                                <form action="{{ route('targets.reopen', $target->id) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm({{ json_encode($reopenMessage) }})">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105 {{ $target->isReviewed() ? 'ring-2 ring-yellow-400' : '' }}">
                                        <i class="fas fa-unlock mr-2"></i>Buka Kembali
                                        @if($target->isReviewed())
                                        <span class="ml-1 text-xs bg-yellow-400 text-yellow-900 px-2 py-0.5 rounded">Sudah Direview</span>
                                        @endif
                                    </button>
                                </form>
                            @else
                                <!-- Close Button -->
                                <form action="{{ route('targets.close', $target->id) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Yakin ingin menutup target ini?\n\nMahasiswa tidak akan dapat mensubmit target ini.')">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-bold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105">
                                        <i class="fas fa-lock mr-2"></i>Tutup Target
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
