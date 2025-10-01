<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Kelompok Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('groups.store') }}">
                        @csrf

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
                                    {{ old('class_room_id', request('class_room_id')) == $classRoom->id ? 'selected' : '' }}
                                    {{ !$classRoom->canAddGroup() ? 'disabled' : '' }}>
                                    {{ $classRoom->name }} 
                                    ({{ $classRoom->groups->count() }}/{{ $classRoom->max_groups }} kelompok)
                                    {{ !$classRoom->canAddGroup() ? '- PENUH' : '' }}
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
                                value="{{ old('name') }}"
                                placeholder="Contoh: Kelompok 1, Tim A, dll"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Maksimal Anggota -->
                        <div class="mb-4">
                            <label for="max_members" class="block text-sm font-medium text-gray-700 mb-2">
                                Maksimal Anggota
                            </label>
                            <input type="number" name="max_members" id="max_members" 
                                value="{{ old('max_members', 5) }}"
                                min="1" max="10"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('max_members') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Maksimal anggota yang bisa bergabung (default: 5)</p>
                            @error('max_members')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Project (Optional) -->
                        @if(isset($projects) && $projects->count() > 0)
                        <div class="mb-6">
                            <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Project (Opsional)
                            </label>
                            <select name="project_id" id="project_id"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Tidak Ada Project --</option>
                                @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->title }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Buttons -->
                        <div class="flex gap-2">
                            <a href="{{ route('classrooms.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                                Batal
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                Buat Kelompok
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-semibold text-blue-800 mb-2">ℹ️ Informasi</h4>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li>• Setelah kelompok dibuat, Anda bisa menambahkan anggota maksimal 5 orang</li>
                    <li>• Salah satu anggota harus ditunjuk sebagai ketua kelompok</li>
                    <li>• Setiap kelas maksimal memiliki 5 kelompok</li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>