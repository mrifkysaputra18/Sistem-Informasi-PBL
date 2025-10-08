<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Target Mingguan Saya') }}
            </h2>
            <div class="text-sm text-gray-600">
                Kelompok: <span class="font-semibold">{{ $group->name }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

            @if(session('info'))
            <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                {{ session('info') }}
            </div>
            @endif

            <!-- Progress Overview -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Progress Target</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded">
                        <div class="text-2xl font-bold text-gray-600">{{ $targets->where('submission_status', 'pending')->count() }}</div>
                        <div class="text-sm text-gray-600">Belum Dikerjakan</div>
                    </div>
                    <div class="text-center p-4 bg-blue-50 rounded">
                        <div class="text-2xl font-bold text-blue-600">{{ $targets->whereIn('submission_status', ['submitted', 'late'])->count() }}</div>
                        <div class="text-sm text-gray-600">Sudah Submit</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded">
                        <div class="text-2xl font-bold text-green-600">{{ $targets->where('submission_status', 'approved')->count() }}</div>
                        <div class="text-sm text-gray-600">Disetujui</div>
                    </div>
                    <div class="text-center p-4 bg-yellow-50 rounded">
                        <div class="text-2xl font-bold text-yellow-600">{{ $targets->where('submission_status', 'revision')->count() }}</div>
                        <div class="text-sm text-gray-600">Perlu Revisi</div>
                    </div>
                </div>
            </div>

            <!-- Targets List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Daftar Target</h3>
                    
                    @if($targets->count() > 0)
                    <div class="space-y-4">
                        @foreach($targets as $target)
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $target->title }}</h4>
                                    <p class="text-sm text-gray-600 mb-2">{{ Str::limit($target->description, 100) }}</p>
                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        <span><i class="fas fa-calendar mr-1"></i>Minggu {{ $target->week_number }}</span>
                                        @if($target->deadline)
                                        <span><i class="fas fa-clock mr-1"></i>Deadline: {{ $target->deadline->format('d/m/Y H:i') }}</span>
                                        @endif
                                        <span><i class="fas fa-user mr-1"></i>Dibuat: {{ $target->creator->name ?? 'System' }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
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

                            <!-- Submission Info -->
                            @if($target->isSubmitted())
                            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            <i class="fas fa-check-circle mr-1 text-green-500"></i>
                                            Sudah disubmit
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $target->completed_at->format('d/m/Y H:i') }}
                                            @if($target->isLate())
                                            <span class="text-orange-600 font-medium">(Terlambat)</span>
                                            @endif
                                        </div>
                                        @if($target->submission_notes)
                                        <div class="text-sm text-gray-600 mt-1">
                                            <strong>Catatan:</strong> {{ $target->submission_notes }}
                                        </div>
                                        @endif
                                        @if($target->evidence_files && count($target->evidence_files) > 0)
                                        <div class="text-sm text-gray-600 mt-1">
                                            <strong>File:</strong> {{ count($target->evidence_files) }} file
                                        </div>
                                        @elseif($target->is_checked_only)
                                        <div class="text-sm text-gray-600 mt-1">
                                            <strong>Submit:</strong> Tanpa file (checklist)
                                        </div>
                                        @endif
                                    </div>
                                    @if($target->isReviewed())
                                    <div class="text-right">
                                        <div class="text-sm font-medium text-gray-900">
                                            <i class="fas fa-user-check mr-1 text-blue-500"></i>
                                            Direview oleh {{ $target->reviewer->name ?? 'Dosen' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $target->reviewed_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <!-- Actions -->
                            <div class="flex justify-between items-center">
                                <div class="flex space-x-2">
                                    <!-- View Detail -->
                                    <a href="{{ route('targets.submissions.show', $target->id) }}" 
                                       class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition duration-200">
                                        <i class="fas fa-eye mr-1"></i>Lihat Detail
                                    </a>

                                    @if($target->canAcceptSubmission())
                                        @if($target->isPending())
                                        <!-- Submit -->
                                        <a href="{{ route('targets.submissions.submit', $target->id) }}" 
                                           class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition duration-200">
                                            <i class="fas fa-upload mr-1"></i>Submit Target
                                        </a>
                                        @elseif($target->submission_status === 'revision')
                                        <!-- Re-submit -->
                                        <a href="{{ route('targets.submissions.edit', $target->id) }}" 
                                           class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 rounded-lg transition duration-200">
                                            <i class="fas fa-edit mr-1"></i>Revisi
                                        </a>
                                        @elseif($target->isSubmitted() && !$target->isReviewed())
                                        <!-- Edit Submission -->
                                        <a href="{{ route('targets.submissions.edit', $target->id) }}" 
                                           class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition duration-200">
                                            <i class="fas fa-edit mr-1"></i>Edit Submission
                                        </a>
                                        @endif
                                    @else
                                        <!-- Target Tertutup -->
                                        <div class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg">
                                            <i class="fas fa-lock mr-1"></i>Target Tertutup
                                        </div>
                                    @endif
                                </div>

                                <!-- Deadline Warning -->
                                @if($target->deadline && $target->isPending())
                                <div class="text-right">
                                    @if($target->isClosed())
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-lock mr-1"></i>Tertutup
                                    </span>
                                    @elseif($target->isOverdue())
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Terlambat
                                    </span>
                                    @elseif($target->deadline->diffInHours(now()) <= 24)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        <i class="fas fa-clock mr-1"></i>Deadline Mendekati
                                    </span>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-clipboard-list text-4xl mb-4"></i>
                        <p class="text-lg mb-2">Belum ada target mingguan</p>
                        <p class="text-sm">Dosen belum membuat target untuk kelompok Anda</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
