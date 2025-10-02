<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('academic-periods.index') }}" 
               class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Periode Akademik
            </h2>
        </div>
    </x-slot>

    <div class="py-6 pb-24">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-4">
                    <form action="{{ route('academic-periods.update', $academicPeriod) }}" method="POST" id="editPeriodForm">
                        @csrf
                        @method('PATCH')

                        <!-- Grid 2 Columns -->
                        <div class="grid grid-cols-2 gap-4 mb-3">
                            <!-- Tahun Ajaran -->
                            <div>
                                <label for="academic_year" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tahun Ajaran <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="academic_year" 
                                       id="academic_year" 
                                       value="{{ old('academic_year', $academicPeriod->academic_year) }}"
                                       required
                                       placeholder="2024/2025"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('academic_year') border-red-500 @enderror">
                                @error('academic_year')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Semester -->
                            <div>
                                <label for="semester_number" class="block text-sm font-medium text-gray-700 mb-1">
                                    Semester <span class="text-red-500">*</span>
                                </label>
                                <select name="semester_number" 
                                        id="semester_number" 
                                        required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('semester_number') border-red-500 @enderror">
                                    <option value="">Pilih</option>
                                    @for($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}" {{ old('semester_number', $academicPeriod->semester_number) == $i ? 'selected' : '' }}>
                                        Semester {{ $i }} @if(in_array($i, [3, 4, 5]))(PBL)@endif
                                    </option>
                                    @endfor
                                </select>
                                @error('semester_number')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Grid 2 Columns - Dates -->
                        <div class="grid grid-cols-2 gap-4 mb-3">
                            <!-- Tanggal Mulai -->
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       name="start_date" 
                                       id="start_date" 
                                       value="{{ old('start_date', $academicPeriod->start_date->format('Y-m-d')) }}"
                                       required
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('start_date') border-red-500 @enderror">
                                @error('start_date')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Selesai -->
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Selesai <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       name="end_date" 
                                       id="end_date" 
                                       value="{{ old('end_date', $academicPeriod->end_date->format('Y-m-d')) }}"
                                       required
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('end_date') border-red-500 @enderror">
                                @error('end_date')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Deskripsi & Status -->
                        <div class="mb-3">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                Deskripsi
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="2"
                                      placeholder="Deskripsi..."
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('description') border-red-500 @enderror">{{ old('description', $academicPeriod->description) }}</textarea>
                        </div>

                        <!-- Status Aktif (Inline) -->
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="is_active" 
                                       id="is_active" 
                                       value="1"
                                       {{ old('is_active', $academicPeriod->is_active) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm">
                                <span class="ml-2 text-sm text-gray-700">Set sebagai periode aktif</span>
                            </label>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- FIXED ACTION BUTTONS AT BOTTOM (Always Visible) -->
    <div class="fixed bottom-0 left-0 right-0 bg-gradient-to-r from-yellow-400 to-orange-500 border-t-4 border-yellow-700 shadow-2xl z-50">
        <div class="max-w-3xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <a href="{{ route('academic-periods.index') }}" 
                   class="inline-flex items-center px-6 py-3 border-2 border-red-600 shadow-lg text-base font-bold rounded-lg bg-white hover:bg-gray-200 transition duration-200"
                   style="color: #991b1b !important;">
                    <i class="fas fa-times mr-2" style="color: #dc2626 !important;"></i>
                    <span style="color: #1f2937 !important;">BATAL</span>
                </a>
                <button type="submit" 
                        form="editPeriodForm"
                        class="inline-flex items-center px-10 py-4 border-4 border-white text-xl font-black rounded-lg shadow-2xl hover:scale-105 transition duration-200 animate-pulse"
                        style="background-color: #16a34a !important; color: #ffffff !important;">
                    <i class="fas fa-save mr-3 text-2xl" style="color: #fef08a !important;"></i>
                    <span style="color: #ffffff !important; font-weight: 900;">SIMPAN PERUBAHAN</span>
                </button>
            </div>
        </div>
    </div>
</x-app-layout>

