<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('semesters.index') }}" 
               class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                Tambah Semester
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8">
                    <!-- Header Icon -->
                    <div class="mb-8 text-center">
                        <div class="bg-primary-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-graduation-cap text-primary-600 text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Tambah Semester Baru</h3>
                        <p class="text-gray-600">Buat semester baru untuk tahun ajaran</p>
                    </div>

                    <form action="{{ route('semesters.store') }}" method="POST">
                        @csrf

                        <!-- Tahun Ajaran -->
                        <div class="mb-6">
                            <label for="academic_year_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt text-gray-500 mr-1"></i>
                                Tahun Ajaran <span class="text-red-500">*</span>
                            </label>
                            <select name="academic_year_id" 
                                    id="academic_year_id" 
                                    required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('academic_year_id') border-red-500 @enderror">
                                <option value="">Pilih Tahun Ajaran</option>
                                @foreach($academicYears as $academicYear)
                                <option value="{{ $academicYear->id }}" 
                                        {{ old('academic_year_id', request('academic_year_id')) == $academicYear->id ? 'selected' : '' }}>
                                    {{ $academicYear->name }} ({{ $academicYear->code }})
                                </option>
                                @endforeach
                            </select>
                            @error('academic_year_id')
                            <p class="mt-1 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Nomor Semester -->
                        <div class="mb-6">
                            <label for="number" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-hashtag text-gray-500 mr-1"></i>
                                Nomor Semester <span class="text-red-500">*</span>
                            </label>
                            <select name="number" 
                                    id="number" 
                                    required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('number') border-red-500 @enderror">
                                <option value="">Pilih Nomor Semester</option>
                                @for($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}" {{ old('number') == $i ? 'selected' : '' }}>
                                    Semester {{ $i }}
                                    @if(in_array($i, [3, 4, 5]))
                                        (PBL)
                                    @endif
                                </option>
                                @endfor
                            </select>
                            @error('number')
                            <p class="mt-1 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Semester 3, 4, dan 5 adalah semester PBL
                            </p>
                        </div>

                        <!-- Nama Semester -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-graduation-cap text-gray-500 mr-1"></i>
                                Nama Semester <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name') }}"
                                   required
                                   placeholder="Contoh: Semester 1, Semester 2"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Kode Semester -->
                        <div class="mb-6">
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-hashtag text-gray-500 mr-1"></i>
                                Kode Semester <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="code" 
                                   id="code" 
                                   value="{{ old('code') }}"
                                   required
                                   placeholder="Contoh: 2023-2024-1, 2023-2024-2"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('code') border-red-500 @enderror">
                            @error('code')
                            <p class="mt-1 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Format: tahun-awal-tahun-akhir-nomor-semester
                            </p>
                        </div>

                        <!-- Tanggal Mulai -->
                        <div class="mb-6">
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-start text-gray-500 mr-1"></i>
                                Tanggal Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   name="start_date" 
                                   id="start_date" 
                                   value="{{ old('start_date') }}"
                                   required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('start_date') border-red-500 @enderror">
                            @error('start_date')
                            <p class="mt-1 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Tanggal Selesai -->
                        <div class="mb-6">
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-end text-gray-500 mr-1"></i>
                                Tanggal Selesai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   name="end_date" 
                                   id="end_date" 
                                   value="{{ old('end_date') }}"
                                   required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('end_date') border-red-500 @enderror">
                            @error('end_date')
                            <p class="mt-1 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-align-left text-gray-500 mr-1"></i>
                                Deskripsi
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="3"
                                      placeholder="Deskripsi singkat tentang semester..."
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                            <p class="mt-1 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Status Aktif -->
                        <div class="mb-6">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="is_active" 
                                       id="is_active" 
                                       value="1"
                                       {{ old('is_active') ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-blue-500">
                                <label for="is_active" class="ml-3 text-sm font-medium text-gray-700">
                                    <i class="fas fa-check-circle text-green-600 mr-1"></i>
                                    Set sebagai semester aktif
                                </label>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Hanya satu semester yang dapat aktif per tahun ajaran
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('semesters.index') }}" 
                               class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                                <i class="fas fa-save mr-2"></i>Simpan Semester
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
