<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('targets.index') }}" 
               class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Edit Target Minggu ') . $weekNumber }}
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

                    <form action="{{ route('targets.week.update', [$weekNumber, $classRoom->id]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Week Number -->
                        <div class="mb-6">
                            <label for="week_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Minggu Ke <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="week_number" id="week_number" required
                                   value="{{ old('week_number', $firstTarget->week_number) }}"
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
                                   value="{{ old('title', $firstTarget->title) }}"
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
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $firstTarget->description) }}</textarea>
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
                                   value="{{ old('deadline', $firstTarget->deadline ? $firstTarget->deadline->format('Y-m-d H:i') : '') }}"
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
                                <i class="fas fa-save mr-2"></i>Update Target
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
            // Inisialisasi Flatpickr dengan format 24 jam
            flatpickr("#deadline", {
                enableTime: true,
                time_24hr: true,
                dateFormat: "Y-m-d H:i",
                locale: "id",
                allowInput: true,
                altInput: true,
                altFormat: "d M Y, H:i",
                minuteIncrement: 5
            });
        });
    </script>
</x-app-layout>
