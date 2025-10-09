<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('groups.index') }}" 
               class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Edit Kelompok') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8">
                    <form action="{{ route('groups.update', $group) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <!-- Pilih Kelas -->
                        <div class="mb-4">
                            <label for="class_room_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Kelas <span class="text-red-500">*</span>
                            </label>
                            <select name="class_room_id" id="class_room_id" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('class_room_id') border-red-500 @enderror">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($classRooms as $classRoom)
                                <option value="{{ $classRoom->id }}" 
                                    {{ old('class_room_id', $group->class_room_id) == $classRoom->id ? 'selected' : '' }}>
                                    {{ $classRoom->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('class_room_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama Kelompok -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Kelompok <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" required
                                value="{{ old('name', $group->name) }}"
                                placeholder="Contoh: Kelompok 1, Tim A, dll"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Maksimal Anggota -->
                        <div class="mb-6">
                            <label for="max_members" class="block text-sm font-medium text-gray-700 mb-2">
                                Maksimal Anggota
                            </label>
                            <input type="number" name="max_members" id="max_members" 
                                value="{{ old('max_members', $group->max_members) }}"
                                min="1" max="10"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('max_members') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Maksimal anggota yang bisa bergabung</p>
                            @error('max_members')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kelola Anggota -->
                        @if(auth()->user()->isKoordinator() || auth()->user()->isAdmin())
                            <!-- Only Koordinator & Admin can manage members -->
                            <div class="mb-6">
                                <div class="bg-secondary-50 border-l-4 border-secondary-500 p-4 mb-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-user-shield text-secondary-600"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-secondary-700">
                                                <strong>Koordinator/Admin:</strong> Anda dapat mengelola anggota kelompok
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Kelola Anggota Kelompok</h3>
                                
                                <!-- Daftar Anggota Saat Ini -->
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Anggota Saat Ini ({{ $group->members->count() }}/{{ $group->max_members }})</h4>
                                    @if($group->members->count() > 0)
                                    <div class="space-y-2">
                                        @foreach($group->members as $member)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                                        {{ substr($member->user->name, 0, 1) }}
                                                    </div>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $member->user->name }}
                                                        @if($member->is_leader)
                                                        <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            Ketua
                                                        </span>
                                                        @endif
                                                    </p>
                                                    <p class="text-xs text-gray-500">{{ $member->user->email }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                @if(!$member->is_leader)
                                                <form action="{{ route('groups.set-leader', $group) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="member_id" value="{{ $member->id }}">
                                                    <button type="submit" 
                                                            class="text-xs bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded"
                                                            onclick="return confirm('Jadikan {{ $member->user->name }} sebagai ketua?')">
                                                        Jadikan Ketua
                                                    </button>
                                                </form>
                                                @endif
                                                <form action="{{ route('groups.remove-member', [$group, $member->id]) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-xs bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded"
                                                            onclick="return confirm('Hapus {{ $member->user->name }} dari kelompok?')">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <div class="text-center py-4 text-gray-500">
                                        <p class="text-sm">Belum ada anggota di kelompok ini.</p>
                                    </div>
                                    @endif
                                </div>

                                <!-- Tambah Anggota Baru -->
                                @if($group->members->count() < $group->max_members)
                                <div class="border-t pt-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Tambah Anggota Baru</h4>
                                    <form action="{{ route('groups.add-member', $group) }}" method="POST" class="flex gap-2">
                                        @csrf
                                        <select name="user_id" required
                                                class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500">
                                            <option value="">-- Pilih Mahasiswa --</option>
                                            @foreach($availableStudents as $student)
                                            <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                                            @endforeach
                                        </select>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="is_leader" value="1" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-gray-700">Jadikan Ketua</span>
                                        </label>
                                        <button type="submit" 
                                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm">
                                            Tambah
                                        </button>
                                    </form>
                                </div>
                                @else
                                <div class="border-t pt-4">
                                    <div class="text-center py-4 text-gray-500">
                                        <p class="text-sm">Kelompok sudah mencapai maksimal anggota.</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        @else
                            <!-- Dosen: View-only member list -->
                            <div class="mb-6">
                                <div class="bg-primary-50 border-l-4 border-primary-500 p-4 mb-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-info-circle text-primary-600"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-primary-700">
                                                <strong>Info:</strong> Hanya koordinator dan admin yang dapat mengelola anggota kelompok
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Anggota Kelompok (View Only)</h3>
                                
                                <!-- Daftar Anggota Saat Ini (Read-only) -->
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Anggota Saat Ini ({{ $group->members->count() }}/{{ $group->max_members }})</h4>
                                    @if($group->members->count() > 0)
                                    <div class="space-y-2">
                                        @foreach($group->members as $member)
                                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                                    {{ substr($member->user->name, 0, 1) }}
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $member->user->name }}
                                                    @if($member->is_leader)
                                                    <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        <i class="fas fa-star mr-1"></i>Ketua
                                                    </span>
                                                    @endif
                                                </p>
                                                <p class="text-xs text-gray-500">{{ $member->user->email }}</p>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <div class="text-center py-4 text-gray-500">
                                        <p class="text-sm">Belum ada anggota di kelompok ini.</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Weekly Targets Section -->
                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">
                                    <i class="fas fa-tasks mr-2 text-primary-600"></i>
                                    Target Mingguan
                                </h3>
                                @if(auth()->user()->isMahasiswa())
                                    <a href="{{ route('groups.targets.create', $group) }}" 
                                       class="bg-primary-500 hover:bg-primary-600 text-white text-sm font-bold py-2 px-4 rounded">
                                        <i class="fas fa-plus mr-1"></i>Tambah Target
                                    </a>
                                @endif
                            </div>

                            @if($group->weeklyTargets->count() > 0)
                                <div class="space-y-3">
                                    @foreach($group->weeklyTargets->sortBy('week_number') as $target)
                                    <div class="p-4 border rounded-lg {{ $target->is_completed ? 'bg-green-50 border-green-200' : 'bg-white border-gray-200' }}">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-start flex-1">
                                                <div class="mt-1 mr-3">
                                                    @if($target->is_completed)
                                                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                                    @else
                                                    <i class="far fa-circle text-gray-400 text-xl"></i>
                                                    @endif
                                                </div>
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                                            Minggu {{ $target->week_number }}
                                                        </span>
                                                        @if($target->is_completed)
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <i class="fas fa-check mr-1"></i>Selesai
                                                        </span>
                                                        @endif
                                                    </div>
                                                    <p class="font-medium text-gray-900 {{ $target->is_completed ? 'line-through text-gray-500' : '' }}">
                                                        {{ $target->title }}
                                                    </p>
                                                    @if($target->description)
                                                    <p class="text-sm text-gray-600 mt-1">{{ $target->description }}</p>
                                                    @endif
                                                    @if($target->is_completed && $target->completed_at)
                                                    <p class="text-xs text-gray-500 mt-2">
                                                        <i class="fas fa-check mr-1"></i>
                                                        Diselesaikan oleh {{ $target->completedByUser->name ?? 'Unknown' }} 
                                                        pada {{ $target->completed_at->format('d M Y, H:i') }}
                                                    </p>
                                                    @endif
                                                    @if($target->evidence_file)
                                                    <p class="text-xs text-primary-600 mt-1">
                                                        <i class="fas fa-paperclip mr-1"></i>
                                                        <a href="{{ asset('storage/' . $target->evidence_file) }}" target="_blank" class="hover:underline">
                                                            Lihat Bukti
                                                        </a>
                                                    </p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2 ml-4">
                                                @if(auth()->user()->isMahasiswa())
                                                    @if(!$target->is_completed)
                                                    <form action="{{ route('targets.complete', $target) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="text-xs bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded"
                                                                title="Tandai Selesai">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    @else
                                                    <form action="{{ route('targets.uncomplete', $target) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="text-xs bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded"
                                                                title="Batal Selesai">
                                                            <i class="fas fa-undo"></i>
                                                        </button>
                                                    </form>
                                                    @endif
                                                    <a href="{{ route('groups.targets.edit', [$group, $target]) }}" 
                                                       class="text-xs bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded"
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('groups.targets.destroy', [$group, $target]) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="text-xs bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded"
                                                                onclick="return confirm('Hapus target ini?')"
                                                                title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <!-- Read-only view for non-mahasiswa -->
                                                    <span class="text-xs text-gray-500 px-3 py-1 rounded bg-gray-100">
                                                        <i class="fas fa-eye mr-1"></i>View Only
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <!-- Completion Stats -->
                                <div class="mt-4 p-4 bg-primary-50 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-700">Tingkat Penyelesaian</span>
                                        @php
                                            $totalTargets = $group->weeklyTargets->count();
                                            $completedTargets = $group->weeklyTargets->where('is_completed', true)->count();
                                            $completionRate = $totalTargets > 0 ? ($completedTargets / $totalTargets) * 100 : 0;
                                        @endphp
                                        <span class="text-sm font-bold text-gray-900">{{ round($completionRate, 1) }}%</span>
                                    </div>
                                    @php
                                        $progressWidth = min(100, max(0, round($completionRate, 1)));
                                        $widthStyle = 'width: ' . $progressWidth . '%';
                                    @endphp
                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                        <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-3 rounded-full transition-all duration-500" 
                                             style="{{ $widthStyle }}">
                                        </div>
                                    </div>
                                    <div class="mt-2 text-xs text-gray-600">
                                        {{ $completedTargets }} dari {{ $totalTargets }} target selesai
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-8 bg-gray-50 rounded-lg">
                                    <div class="text-gray-400 mb-3">
                                        <i class="fas fa-tasks text-4xl"></i>
                                    </div>
                                    <p class="text-gray-600 mb-3">Belum ada target mingguan</p>
                                    @if(auth()->user()->isMahasiswa())
                                        <a href="{{ route('groups.targets.create', $group) }}" 
                                           class="inline-flex items-center bg-primary-500 hover:bg-primary-600 text-white text-sm px-4 py-2 rounded">
                                            <i class="fas fa-plus mr-2"></i>Tambah Target Pertama
                                        </a>
                                    @else
                                        <p class="text-sm text-gray-500">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Hanya mahasiswa yang dapat menambah target mingguan
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('groups.index') }}" 
                               class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-200">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-200">
                                <i class="fas fa-save mr-2"></i>Update Kelompok
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>