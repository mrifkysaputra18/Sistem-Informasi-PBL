<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('targets.index') }}" 
               class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Buat Target Mingguan Baru') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('targets.store') }}">
                        @csrf
                        
                        <!-- Target Type -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Tipe Target <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label class="relative">
                                    <input type="radio" name="target_type" value="single" 
                                           class="sr-only peer" 
                                           onchange="toggleTargetType('single')">
                                    <div class="p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                        <div class="text-center">
                                            <i class="fas fa-users text-2xl mb-2 text-gray-400 peer-checked:text-blue-500"></i>
                                            <div class="font-medium text-gray-900 peer-checked:text-blue-700">Satu Kelompok</div>
                                            <div class="text-sm text-gray-500">Target untuk 1 kelompok spesifik</div>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="relative">
                                    <input type="radio" name="target_type" value="multiple" 
                                           class="sr-only peer"
                                           onchange="toggleTargetType('multiple')">
                                    <div class="p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                        <div class="text-center">
                                            <i class="fas fa-layer-group text-2xl mb-2 text-gray-400 peer-checked:text-blue-500"></i>
                                            <div class="font-medium text-gray-900 peer-checked:text-blue-700">Multiple Kelompok</div>
                                            <div class="text-sm text-gray-500">Target untuk beberapa kelompok</div>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="relative">
                                    <input type="radio" name="target_type" value="all_class" 
                                           class="sr-only peer"
                                           onchange="toggleTargetType('all_class')">
                                    <div class="p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                        <div class="text-center">
                                            <i class="fas fa-school text-2xl mb-2 text-gray-400 peer-checked:text-blue-500"></i>
                                            <div class="font-medium text-gray-900 peer-checked:text-blue-700">Semua Kelas</div>
                                            <div class="text-sm text-gray-500">Target untuk semua kelompok di kelas</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @error('target_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Class Selection (for all_class) -->
                        <div id="class-selection" class="mb-6 hidden">
                            <label for="class_room_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih Kelas <span class="text-red-500">*</span>
                            </label>
                            <select name="class_room_id" id="class_room_id" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    onchange="loadGroups()">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($classRooms as $classRoom)
                                <option value="{{ $classRoom->id }}">{{ $classRoom->name }}</option>
                                @endforeach
                            </select>
                            @error('class_room_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Single Group Selection -->
                        <div id="single-group-selection" class="mb-6 hidden">
                            <label for="group_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih Kelompok <span class="text-red-500">*</span>
                            </label>
                            <select name="group_id" id="group_id" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Pilih Kelompok --</option>
                                @if($selectedGroup)
                                <option value="{{ $selectedGroup->id }}" selected>
                                    {{ $selectedGroup->name }} ({{ $selectedGroup->classRoom->name }})
                                </option>
                                @endif
                            </select>
                            @error('group_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Multiple Groups Selection -->
                        <div id="multiple-groups-selection" class="mb-6 hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih Kelompok <span class="text-red-500">*</span>
                            </label>
                            <div class="border border-gray-300 rounded-md p-4 max-h-60 overflow-y-auto">
                                <div id="groups-list">
                                    <p class="text-gray-500 text-sm">Pilih kelas terlebih dahulu</p>
                                </div>
                            </div>
                            @error('group_ids')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Week Number -->
                        <div class="mb-6">
                            <label for="week_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Minggu Ke <span class="text-red-500">*</span>
                            </label>
                            <select name="week_number" id="week_number" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Pilih Minggu --</option>
                                @for($i = 1; $i <= 16; $i++)
                                <option value="{{ $i }}" {{ old('week_number') == $i ? 'selected' : '' }}>
                                    Minggu {{ $i }}
                                </option>
                                @endfor
                            </select>
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
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror">
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
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deadline -->
                        <div class="mb-6">
                            <label for="deadline" class="block text-sm font-medium text-gray-700 mb-2">
                                Deadline Submit <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" name="deadline" id="deadline" required
                                   value="{{ old('deadline') }}"
                                   min="{{ now()->format('Y-m-d\TH:i') }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('deadline') border-red-500 @enderror">
                                    <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Mahasiswa harus submit sebelum deadline ini
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
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                <i class="fas fa-save mr-2"></i>Buat Target
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleTargetType(type) {
            // Hide all selections
            document.getElementById('class-selection').classList.add('hidden');
            document.getElementById('single-group-selection').classList.add('hidden');
            document.getElementById('multiple-groups-selection').classList.add('hidden');
            
            // Show relevant selection
            if (type === 'all_class') {
                document.getElementById('class-selection').classList.remove('hidden');
            } else if (type === 'single') {
                document.getElementById('single-group-selection').classList.remove('hidden');
            } else if (type === 'multiple') {
                document.getElementById('multiple-groups-selection').classList.remove('hidden');
            }
        }

        function loadGroups() {
            const classId = document.getElementById('class_room_id').value;
            const singleSelect = document.getElementById('group_id');
            const multipleList = document.getElementById('groups-list');
            
            if (!classId) {
                singleSelect.innerHTML = '<option value="">-- Pilih Kelompok --</option>';
                multipleList.innerHTML = '<p class="text-gray-500 text-sm">Pilih kelas terlebih dahulu</p>';
                return;
            }

            // Load groups via AJAX
            fetch(`/api/classrooms/${classId}/groups`)
                .then(response => response.json())
                .then(data => {
                    // Update single select
                    singleSelect.innerHTML = '<option value="">-- Pilih Kelompok --</option>';
                    data.groups.forEach(group => {
                        singleSelect.innerHTML += `<option value="${group.id}">${group.name}</option>`;
                    });

                    // Update multiple selection
                    multipleList.innerHTML = '';
                    data.groups.forEach(group => {
                        multipleList.innerHTML += `
                            <label class="flex items-center mb-2">
                                <input type="checkbox" name="group_ids[]" value="${group.id}" 
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">${group.name}</span>
                            </label>
                        `;
                    });
                })
                .catch(error => {
                    console.error('Error loading groups:', error);
                });
        }

        // Set default datetime to next week
        document.addEventListener('DOMContentLoaded', function() {
            const now = new Date();
            const nextWeek = new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000);
            const datetimeString = nextWeek.toISOString().slice(0, 16);
            document.getElementById('deadline').value = datetimeString;
        });
    </script>
</x-app-layout>