<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('groups.index') }}" class="mr-4 text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Buat Kelompok Baru') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('groups.store') }}" id="createGroupForm">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- LEFT COLUMN: Group Info -->
                            <div>
                                <h3 class="text-lg font-semibold mb-4 text-gray-800">📋 Informasi Kelompok</h3>
                                
                                <!-- Pilih Kelas -->
                                <div class="mb-4">
                                    <label for="class_room_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Kelas <span class="text-red-500">*</span>
                                    </label>
                                    <select name="class_room_id" id="class_room_id" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('class_room_id') border-red-500 @enderror">
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($classRooms as $classRoom)
                                        <option value="{{ $classRoom->id }}" 
                                            {{ old('class_room_id', request('class_room_id')) == $classRoom->id ? 'selected' : '' }}
                                            {{ !$classRoom->canAddGroup() ? 'disabled' : '' }}>
                                            {{ $classRoom->name }} 
                                            ({{ $classRoom->groups->count() }}/{{ $classRoom->max_groups }} kelompok)
                                            {{ !$classRoom->canAddGroup() ? '- PENUH' : '' }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('class_room_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nama Kelompok -->
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Kelompok <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" required
                                        value="{{ old('name') }}"
                                        placeholder="Contoh: Kelompok 1, Tim A, dll"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                                    @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Maksimal Anggota -->
                                <div class="mb-4">
                                    <label for="max_members" class="block text-sm font-medium text-gray-700 mb-2">
                                        Maksimal Anggota
                                    </label>
                                    <input type="number" name="max_members" id="max_members" 
                                        value="{{ old('max_members', 5) }}"
                                        min="1" max="10"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('max_members') border-red-500 @enderror">
                                    <p class="mt-1 text-xs text-gray-500">Maksimal anggota yang bisa bergabung (default: 5)</p>
                                    @error('max_members')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Mata Kuliah (Project) -->
                                <div class="mb-4">
                                    <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Mata Kuliah (Opsional)
                                    </label>
                                    <select name="project_id" id="project_id"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500">
                                        <option value="">-- Tidak Ada Mata Kuliah --</option>
                                        @if(isset($projects))
                                            @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                                {{ $project->title }}
                                            </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <!-- RIGHT COLUMN: Members Selection -->
                            <div>
                                <h3 class="text-lg font-semibold mb-4 text-gray-800">👥 Pilih Anggota Kelompok</h3>
                                
                                <div class="mb-4">
                                    <input type="text" id="searchMember" placeholder="🔍 Cari mahasiswa..." 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 mb-3">
                                    
                                    <div class="border rounded-lg p-4 max-h-96 overflow-y-auto bg-gray-50">
                                        <div id="studentsList">
                                            <p class="text-gray-500 text-sm text-center py-8">
                                                <i class="fas fa-info-circle text-primary-500 text-2xl mb-2"></i><br>
                                                <strong>Pilih kelas terlebih dahulu</strong><br>
                                                <small class="text-xs">Mahasiswa yang tersedia akan ditampilkan sesuai kelas yang dipilih</small>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <p class="text-xs text-gray-500 mt-2">
                                        <span id="selectedCount">0</span> anggota dipilih
                                    </p>
                                </div>

                                <!-- Leader Selection -->
                                <div class="mb-4" id="leaderSection" style="display: none;">
                                    <label for="leader_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Pilih Ketua Kelompok <span class="text-red-500">*</span>
                                    </label>
                                    <select name="leader_id" id="leader_id"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500">
                                        <option value="">-- Pilih Ketua --</option>
                                    </select>
                                    <p class="mt-1 text-xs text-gray-500">Ketua dipilih dari anggota yang sudah ditandai</p>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-2 mt-6 pt-6 border-t">
                            <a href="{{ route('groups.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                            <button type="submit" class="bg-primary-500 hover:bg-primary-700 text-white font-bold py-2 px-6 rounded">
                                <i class="fas fa-save mr-2"></i>Buat Kelompok
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-primary-50 border border-primary-200 rounded-lg p-4">
                <h4 class="font-semibold text-primary-800 mb-2">ℹ️ Informasi</h4>
                <ul class="text-sm text-primary-700 space-y-1">
                    <li>• Pilih anggota dari daftar mahasiswa di sebelah kanan</li>
                    <li>• Setelah memilih anggota, pilih salah satu sebagai ketua kelompok</li>
                    <li>• Anda juga bisa membuat kelompok kosong dan menambahkan anggota nanti</li>
                </ul>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Filter students by classroom
        const classRoomSelect = document.getElementById('class_room_id');
        const studentsList = document.getElementById('studentsList');
        const searchMemberInput = document.getElementById('searchMember');
        
        classRoomSelect.addEventListener('change', function() {
            const classRoomId = this.value;
            
            if (!classRoomId) {
                studentsList.innerHTML = '<p class="text-gray-500 text-sm text-center py-4">Pilih kelas terlebih dahulu untuk melihat mahasiswa yang tersedia</p>';
                return;
            }
            
            // Show loading
            studentsList.innerHTML = '<p class="text-gray-500 text-sm text-center py-4"><i class="fas fa-spinner fa-spin mr-2"></i>Memuat daftar mahasiswa...</p>';
            
            // Fetch available students for this classroom
            fetch(`{{ route('groups.available-students') }}?class_room_id=${classRoomId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.students.length === 0) {
                        studentsList.innerHTML = '<p class="text-gray-500 text-sm text-center py-4">Tidak ada mahasiswa yang tersedia untuk kelas ini.<br><small class="text-xs">Semua mahasiswa sudah tergabung dalam kelompok.</small></p>';
                        return;
                    }
                    
                    // Build students list HTML
                    let html = '';
                    data.students.forEach(student => {
                        html += `
                            <label class="flex items-center p-2 hover:bg-white rounded cursor-pointer student-item" data-name="${student.name.toLowerCase()}">
                                <input type="checkbox" name="members[]" value="${student.id}" 
                                    class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 member-checkbox">
                                <div class="ml-3">
                                    <span class="text-sm font-medium text-gray-700">${student.name}</span>
                                    <span class="text-xs text-gray-500 block">${student.email}</span>
                                </div>
                            </label>
                        `;
                    });
                    
                    studentsList.innerHTML = html;
                    
                    // Re-attach event listeners for checkboxes
                    attachCheckboxListeners();
                })
                .catch(error => {
                    console.error('Error:', error);
                    studentsList.innerHTML = '<p class="text-red-500 text-sm text-center py-4">Terjadi kesalahan saat memuat data mahasiswa</p>';
                });
        });
        
        // Function to attach checkbox event listeners
        function attachCheckboxListeners() {
            const memberCheckboxes = document.querySelectorAll('.member-checkbox');
            memberCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateLeaderOptions);
            });
            updateLeaderOptions();
        }
        
        // Search functionality
        document.getElementById('searchMember').addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const students = document.querySelectorAll('.student-item');
            
            students.forEach(student => {
                const name = student.getAttribute('data-name');
                if (name.includes(searchTerm)) {
                    student.style.display = 'flex';
                } else {
                    student.style.display = 'none';
                }
            });
        });

        // Update leader dropdown when members are selected
        const memberCheckboxes = document.querySelectorAll('.member-checkbox');
        const leaderSelect = document.getElementById('leader_id');
        const leaderSection = document.getElementById('leaderSection');
        const selectedCountSpan = document.getElementById('selectedCount');

        function updateLeaderOptions() {
            const checked = Array.from(memberCheckboxes).filter(cb => cb.checked);
            selectedCountSpan.textContent = checked.length;
            
            leaderSelect.innerHTML = '<option value="">-- Pilih Ketua --</option>';
            
            if (checked.length > 0) {
                leaderSection.style.display = 'block';
                checked.forEach(checkbox => {
                    const label = checkbox.closest('.student-item');
                    const studentName = label.querySelector('.text-sm.font-medium').textContent;
                    const option = document.createElement('option');
                    option.value = checkbox.value;
                    option.textContent = studentName;
                    leaderSelect.appendChild(option);
                });
            } else {
                leaderSection.style.display = 'none';
            }
        }

        // Initial check - attach listeners for any pre-loaded students
        attachCheckboxListeners();
    </script>
    @endpush
</x-app-layout>