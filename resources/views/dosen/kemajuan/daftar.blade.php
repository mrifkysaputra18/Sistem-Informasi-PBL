<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Monitoring Progress Kelas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Overview Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-full">
                            <i class="fas fa-chalkboard-teacher text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-blue-100 text-sm">Kelas Diampu</p>
                            <p class="text-white text-2xl font-bold">{{ $stats['totalClassRooms'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-full">
                            <i class="fas fa-users text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-purple-100 text-sm">Total Kelompok</p>
                            <p class="text-white text-2xl font-bold">{{ $stats['totalGroups'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-full">
                            <i class="fas fa-user-graduate text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-green-100 text-sm">Total Mahasiswa</p>
                            <p class="text-white text-2xl font-bold">{{ $stats['totalStudents'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg shadow-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-orange-100 rounded-full">
                            <i class="fas fa-clipboard-list text-orange-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-orange-100 text-sm">Perlu Review</p>
                            <p class="text-white text-2xl font-bold">{{ $stats['pendingReviews'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Classroom List -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600">
                    <h3 class="text-lg font-semibold text-white">Daftar Kelas Anda</h3>
                </div>
                
                <div class="p-6">
                    @if($classRooms->count() > 0)
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            @foreach($classRooms as $classRoom)
                                <div class="border rounded-lg hover:shadow-lg transition-shadow duration-200">
                                    <div class="p-6">
                                        <!-- Header -->
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h4 class="text-lg font-semibold text-gray-900">{{ $classRoom->name }}</h4>
                                                <p class="text-sm text-gray-600">{{ $classRoom->subject->name ?? 'Tidak ada subject' }}</p>
                                                <p class="text-xs text-gray-500 mt-1">{{ $classRoom->academicPeriod->name ?? 'Tidak ada periode' }}</p>
                                            </div>
                                            <a href="{{ route('dosen.progress.show-class', $classRoom) }}" 
                                               class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition-colors">
                                                <i class="fas fa-eye mr-1"></i>
                                                Lihat Detail
                                            </a>
                                        </div>

                                        <!-- Stats -->
                                        <div class="grid grid-cols-3 gap-4 text-center">
                                            <div class="bg-gray-50 rounded-lg p-3">
                                                <div class="text-2xl font-bold text-indigo-600">{{ $classRoom->groups_count }}</div>
                                                <div class="text-xs text-gray-600">Kelompok</div>
                                            </div>
                                            <div class="bg-gray-50 rounded-lg p-3">
                                                <div class="text-2xl font-bold text-green-600">{{ $classRoom->members_total }}</div>
                                                <div class="text-xs text-gray-600">Mahasiswa</div>
                                            </div>
                                            <div class="bg-gray-50 rounded-lg p-3">
                                                <div class="text-2xl font-bold text-purple-600">
                                                    {{-- Target submission rate --}}
                                                    @php
                                                        $targets = \App\Models\WeeklyTarget::whereHas('group', function($query) use ($classRoom) {
                                                            $query->where('class_room_id', $classRoom->id);
                                                        })->get();
                                                        $submissionRate = $targets->count() > 0 
                                                            ? round(($targets->where('submission_status', 'approved')->count() / $targets->count()) * 100)
                                                            : 0;
                                                    @endphp
                                                    {{ $submissionRate }}%
                                                </div>
                                                <div class="text-xs text-gray-600">Completion</div>
                                            </div>
                                        </div>

                                        <!-- Quick Actions -->
                                        <div class="mt-4 pt-4 border-t border-gray-200">
                                            <div class="flex justify-between items-center text-sm">
                                                <span class="text-gray-600">
                                                    Latest: {{ $classRoom->updated_at->diffForHumans() }}
                                                </span>
                                                <div class="space-x-2">
                                                    <a href="#" 
                                                       class="text-indigo-600 hover:text-indigo-800"
                                                       onclick="showGroupsModal({{ $classRoom->id }})">
                                                        <i class="fas fa-layer-group mr-1"></i>Lihat Kelompok
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-chalkboard-teacher text-gray-400 text-5xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Kelas</h3>
                            <p class="text-gray-600 mb-4">Anda belum ditugaskan untuk kelas manapun.</p>
                            <p class="text-sm text-gray-500">Hubungi administrator untuk penugasan kelas.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Groups Modal -->
    <div id="groupsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-lg bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Kelompok</h3>
                    <button onclick="closeGroupsModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="groupsContent" class="space-y-2">
                    <!-- Load groups via AJAX -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function showGroupsModal(classRoomId) {
            const modal = document.getElementById('groupsModal');
            const content = document.getElementById('groupsContent');
            
            // Show loading
            content.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin mr-2"></i>Loading...</div>';
            modal.classList.remove('hidden');
            
            // Load groups via AJAX
            fetch(`/dosen/api/classroom/${classRoomId}/groups`)
                .then(response => {
                    if (!response.ok) throw new Error('Failed to load');
                    return response.json();
                })
                .then(groups => {
                    if (groups.length === 0) {
                        content.innerHTML = '<p class="text-gray-500 text-center py-4">Belum ada kelompok di kelas ini.</p>';
                        return;
                    }
                    
                    content.innerHTML = groups.map(group => `
                        <div class="border rounded-lg p-3 hover:bg-gray-50">
                            <div class="flex justify-between items-center">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">${group.name}</h4>
                                    <p class="text-sm text-gray-600">${group.project_title}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Leader: ${group.leader} • ${group.members_count} anggota
                                    </p>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-indigo-600">${group.completed_count}/${group.targets_count}</div>
                                    <div class="text-xs text-gray-500">Complete</div>
                                </div>
                            </div>
                        </div>
                    `).join('');
                })
                .catch(error => {
                    content.innerHTML = '<p class="text-red-500 text-center py-4">Gagal memuat data kelompok.</p>';
                    console.error('Error:', error);
                });
        }
        
        function closeGroupsModal() {
            document.getElementById('groupsModal').classList.add('hidden');
        }
        
        // Close modal when clicking outside
        document.getElementById('groupsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeGroupsModal();
            }
        });
    </script>
</x-app-layout>
