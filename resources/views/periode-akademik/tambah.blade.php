<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('academic-periods.index') }}" class="mr-4 text-white hover:text-gray-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                Tambah Periode Akademik
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md rounded-xl">
                <div class="p-6">
                    <form action="{{ route('academic-periods.store') }}" method="POST">
                        @csrf

                        <!-- Grid 2 Columns -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <!-- Tahun Ajaran -->
                            <div>
                                <label for="academic_year" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tahun Ajaran <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="academic_year" 
                                       id="academic_year" 
                                       value="{{ old('academic_year') }}"
                                       required
                                       placeholder="2024/2025"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('academic_year') border-red-500 @enderror">
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
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('semester_number') border-red-500 @enderror">
                                    <option value="">Pilih Semester</option>
                                    <option value="3" {{ old('semester_number') == 3 ? 'selected' : '' }}>Semester 3</option>
                                    <option value="4" {{ old('semester_number') == 4 ? 'selected' : '' }}>Semester 4</option>
                                </select>
                                @error('semester_number')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Grid 2 Columns - Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <!-- Tanggal Mulai -->
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="start_date" 
                                       id="start_date" 
                                       value="{{ old('start_date') }}"
                                       required
                                       placeholder="dd/mm/yyyy"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('start_date') border-red-500 @enderror">
                                @error('start_date')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Selesai -->
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Selesai <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="end_date" 
                                       id="end_date" 
                                       value="{{ old('end_date') }}"
                                       required
                                       placeholder="dd/mm/yyyy"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('end_date') border-red-500 @enderror">
                                @error('end_date')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                Deskripsi
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="3"
                                      placeholder="Deskripsi periode akademik..."
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        </div>

                        <!-- Status Aktif -->
                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active') ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Set sebagai periode aktif</span>
                            </label>
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t">
                            <a href="{{ route('academic-periods.index') }}" 
                               class="px-5 py-2.5 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                <i class="fas fa-save mr-2"></i>
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi Flatpickr untuk Tanggal Mulai
            flatpickr("#start_date", {
                dateFormat: "Y-m-d",          // Format untuk database (YYYY-MM-DD)
                altInput: true,               // Gunakan input alternatif untuk display
                altFormat: "d/m/Y",           // Format tampilan (DD/MM/YYYY)
                locale: {
                    firstDayOfWeek: 1         // Senin sebagai hari pertama
                },
                allowInput: true              // Allow manual input
            });

            // Inisialisasi Flatpickr untuk Tanggal Selesai
            flatpickr("#end_date", {
                dateFormat: "Y-m-d",          // Format untuk database (YYYY-MM-DD)
                altInput: true,               // Gunakan input alternatif untuk display
                altFormat: "d/m/Y",           // Format tampilan (DD/MM/YYYY)
                locale: {
                    firstDayOfWeek: 1         // Senin sebagai hari pertama
                },
                allowInput: true              // Allow manual input
            });
        });
    </script>
</x-app-layout>
