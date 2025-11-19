<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('classrooms.index') }}" 
               class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Edit Kelas') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ url('/classrooms/' . $classRoom->id) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Kelas <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" required
                                value="{{ old('name', $classRoom->name) }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
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
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('code') border-red-500 @enderror">
                            @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">
                                Semester <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="semester" id="semester" required
                                value="{{ old('semester', $classRoom->semester) }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('semester') border-red-500 @enderror">
                            @error('semester')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="academic_period_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Periode Akademik
                            </label>
                            <select name="academic_period_id" id="academic_period_id"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('academic_period_id') border-red-500 @enderror">
                                <option value="">-- Auto (berdasarkan semester) --</option>
                                @foreach($academicPeriods as $period)
                                    <option value="{{ $period->id }}" 
                                        {{ old('academic_period_id', $classRoom->academic_period_id) == $period->id ? 'selected' : '' }}>
                                        {{ $period->name }} (Semester {{ $period->semester_number }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Kosongkan untuk auto-link berdasarkan semester</p>
                            @error('academic_period_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="program_studi" class="block text-sm font-medium text-gray-700 mb-2">
                                Program Studi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="program_studi" id="program_studi" required
                                value="{{ old('program_studi', $classRoom->program_studi) }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('program_studi') border-red-500 @enderror">
                            @error('program_studi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="dosen_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Dosen Pengampu
                            </label>
                            <select name="dosen_id" id="dosen_id"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('dosen_id') border-red-500 @enderror">
                                <option value="">-- Pilih Dosen (Optional) --</option>
                                @foreach($dosens as $dosen)
                                    <option value="{{ $dosen->id }}" {{ old('dosen_id', $classRoom->dosen_id) == $dosen->id ? 'selected' : '' }}>
                                        {{ $dosen->name }} - {{ $dosen->email }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Assign dosen yang akan mengampu kelas ini</p>
                            @error('dosen_id')
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
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('max_groups') border-red-500 @enderror">
                            @error('max_groups')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', $classRoom->is_active) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">
                                    Kelas Aktif
                                </span>
                            </label>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('classrooms.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                                Batal
                            </a>
                            <button type="submit" class="bg-primary-500 hover:bg-primary-700 text-white font-bold py-2 px-6 rounded">
                                Update Kelas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
