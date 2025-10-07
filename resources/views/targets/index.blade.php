<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Target Mingguan') }}
            </h2>
            @if(in_array(auth()->user()->role, ['dosen', 'admin']))
            <a href="{{ route('targets.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
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

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <form method="GET" action="{{ route('targets.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Class Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                        <select name="class_room_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
                        <select name="week_number" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
                        <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">
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
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $target->title }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($target->description, 50) }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $target->group->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $target->group->classRoom->name }}</div>
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
                                        @else
                                        <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
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
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                            {{ $target->getStatusLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center gap-2">
                                            <!-- View Detail -->
                                            <a href="{{ route('targets.show', $target->id) }}" 
                                               class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded transition duration-200"
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
                            <a href="{{ route('targets.create') }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
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
