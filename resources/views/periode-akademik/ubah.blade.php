<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('academic-periods.index') }}" class="mr-4 text-white hover:text-gray-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                Edit Periode Akademik
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md rounded-xl">
                <div class="p-6">
                    <form action="{{ route('academic-periods.update', $academicPeriod) }}" method="POST">
                        @csrf
                        @method('PATCH')

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
                                       value="{{ old('academic_year', $academicPeriod->academic_year) }}"
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
                                    <option value="3" {{ old('semester_number', $academicPeriod->semester_number) == 3 ? 'selected' : '' }}>Semester 3</option>
                                    <option value="4" {{ old('semester_number', $academicPeriod->semester_number) == 4 ? 'selected' : '' }}>Semester 4</option>
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
                                       value="{{ old('start_date', $academicPeriod->start_date->format('Y-m-d')) }}"
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
                                       value="{{ old('end_date', $academicPeriod->end_date->format('Y-m-d')) }}"
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
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $academicPeriod->description) }}</textarea>
                        </div>

                        <!-- Status Aktif -->
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', $academicPeriod->is_active) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Set sebagai periode aktif</span>
                            </label>
                        </div>

                        <!-- Status Ujian -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status Periode Ujian
                            </label>
                            <div class="flex flex-wrap gap-3" id="exam-period-container">
                                <!-- Option: Tidak Ada Ujian -->
                                <label class="exam-period-option flex items-center px-4 py-2 border rounded-lg cursor-pointer transition-all {{ old('current_exam_period', $academicPeriod->current_exam_period ?? 'none') === 'none' ? 'bg-gray-100 border-gray-400 ring-2 ring-gray-400' : 'bg-white border-gray-200 hover:bg-gray-50' }}"
                                       data-value="none"
                                       data-active-class="bg-gray-100 border-gray-400 ring-2 ring-gray-400"
                                       data-inactive-class="bg-white border-gray-200 hover:bg-gray-50">
                                    <input type="radio" 
                                           name="current_exam_period" 
                                           value="none"
                                           {{ old('current_exam_period', $academicPeriod->current_exam_period ?? 'none') === 'none' ? 'checked' : '' }}
                                           class="sr-only"
                                           onchange="updateExamPeriodStyles(this)">
                                    <i class="fas fa-pause-circle {{ old('current_exam_period', $academicPeriod->current_exam_period ?? 'none') === 'none' ? 'text-gray-700' : 'text-gray-500' }} icon mr-2"></i>
                                    <span class="text-sm font-medium {{ old('current_exam_period', $academicPeriod->current_exam_period ?? 'none') === 'none' ? 'text-gray-900' : 'text-gray-700' }} text">Tidak Ada Ujian</span>
                                </label>

                                <!-- Option: Periode UTS -->
                                <label class="exam-period-option flex items-center px-4 py-2 border rounded-lg cursor-pointer transition-all {{ old('current_exam_period', $academicPeriod->current_exam_period) === 'uts' ? 'bg-blue-100 border-blue-400 ring-2 ring-blue-400' : 'bg-white border-gray-200 hover:bg-blue-50' }}"
                                       data-value="uts"
                                       data-active-class="bg-blue-100 border-blue-400 ring-2 ring-blue-400"
                                       data-inactive-class="bg-white border-gray-200 hover:bg-blue-50">
                                    <input type="radio" 
                                           name="current_exam_period" 
                                           value="uts"
                                           {{ old('current_exam_period', $academicPeriod->current_exam_period) === 'uts' ? 'checked' : '' }}
                                           class="sr-only"
                                           onchange="updateExamPeriodStyles(this)">
                                    <i class="fas fa-file-alt {{ old('current_exam_period', $academicPeriod->current_exam_period) === 'uts' ? 'text-blue-700' : 'text-blue-500' }} icon mr-2"></i>
                                    <span class="text-sm font-medium {{ old('current_exam_period', $academicPeriod->current_exam_period) === 'uts' ? 'text-blue-900' : 'text-blue-700' }} text">Periode UTS</span>
                                </label>

                                <!-- Option: Periode UAS -->
                                <label class="exam-period-option flex items-center px-4 py-2 border rounded-lg cursor-pointer transition-all {{ old('current_exam_period', $academicPeriod->current_exam_period) === 'uas' ? 'bg-orange-100 border-orange-400 ring-2 ring-orange-400' : 'bg-white border-gray-200 hover:bg-orange-50' }}"
                                       data-value="uas"
                                       data-active-class="bg-orange-100 border-orange-400 ring-2 ring-orange-400"
                                       data-inactive-class="bg-white border-gray-200 hover:bg-orange-50">
                                    <input type="radio" 
                                           name="current_exam_period" 
                                           value="uas"
                                           {{ old('current_exam_period', $academicPeriod->current_exam_period) === 'uas' ? 'checked' : '' }}
                                           class="sr-only"
                                           onchange="updateExamPeriodStyles(this)">
                                    <i class="fas fa-file-signature {{ old('current_exam_period', $academicPeriod->current_exam_period) === 'uas' ? 'text-orange-700' : 'text-orange-500' }} icon mr-2"></i>
                                    <span class="text-sm font-medium {{ old('current_exam_period', $academicPeriod->current_exam_period) === 'uas' ? 'text-orange-900' : 'text-orange-700' }} text">Periode UAS</span>
                                </label>

                                <script>
                                    function updateExamPeriodStyles(radio) {
                                        // Reset all labels
                                        document.querySelectorAll('.exam-period-option').forEach(label => {
                                            const activeClass = label.dataset.activeClass;
                                            const inactiveClass = label.dataset.inactiveClass;
                                            
                                            // Remove active classes, add inactive classes
                                            label.classList.remove(...activeClass.split(' '));
                                            label.classList.add(...inactiveClass.split(' '));
                                            
                                            // Reset text/icon colors to default (muted)
                                            /* Note: We keep simple generic logic or re-render based on specific logic. 
                                               To keep it simple, we just toggle the container classes defined in data attrs.
                                               For distinct text colors, we can handle them if needed, but the bg change is the most important.
                                            */
                                        });

                                        // Set active label
                                        if (radio.checked) {
                                            const activeLabel = radio.closest('label');
                                            const activeClass = activeLabel.dataset.activeClass;
                                            const inactiveClass = activeLabel.dataset.inactiveClass;
                                            
                                            activeLabel.classList.remove(...inactiveClass.split(' '));
                                            activeLabel.classList.add(...activeClass.split(' '));
                                        }
                                    }
                                </script>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Status ini menentukan dosen mana yang bisa input nilai rubrik.
                            </p>
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
                                Simpan Perubahan
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
