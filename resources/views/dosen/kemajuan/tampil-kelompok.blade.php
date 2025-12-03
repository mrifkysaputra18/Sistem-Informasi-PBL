<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('dosen.progress.show-class', $classRoom) }}" 
               class="mr-4 text-white hover:text-gray-200 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ $classRoom->name }} - {{ $group->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Group Header -->
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg shadow-lg p-6 mb-8 text-white">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <h3 class="text-2xl font-bold mb-2">{{ $group->name }}</h3>
                        <p class="text-blue-100">{{ $group->project?->title ?? 'Belum ada project' }}</p>
                        @if($group->leader)
                            <p class="text-blue-200 text-sm mt-1">
                                <i class="fas fa-user-tie mr-1"></i>{{ $group->leader->name }}
                            </p>
                        @endif
                    </div>
                    
                    <div class="text-center">
                        <div class="text-3xl font-bold">{{ $stats['totalTargets'] }}</div>
                        <div class="text-blue-100 text-sm">Total Targets</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-300">{{ $stats['submittedTargets'] }}</div>
                        <div class="text-blue-100 text-sm">Submitted</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-300">{{ $stats['pendingReviews'] }}</div>
                        <div class="text-blue-100 text-sm">Need Review</div>
                    </div>
                </div>
            </div>

            <!-- Google Drive Folder Link -->
            @if($group->google_drive_folder_id)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fab fa-google-drive text-blue-600 text-2xl mr-3"></i>
                        <div>
                            <h3 class="font-semibold text-blue-900">Google Drive Folder</h3>
                            <p class="text-sm text-blue-700">Semua file upload tersimpan di Google Drive</p>
                        </div>
                    </div>
                    <a href="{{ route('dosen.progress.view-folder', [$classRoom, $group]) }}" 
                       target="_blank"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Buka Folder
                    </a>
                </div>
            </div>
            @endif

            <!-- Members Info -->
            <div class="bg-white shadow-md rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Anggota Kelompok</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($group->members as $member)
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                <span class="text-indigo-600 font-medium">
                                    {{ strtoupper(substr($member->user->name, 0, 1)) }}
                                </span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $member->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $member->user->email }}</p>
                            </div>
                            @if($group->leader_id === $member->user_id)
                                <span class="ml-auto text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Leader</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Weekly Targets with Files -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-teal-600">
                    <h3 class="text-lg font-semibold text-white">Target Mingguan & Submission Files</h3>
                </div>
                
                <div class="p-6">
                    @if($targetsWithFiles->count() > 0)
                        <div class="space-y-6">
                            @foreach($targetsWithFiles as $targetWithFiles)
                                @php
                                    $target = $targetWithFiles['target'];
                                    $files = $targetWithFiles['files'];
                                    $submission = $targetWithFiles['submission'];
                                    $review = $targetWithFiles['review'];
                                @endphp
                                
                                <div class="border rounded-lg {{ $target->isClosed() ? 'bg-gray-50' : 'bg-white' }}">
                                    <div class="p-6">
                                        <!-- Target Header -->
                                        <div class="flex justify-between items-start mb-4">
                                            <div class="flex-1">
                                                <h4 class="text-lg font-semibold text-gray-900">
                                                    Minggu {{ $target->week_number }}: {{ $target->title }}
                                                </h4>
                                                <p class="text-sm text-gray-600 mt-1">{{ $target->description }}</p>
                                                @if($target->deadline)
                                                    <p class="text-xs text-gray-500 mt-2">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        Deadline: {{ $target->deadline->format('d/m/Y H:i') }}
                                                        @if($target->isOverdue())
                                                            <span class="text-red-600 font-medium">(OVERDUE)</span>
                                                        @endif
                                                    </p>
                                                @endif
                                            </div>
                                            
                                            <!-- Status Badge -->
                                            <div class="flex flex-col items-end space-y-2">
                                                <span class="px-3 py-1 text-xs font-medium rounded-full
                                                    @switch($target->submission_status)
                                                        @case('pending')
                                                            bg-gray-100 text-gray-800
                                                        @break
                                                        @case('submitted')
                                                            bg-yellow-100 text-yellow-800
                                                        @break
                                                        @case('approved')
                                                            bg-green-100 text-green-800
                                                        @break
                                                        @case('revision')
                                                            bg-orange-100 text-orange-800
                                                        @break
                                                        @default
                                                            bg-gray-100 text-gray-800
                                                    @endswitch
                                                ">
                                                    {{ $target->getStatusLabel() }}
                                                </span>
                                                
                                                @if($target->isClosed() && !$target->is_reviewed)
                                                    <span class="text-xs text-gray-500">
                                                        {{ $target->getClosureReason() }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Submission Info -->
                                        @if($submission)
                                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                                <h5 class="text-sm font-semibold text-green-800 mb-2">Submission Details</h5>
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                                    <div>
                                                        <span class="text-gray-600">Submitted by:</span>
                                                        <span class="font-medium">{{ $submission['submitted_by']->name }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-600">Submitted at:</span>
                                                        <span class="font-medium">{{ $submission['submitted_at']->format('d/m/Y H:i') }}</span>
                                                    </div>
                                                    @if($submission['notes'])
                                                        <div>
                                                            <span class="text-gray-600">Notes:</span>
                                                            <span class="font-medium">{{ $submission['notes'] }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Files Section -->
                                        @if($files && count($files) > 0)
                                            <div class="mb-4">
                                                <div class="flex justify-between items-center mb-3">
                                                    <h5 class="text-sm font-semibold text-gray-700">
                                                        <i class="fas fa-paperclip mr-1"></i>Uploaded Files ({{ count($files) }})
                                                    </h5>
                                                    @if($group->google_drive_folder_id)
                                                        <a href="{{ route('dosen.progress.download-all', [$classRoom, $group, $target->id]) }}" 
                                                           target="_blank"
                                                           class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-2 py-1 rounded flex items-center">
                                                            <i class="fab fa-google-drive mr-1"></i>
                                                            Lihat Semua di Drive
                                                        </a>
                                                    @endif
                                                </div>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                    @foreach($files as $fileIndex => $fileInfo)
                                                        @php
                                                            $fileName = $fileInfo['file_name'] ?? 'File ' . ($fileIndex + 1);
                                                            $isGoogleDrive = ($fileInfo['storage'] ?? '') === 'google_drive' || isset($fileInfo['file_id']);
                                                        @endphp
                                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                                            <div class="flex items-center flex-1 min-w-0">
                                                                @if($isGoogleDrive)
                                                                    <i class="fab fa-google-drive text-blue-500 mr-2 flex-shrink-0"></i>
                                                                @else
                                                                    <i class="fas 
                                                                        @if(str_contains(strtolower($fileName), 'pdf')) fa-file-pdf text-red-500
                                                                        @elseif(str_contains(strtolower($fileName), 'doc') || str_contains(strtolower($fileName), 'docx')) fa-file-word text-blue-500
                                                                        @elseif(str_contains(strtolower($fileName), 'xls') || str_contains(strtolower($fileName), 'xlsx')) fa-file-excel text-green-500
                                                                        @elseif(str_contains(strtolower($fileName), 'ppt') || str_contains(strtolower($fileName), 'pptx')) fa-file-powerpoint text-orange-500
                                                                        @elseif(str_contains(strtolower($fileName), 'jpg') || str_contains(strtolower($fileName), 'jpeg') || str_contains(strtolower($fileName), 'png')) fa-file-image text-purple-500
                                                                        @else fa-file text-gray-500
                                                                        @endif
                                                                     mr-2 flex-shrink-0">
                                                                    </i>
                                                                @endif
                                                                <span class="text-sm text-gray-900 truncate">{{ $fileName }}</span>
                                                                @if(isset($fileInfo['size']))
                                                                    <span class="text-xs text-gray-500 ml-2">({{ number_format($fileInfo['size'] / 1024, 1) }} KB)</span>
                                                                @endif
                                                            </div>
                                                            <div class="flex items-center space-x-2 ml-2 flex-shrink-0">
                                                                <a href="{{ route('dosen.progress.download-file', [$classRoom, $group, $target->id, $fileIndex]) }}" 
                                                                   class="text-indigo-600 hover:text-indigo-800 text-sm"
                                                                   title="Download file">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                                @if(isset($fileInfo['file_url']) || isset($fileInfo['view_url']))
                                                                    <a href="{{ $fileInfo['view_url'] ?? $fileInfo['file_url'] }}" 
                                                                       target="_blank"
                                                                       class="text-blue-600 hover:text-blue-800 text-sm"
                                                                       title="Lihat di Google Drive">
                                                                        <i class="fas fa-external-link-alt"></i>
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @else
                                            <div class="bg-gray-50 rounded-lg p-4 text-center text-gray-500 text-sm mb-4">
                                                <i class="fas fa-paperclip mr-1"></i> Belum ada file yang diupload
                                            </div>
                                        @endif

                                        <!-- Review Section -->
                                        @if($review)
                                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                                <h5 class="text-sm font-semibold text-yellow-800 mb-2">Review oleh {{ $review->reviewer->name }}</h5>
                                                <div class="text-sm text-gray-700">
                                                    <p class="mb-2">{{ $review->feedback }}</p>
                                                    <div class="flex justify-between items-center text-xs text-gray-500">
                                                        <span>Reviewed at: {{ $review->created_at->format('d/m/Y H:i') }}</span>
                                                        <span>Status: 
                                                            <span class="font-medium text-yellow-700">
                                                                {{ $review->status === 'approved' ? 'Approved' : 'Need Revision' }}
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Actions -->
                                        <div class="mt-4 pt-4 border-t flex justify-between items-center">
                                            <span class="text-xs text-gray-500">
                                                Created by {{ $target->creator->name }} at {{ $target->created_at->format('d/m/Y H:i') }}
                                            </span>
                                            @if($target->canAcceptSubmission())
                                                <div class="space-x-2">
                                                    <!-- Can review actions here -->
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-tasks text-gray-400 text-5xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Target</h3>
                            <p class="text-gray-600">Kelompok ini belum memiliki target mingguan yang ditetapkan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
