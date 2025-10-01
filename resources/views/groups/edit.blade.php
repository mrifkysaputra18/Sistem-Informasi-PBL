<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('groups.index') }}" 
               class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
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
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('class_room_id') border-red-500 @enderror">
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
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
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
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('max_members') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Maksimal anggota yang bisa bergabung</p>
                            @error('max_members')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kelola Anggota -->
                        <div class="mb-6">
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
                                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
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
                                            class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">-- Pilih Mahasiswa --</option>
                                        @foreach($availableStudents as $student)
                                        <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                                        @endforeach
                                    </select>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="is_leader" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('groups.index') }}" 
                               class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200">
                                <i class="fas fa-save mr-2"></i>Update Kelompok
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>