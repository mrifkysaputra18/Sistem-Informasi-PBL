<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('classrooms.index') }}" 
               class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Kelas') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('classrooms.update', $classRoom) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Kelas <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" required
                                value="{{ old('name', $classRoom->name) }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                                Kode Kelas <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="code" id="code" required
                                value="{{ old('code', $classRoom->code) }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('code') border-red-500 @enderror">
                            @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Mata Kuliah
                            </label>
                            <select name="subject_id" id="subject_id"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('subject_id') border-red-500 @enderror">
                                <option value="">-- Pilih Mata Kuliah (Opsional) --</option>
                                @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id', $classRoom->subject_id) == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->code }} - {{ $subject->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Pilih mata kuliah yang terkait dengan kelas ini
                            </p>
                        </div>

                        <div class="mb-4">
                            <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">
                                Semester <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="semester" id="semester" required
                                value="{{ old('semester', $classRoom->semester) }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('semester') border-red-500 @enderror">
                            @error('semester')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="program_studi" class="block text-sm font-medium text-gray-700 mb-2">
                                Program Studi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="program_studi" id="program_studi" required
                                value="{{ old('program_studi', $classRoom->program_studi) }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('program_studi') border-red-500 @enderror">
                            @error('program_studi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="max_groups" class="block text-sm font-medium text-gray-700 mb-2">
                                Maksimal Kelompok <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="max_groups" id="max_groups" required
                                value="{{ old('max_groups', $classRoom->max_groups) }}"
                                min="1" max="10"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('max_groups') border-red-500 @enderror">
                            @error('max_groups')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', $classRoom->is_active) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">
                                    Kelas Aktif
                                </span>
                            </label>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('classrooms.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                                Batal
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                Update Kelas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
