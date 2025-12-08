<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('targets.index') }}" 
               class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Buat Target Mingguan Baru') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Display Validation Errors -->
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded relative" role="alert">
                            <div class="font-bold mb-2">
                                <i class="fas fa-exclamation-circle mr-2"></i>Terjadi Kesalahan!
                            </div>
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('targets.store') }}" id="targetForm">
                        @csrf
                        
                        <!-- Hidden field: Target type always 'all_class' -->
                        <input type="hidden" name="target_type" value="all_class">

                        <!-- Class Selection -->
                        <div class="mb-6">
                            <label for="class_room_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih Kelas Terlebih Dahulu <span class="text-red-500">*</span>
                            </label>
                            <select name="class_room_id" id="class_room_id" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($classRooms as $classRoom)
                                <option value="{{ $classRoom->id }}" {{ old('class_room_id') == $classRoom->id ? 'selected' : '' }}>
                                    {{ $classRoom->name }}
                                </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                Target akan dibuat untuk <strong>semua kelompok</strong> di kelas yang dipilih
                            </p>
                            @error('class_room_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        <!-- Week Number -->
                        <div class="mb-6">
                            <label for="week_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Minggu Ke <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="week_number" id="week_number" required
                                   value="{{ old('week_number') }}"
                                   min="1"
                                   placeholder="Masukkan nomor minggu"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('week_number') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Masukkan nomor minggu (minimal 1)
                            </p>
                            @error('week_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Title -->
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Target <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" required
                                   value="{{ old('title') }}"
                                   placeholder="Contoh: Membuat Use Case Diagram"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('title') border-red-500 @enderror">
                            @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Target <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" id="description" rows="4" required
                                      placeholder="Jelaskan detail target yang harus dikerjakan mahasiswa..."
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deadline -->
                        <div class="mb-6">
                            <label for="deadline" class="block text-sm font-medium text-gray-700 mb-2">
                                Deadline Submit <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="deadline" id="deadline" required
                                   value="{{ old('deadline') }}"
                                   placeholder="Pilih tanggal dan waktu"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('deadline') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Mahasiswa harus submit sebelum deadline ini (format 24 jam)
                            </p>
                            @error('deadline')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex gap-2">
                            <a href="{{ route('targets.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="bg-primary-500 hover:bg-primary-700 text-white font-bold py-2 px-6 rounded">
                                <i class="fas fa-save mr-2"></i>Buat Target
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Flatpickr CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set default deadline ke besok jam 23:59
            const besok = new Date();
            besok.setDate(besok.getDate() + 1);
            besok.setHours(23, 59, 0, 0);
            
            // Inisialisasi Flatpickr dengan format 24 jam
            flatpickr("#deadline", {
                enableTime: true,
                time_24hr: true,
                dateFormat: "Y-m-d H:i",
                minDate: "today",
                defaultDate: besok,
                locale: "id",
                allowInput: true,
                altInput: true,
                altFormat: "d M Y, H:i",
                minuteIncrement: 5
            });
        });
    </script>
</x-app-layout>