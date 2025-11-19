<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('dosen.progress.index') }}" 
               class="mr-4 text-white hover:text-gray-200 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                Monitoring: {{ $classRoom->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Class Info & Stats -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg shadow-lg p-6 mb-8 text-white">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-1">{{ $classRoom->name }}</h3>
                        <p class="text-indigo-100 text-sm">{{ $classRoom->program_studi }} • Semester {{ $classRoom->semester }}</p>
                        <p class="text-indigo-200 text-xs">{{ $classRoom->academicPeriod->name ?? 'Tidak ada periode' }}</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-3xl font-bold">{{ $stats['totalGroups'] }}</div>
                        <div class="text-indigo-100 text-sm">Kelompok</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-3xl font-bold">{{ $stats['totalStudents'] }}</div>
                        <div class="text-indigo-100 text-sm">Mahasiswa</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-3xl font-bold">{{ $stats['completionRate'] }}%</div>
                        <div class="text-indigo-100 text-sm">Completion</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-300">{{ $stats['pendingReviews'] }}</div>
                        <div class="text-indigo-100 text-sm">Perlu Review</div>
                    </div>
                </div>
            </div>

            <!-- Groups Grid -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Kelompok</h3>
                        <div class="text-sm text-gray-600">
                            Total: {{ $classRoom->groups->count() }} kelompok
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        @foreach($classRoom->groups as $group)
                            <div class="border rounded-lg hover:shadow-lg transition-shadow duration-200">
                                <div class="p-6">
                                    <!-- Group Header -->
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex-1">
                                            <h4 class="text-lg font-semibold text-gray-900">
                                                {{ $group->name }}
                                                @if($group->leader)
                                                    <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                                        Leader: {{ $group->leader->name }}
                                                    </span>
                                                @endif
                                            </h4>
                                            <p class="text-sm text-gray-600">{{ $group->project?->title ?? 'Belum ada project' }}</p>
                                        </div>
                                        <a href="{{ route('dosen.progress.show-group', [$classRoom, $group]) }}" 
                                           class="px-3 py-1 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-700 transition-colors">
                                            <i class="fas fa-folder-open mr-1"></i>
                                            Detail
                                        </a>
                                    </div>

                                    <!-- Members -->
                                    <div class="mb-4">
                                        <p class="text-xs font-medium text-gray-700 mb-2">Anggota ({{ $group->members->count() }}):</p>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($group->members->take(4) as $member)
                                                <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">
                                                    {{ $member->user->name }}
                                                </span>
                                            @endforeach
                                            @if($group->members->count() > 4)
                                                <span class="text-xs text-gray-500">+{{ $group->members->count() - 4 }} lainnya</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Target Progress -->
                                    <div class="border-t pt-4">
                                        <div class="flex justify-between items-center mb-2">
                                            <p class="text-sm font-medium text-gray-700">Progress Target</p>
                                            <p class="text-xs text-gray-500">
                                                {{ $group->weeklyTargets->where('is_completed', true)->count() }} / {{ $group->weeklyTargets->count() }}
                                            </p>
                                        </div>
                                        
                                        @php
                                            $completedCount = $group->weeklyTargets->where('is_completed', true)->count();
                                            $totalCount = $group->weeklyTargets->count();
                                            $percentage = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
                                        @endphp
                                        
                                        <!-- Progress Bar -->
                                        <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                            <div class="bg-green-500 h-2 rounded-full transition-all duration-300" 
                                                 style="width: {{ $percentage }}%"></div>
                                        </div>

                                        <!-- Target Status Distribution -->
                                        <div class="grid grid-cols-4 gap-2 text-xs mt-3">
                                            <div class="text-center">
                                                <div class="font-medium text-blue-600">
                                                    {{ $group->weeklyTargets->where('submission_status', 'pending')->count() }}
                                                </div>
                                                <div class="text-gray-500">Pending</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="font-medium text-yellow-600">
                                                    {{ $group->weeklyTargets->where('submission_status', 'submitted')->count() }}
                                                </div>
                                                <div class="text-gray-500">Submitted</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="font-medium text-green-600">
                                                    {{ $group->weeklyTargets->where('submission_status', 'approved')->count() }}
                                                </div>
                                                <div class="text-gray-500">Approved</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="font-medium text-orange-600">
                                                    {{ $group->weeklyTargets->where('submission_status', 'revision')->count() }}
                                                </div>
                                                <div class="text-gray-500">Revision</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Quick Actions -->
                                    <div class="mt-4 pt-4 border-t flex justify-between items-center">
                                        <span class="text-xs text-gray-500">
                                            Updated: {{ $group->updated_at->diffForHumans() }}
                                        </span>
                                        <div class="space-x-2">
                                            @if($group->weeklyTargets->whereIn('submission_status', ['submitted', 'revision'])->count() > 0)
                                                <a href="{{ route('dosen.progress.show-group', [$classRoom, $group]) }}#reviews" 
                                                   class="text-orange-600 hover:text-orange-800 text-sm">
                                                    <i class="fas fa-clipboard-check mr-1"></i>Review
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($classRoom->groups->count() === 0)
                        <div class="text-center py-12">
                            <i class="fas fa-layer-group text-gray-400 text-5xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Kelompok</h3>
                            <p class="text-gray-600">Kelas ini belum memiliki kelompok yang ditugaskan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
