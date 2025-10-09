<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Kelola Target Mingguan') }}
            </h2>
            @if(in_array(auth()->user()->role, ['dosen', 'admin']))
            <a href="{{ route('targets.create') }}" class="bg-primary-500 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-plus mr-2"></i>Buat Target Baru
            </a>
            @endif
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

            <!-- Submission Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <!-- Total Target -->
                <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-gray-400">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Total Target</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                        </div>
                        <div class="bg-gray-100 rounded-full p-3">
                            <i class="fas fa-clipboard-list text-2xl text-gray-600"></i>
                        </div>
                    </div>
                </div>

                <!-- Sudah Submit -->
                <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-primary-400">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Sudah Submit</p>
                            <p class="text-2xl font-bold text-primary-800">{{ $stats['submitted'] }}</p>
                        </div>
                        <div class="bg-primary-100 rounded-full p-3">
                            <i class="fas fa-check-circle text-2xl text-primary-600"></i>
                        </div>
                    </div>
                </div>

                <!-- Disetujui -->
                <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-400">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Disetujui</p>
                            <p class="text-2xl font-bold text-green-800">{{ $stats['approved'] }}</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-3">
                            <i class="fas fa-check-double text-2xl text-green-600"></i>
                        </div>
                    </div>
                </div>

                <!-- Perlu Revisi -->
                <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-yellow-400">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Perlu Revisi</p>
                            <p class="text-2xl font-bold text-yellow-800">{{ $stats['revision'] }}</p>
                        </div>
                        <div class="bg-yellow-100 rounded-full p-3">
                            <i class="fas fa-edit text-2xl text-yellow-600"></i>
                        </div>
                    </div>
                </div>

                <!-- Belum Submit -->
                <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-red-400">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Belum Submit</p>
                            <p class="text-2xl font-bold text-red-800">{{ $stats['pending'] + $stats['late'] }}</p>
                        </div>
                        <div class="bg-red-100 rounded-full p-3">
                            <i class="fas fa-hourglass-half text-2xl text-red-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-semibold text-gray-700">Progress Submission</h3>
                    <span class="text-sm font-bold text-gray-700">{{ $stats['submitted_percentage'] }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="bg-gradient-to-r from-blue-500 to-green-500 h-4 rounded-full transition-all duration-500" 
                         style="width: {{ $stats['submitted_percentage'] }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-2">
                    {{ $stats['submitted'] + $stats['approved'] + $stats['revision'] }} dari {{ $stats['total'] }} kelompok sudah submit target
                </p>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <form method="GET" action="{{ route('targets.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Class Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                        <select name="class_room_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500">
                            <option value="">Semua Kelas</option>
                            @foreach($classRooms as $classRoom)
                            <option value="{{ $classRoom->id }}" {{ request('class_room_id') == $classRoom->id ? 'selected' : '' }}>
                                {{ $classRoom->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Week Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Minggu</label>
                        <select name="week_number" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500">
                            <option value="">Semua Minggu</option>
                            @for($i = 1; $i <= 16; $i++)
                            <option value="{{ $i }}" {{ request('week_number') == $i ? 'selected' : '' }}>
                                Minggu {{ $i }}
                            </option>
                            @endfor
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Belum Dikerjakan</option>
                            <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Sudah Submit</option>
                            <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Terlambat</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                            <option value="revision" {{ request('status') == 'revision' ? 'selected' : '' }}>Perlu Revisi</option>
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-primary-500 hover:bg-primary-600 text-white py-2 px-4 rounded-md">
                            <i class="fas fa-search mr-2"></i>Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Targets List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Daftar Target Mingguan</h3>
                    
                    @if($targets->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelompok</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Minggu</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deadline</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($targets as $target)
                                @php
                                    // Highlight row untuk yang sudah submit
                                    $rowClass = match($target->submission_status) {
                                        'submitted' => 'bg-primary-50 hover:bg-primary-100 border-l-4 border-primary-500',
                                        'approved' => 'bg-green-50 hover:bg-green-100 border-l-4 border-green-500',
                                        'revision' => 'bg-yellow-50 hover:bg-yellow-100 border-l-4 border-yellow-500',
                                        'late' => 'bg-orange-50 hover:bg-orange-100 border-l-4 border-orange-500',
                                        default => 'hover:bg-gray-50',
                                    };
                                @endphp
                                <tr class="{{ $rowClass }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if(in_array($target->submission_status, ['submitted', 'approved', 'revision']))
                                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                            @elseif($target->submission_status === 'late')
                                                <i class="fas fa-exclamation-triangle text-orange-500 mr-2"></i>
                                            @else
                                                <i class="fas fa-clock text-gray-400 mr-2"></i>
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $target->title }}</div>
                                                <div class="text-sm text-gray-500">{{ Str::limit($target->description, 50) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $target->group->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $target->group->classRoom->name }}</div>
                                            @if($target->completedByUser)
                                                <div class="text-xs text-primary-600 mt-1">
                                                    <i class="fas fa-user text-xs"></i> Submit oleh: {{ $target->completedByUser->name }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        Minggu {{ $target->week_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($target->deadline)
                                        <div class="text-sm text-gray-900">
                                            {{ $target->deadline->format('d/m/Y') }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $target->deadline->format('H:i') }}
                                        </div>
                                        @if($target->isClosed())
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-lock mr-1"></i>Tertutup
                                            </span>
                                        </div>
                                        @elseif($target->isOverdue() && !$target->isSubmitted())
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>Lewat Deadline
                                            </span>
                                        </div>
                                        @endif
                                        @else
                                        <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
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
                                        <div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                                {{ $target->getStatusLabel() }}
                                            </span>
                                            @if($target->completed_at && in_array($target->submission_status, ['submitted', 'approved', 'revision']))
                                                <div class="text-xs text-gray-500 mt-1">
                                                    <i class="fas fa-clock text-xs"></i> {{ $target->completed_at->format('d/m/Y H:i') }}
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    {{ $target->completed_at->diffForHumans() }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center gap-2">
                                            <!-- View Detail -->
                                            <a href="{{ route('targets.show', $target->id) }}" 
                                               class="inline-flex items-center px-3 py-1.5 bg-primary-100 text-primary-700 hover:bg-primary-200 rounded transition duration-200"
                                               title="Lihat Detail">
                                                <i class="fas fa-eye mr-1"></i>
                                                <span class="hidden sm:inline">Detail</span>
                                            </a>
                                            
                                            @if(in_array(auth()->user()->role, ['dosen', 'admin']))
                                                @if(($target->created_by === auth()->id() || auth()->user()->isAdmin()) && $target->canBeModified())
                                                    <!-- Edit -->
                                                    <a href="{{ route('targets.edit', $target->id) }}" 
                                                       class="inline-flex items-center px-3 py-1.5 bg-yellow-100 text-yellow-700 hover:bg-yellow-200 rounded transition duration-200"
                                                       title="Edit Target">
                                                        <i class="fas fa-edit mr-1"></i>
                                                        <span class="hidden sm:inline">Edit</span>
                                                    </a>
                                                    
                                                    <!-- Delete -->
                                                    <form action="{{ route('targets.destroy', $target->id) }}" 
                                                          method="POST" 
                                                          class="inline"
                                                          onsubmit="return confirm('Yakin ingin menghapus target ini?\n\nTarget: {{ $target->title }}\nKelompok: {{ $target->group->name }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 hover:bg-red-200 rounded transition duration-200"
                                                                title="Hapus Target">
                                                            <i class="fas fa-trash mr-1"></i>
                                                            <span class="hidden sm:inline">Hapus</span>
                                                        </button>
                                                    </form>
                                                @elseif(($target->created_by === auth()->id() || auth()->user()->isAdmin()) && !$target->canBeModified())
                                                    <!-- Locked indicator (only show to creator/admin) -->
                                                    <span class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-500 rounded text-xs" title="Target sudah direview/disubmit, tidak bisa diedit">
                                                        <i class="fas fa-lock mr-1"></i>
                                                        <span class="hidden sm:inline">Terkunci</span>
                                                    </span>
                                                @endif
                                            @endif
                                            
                                            <!-- Review -->
                                            @if($target->isSubmitted() && !$target->isReviewed())
                                            <a href="{{ route('targets.review', $target->id) }}" 
                                               class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 hover:bg-green-200 rounded transition duration-200"
                                               title="Review Submission">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                <span class="hidden sm:inline">Review</span>
                                            </a>
                                            @endif
                                            
                                            <!-- Reopen/Close Target (Only for dosen/admin and not reviewed yet) -->
                                            @if(in_array(auth()->user()->role, ['dosen', 'admin', 'koordinator']) && !$target->isReviewed())
                                                @if($target->isClosed())
                                                    <!-- Reopen Button -->
                                                    <form action="{{ route('targets.reopen', $target->id) }}" 
                                                          method="POST" 
                                                          class="inline"
                                                          onsubmit="return confirm('Yakin ingin membuka kembali target ini?\n\nMahasiswa akan dapat mensubmit target yang sudah tertutup.')">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="inline-flex items-center px-3 py-1.5 bg-secondary-100 text-secondary-700 hover:bg-secondary-200 rounded transition duration-200"
                                                                title="Buka Kembali Target">
                                                            <i class="fas fa-unlock mr-1"></i>
                                                            <span class="hidden sm:inline">Buka</span>
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
                                                                class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded transition duration-200"
                                                                title="Tutup Target">
                                                            <i class="fas fa-lock mr-1"></i>
                                                            <span class="hidden sm:inline">Tutup</span>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($targets->hasPages())
                    <div class="mt-6">
                        {{ $targets->links() }}
                    </div>
                    @endif
                    @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-clipboard-list text-4xl mb-4"></i>
                        <p class="text-lg mb-2">Belum ada target mingguan</p>
                        @if(in_array(auth()->user()->role, ['dosen', 'admin']))
                            <p class="text-sm mb-4">Silakan buat target mingguan untuk kelompok</p>
                            <a href="{{ route('targets.create') }}" class="inline-block bg-primary-500 hover:bg-primary-700 text-white font-bold py-2 px-6 rounded">
                                <i class="fas fa-plus mr-2"></i>Buat Target Pertama
                            </a>
                        @else
                            <p class="text-sm">Target mingguan akan dibuat oleh dosen</p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
