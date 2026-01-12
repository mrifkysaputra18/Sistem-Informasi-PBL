<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('targets.index') }}" 
               class="back-btn mr-4"><i class="fas fa-arrow-left"></i>
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

                        <!-- Todo List Section -->
                        <div class="mb-6" x-data="todoListManager()">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fa-solid fa-list-check mr-1 text-indigo-600"></i>
                                Todo List <span class="text-red-500">*</span>
                            </label>
                            <p class="text-xs text-gray-500 mb-3">
                                <i class="fa-solid fa-info-circle mr-1"></i>
                                Buat daftar tugas yang harus diselesaikan mahasiswa. Minimal 1 item.
                            </p>

                            <!-- Todo Items Container -->
                            <div class="space-y-3" id="todo-container">
                                <template x-for="(todo, index) in todos" :key="index">
                                    <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                        <!-- Order Number -->
                                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm" x-text="index + 1"></div>
                                        
                                        <!-- Input Fields -->
                                        <div class="flex-1 space-y-2">
                                            <input type="text" 
                                                   :name="'todo_items[' + index + '][title]'"
                                                   x-model="todo.title"
                                                   placeholder="Judul todo (wajib)"
                                                   required
                                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            <input type="text" 
                                                   :name="'todo_items[' + index + '][description]'"
                                                   x-model="todo.description"
                                                   placeholder="Deskripsi tambahan (opsional)"
                                                   class="w-full rounded-md border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs text-gray-600">
                                            <input type="hidden" :name="'todo_items[' + index + '][order]'" :value="index">
                                        </div>
                                        
                                        <!-- Delete Button -->
                                        <button type="button" 
                                                @click="removeTodo(index)"
                                                x-show="todos.length > 1"
                                                class="flex-shrink-0 w-8 h-8 rounded-full bg-red-100 text-red-600 hover:bg-red-200 flex items-center justify-center transition-colors">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>

                            <!-- Add Todo Button -->
                            <button type="button" 
                                    @click="addTodo()"
                                    class="mt-3 w-full py-3 border-2 border-dashed border-gray-300 rounded-lg text-gray-500 hover:border-indigo-400 hover:text-indigo-600 transition-colors flex items-center justify-center gap-2">
                                <i class="fa-solid fa-plus"></i>
                                <span>Tambah Todo Item</span>
                            </button>

                            <!-- Todo Count Info -->
                            <p class="mt-2 text-xs text-gray-500">
                                <i class="fa-solid fa-calculator mr-1"></i>
                                Total: <span class="font-bold" x-text="todos.length"></span> todo items. 
                                Setiap item bernilai <span class="font-bold text-indigo-600" x-text="(100 / todos.length).toFixed(1)"></span>% dari nilai total.
                            </p>
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
        // Alpine.js Todo List Manager
        function todoListManager() {
            return {
                todos: [
                    { title: '', description: '' }
                ],
                addTodo() {
                    this.todos.push({ title: '', description: '' });
                },
                removeTodo(index) {
                    if (this.todos.length > 1) {
                        this.todos.splice(index, 1);
                    }
                }
            }
        }

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