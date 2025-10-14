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
                                            {{ old('class_room_id', $selectedClassroom ?? request('class_room_id')) == $classRoom->id ? 'selected' : '' }}
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
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 mb-3 rounded-r">
                                        <p class="text-xs text-yellow-800">
                                            <i class="fas fa-hand-pointer text-yellow-600"></i>
                                            <strong>Cara Menggunakan:</strong> Centang kotak di samping nama mahasiswa untuk menambahkan mereka ke kelompok
                                        </p>
                                    </div>
                                    
                                    <div class="flex gap-2 mb-3">
                                        <input type="text" id="searchMember" placeholder="🔍 Cari mahasiswa..." 
                                            class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500">
                                        <button type="button" id="selectAllBtn" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md text-xs font-medium transition-colors" style="display: none;">
                                            <i class="fas fa-check-double mr-1"></i>Pilih Semua
                                        </button>
                                    </div>
                                    
                                    <div class="border-2 border-gray-300 rounded-lg p-3 max-h-96 overflow-y-auto bg-white shadow-inner">
                                        <div id="studentsList">
                                            <div class="text-gray-400 text-center py-12">
                                                <i class="fas fa-users text-5xl mb-3"></i>
                                                <p class="text-sm font-medium">Pilih Kelas Terlebih Dahulu</p>
                                                <p class="text-xs mt-1">Daftar mahasiswa akan muncul di sini</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3 flex items-center justify-between bg-blue-50 border border-blue-200 rounded-lg p-2">
                                        <p class="text-sm text-blue-800">
                                            <i class="fas fa-check-circle"></i>
                                            <span class="font-semibold"><span id="selectedCount">0</span> mahasiswa</span> dipilih
                                        </p>
                                    </div>
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
            <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 rounded-r-lg p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-lightbulb text-blue-500 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="font-semibold text-blue-900 mb-2">💡 Panduan Membuat Kelompok</h4>
                        <ol class="text-sm text-blue-800 space-y-2 list-decimal list-inside">
                            <li><strong>Pilih Kelas</strong> - Mahasiswa akan otomatis dimuat sesuai kelas</li>
                            <li><strong>Centang Mahasiswa</strong> - Klik kotak di samping nama untuk menambahkan anggota</li>
                            <li><strong>Pilih Ketua</strong> - Pilih satu anggota sebagai ketua kelompok</li>
                            <li><strong>Simpan</strong> - Klik "Buat Kelompok" untuk menyimpan</li>
                        </ol>
                        <div class="mt-3 p-2 bg-blue-100 rounded text-xs text-blue-900">
                            <i class="fas fa-info-circle mr-1"></i>
                            <strong>Tips:</strong> Anda juga bisa membuat kelompok kosong terlebih dahulu, lalu menambahkan anggota kemudian
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter students by classroom
            const classRoomSelect = document.getElementById('class_room_id');
            const studentsList = document.getElementById('studentsList');
            const searchMemberInput = document.getElementById('searchMember');
            const selectAllBtn = document.getElementById('selectAllBtn');
            
            console.log('Form loaded');
            
            function loadStudents(classRoomId) {
                console.log('Loading students for class:', classRoomId);
                
                if (!classRoomId) {
                    studentsList.innerHTML = '<div class="text-gray-400 text-center py-12"><i class="fas fa-users text-5xl mb-3"></i><p class="text-sm font-medium">Pilih Kelas Terlebih Dahulu</p><p class="text-xs mt-1">Daftar mahasiswa akan muncul di sini</p></div>';
                    selectAllBtn.style.display = 'none';
                    return;
                }
                
                // Show loading
                studentsList.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-blue-500 text-3xl mb-3"></i><p class="text-sm text-gray-600">Memuat daftar mahasiswa...</p></div>';
                
                // Fetch available students for this classroom
                const url = `{{ route('groups.available-students') }}?class_room_id=${classRoomId}`;
                console.log('Fetching from:', url);
                
                fetch(url)
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            throw new Error('HTTP error! status: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Data received:', data.students.length, 'students');
                    if (data.students.length === 0) {
                        studentsList.innerHTML = `
                            <div class="text-center py-8">
                                <i class="fas fa-user-slash text-gray-300 text-4xl mb-3"></i>
                                <p class="text-sm font-medium text-gray-600">Tidak Ada Mahasiswa Tersedia</p>
                                <p class="text-xs text-gray-500 mt-1">Semua mahasiswa di kelas ini sudah tergabung dalam kelompok</p>
                            </div>
                        `;
                        return;
                    }
                    
                    // Show success message with count
                    console.log('Loaded ' + data.students.length + ' students');
                    
                    // Build students list HTML
                    let html = '';
                    data.students.forEach((student, index) => {
                        html += `
                            <label class="flex items-start p-3 mb-2 hover:bg-blue-50 border border-transparent hover:border-blue-300 rounded-lg cursor-pointer transition-all duration-200 student-item" data-name="${student.name.toLowerCase()}">
                                <input type="checkbox" name="members[]" value="${student.id}" 
                                    class="mt-1 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 member-checkbox w-4 h-4">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-semibold text-gray-800">${student.name}</span>
                                        <span class="text-xs text-gray-400 ml-2">#${index + 1}</span>
                                    </div>
                                    <div class="mt-0.5 flex items-center gap-2">
                                        <span class="text-xs text-blue-600 font-medium">${student.politala_id || 'N/A'}</span>
                                        <span class="text-xs text-gray-400">•</span>
                                        <span class="text-xs text-gray-500">${student.email}</span>
                                    </div>
                                </div>
                            </label>
                        `;
                    });
                    
                    studentsList.innerHTML = html;
                    
                        // Show select all button
                        selectAllBtn.style.display = 'block';
                        
                        // Re-attach event listeners for checkboxes
                        attachCheckboxListeners();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        studentsList.innerHTML = '<div class="text-center py-8"><i class="fas fa-exclamation-triangle text-red-500 text-3xl mb-3"></i><p class="text-sm text-red-600">Terjadi kesalahan saat memuat data mahasiswa</p><p class="text-xs text-gray-500 mt-1">' + error.message + '</p></div>';
                        selectAllBtn.style.display = 'none';
                    });
            }
            
            // Event listener for class selection
            classRoomSelect.addEventListener('change', function() {
                loadStudents(this.value);
            });
        
            // Function to attach checkbox event listeners
            function attachCheckboxListeners() {
                const memberCheckboxes = document.querySelectorAll('.member-checkbox');
                memberCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', updateLeaderOptions);
                });
                updateLeaderOptions();
            }
            
            // Select All functionality
            selectAllBtn.addEventListener('click', function() {
            const visibleCheckboxes = Array.from(document.querySelectorAll('.student-item'))
                .filter(item => item.style.display !== 'none')
                .map(item => item.querySelector('.member-checkbox'));
            
            const allChecked = visibleCheckboxes.every(cb => cb.checked);
            
            visibleCheckboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
            
            // Update button text
            this.innerHTML = allChecked 
                ? '<i class="fas fa-check-double mr-1"></i>Pilih Semua'
                : '<i class="fas fa-times mr-1"></i>Batal Pilih';
            
                updateLeaderOptions();
            });
            
            // Search functionality
            searchMemberInput.addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const students = document.querySelectorAll('.student-item');
            let visibleCount = 0;
            
            students.forEach(student => {
                const name = student.getAttribute('data-name');
                if (name.includes(searchTerm)) {
                    student.style.display = 'flex';
                    visibleCount++;
                } else {
                    student.style.display = 'none';
                }
            });
            
                // Show/hide select all button based on visible students
                selectAllBtn.style.display = visibleCount > 0 ? 'block' : 'none';
            });

            // Update leader dropdown when members are selected
            const leaderSelect = document.getElementById('leader_id');
            const leaderSection = document.getElementById('leaderSection');
            const selectedCountSpan = document.getElementById('selectedCount');

            function updateLeaderOptions() {
                const memberCheckboxes = document.querySelectorAll('.member-checkbox');
                const checked = Array.from(memberCheckboxes).filter(cb => cb.checked);
                selectedCountSpan.textContent = checked.length;
                
                leaderSelect.innerHTML = '<option value="">-- Pilih Ketua --</option>';
                
                if (checked.length > 0) {
                    leaderSection.style.display = 'block';
                    checked.forEach(checkbox => {
                        const label = checkbox.closest('.student-item');
                        const studentName = label.querySelector('.text-sm.font-semibold').textContent;
                        const option = document.createElement('option');
                        option.value = checkbox.value;
                        option.textContent = studentName;
                        leaderSelect.appendChild(option);
                    });
                } else {
                    leaderSection.style.display = 'none';
                }
            }

            // Auto-load students if classroom is pre-selected
            const preSelectedClassroom = classRoomSelect.value;
            if (preSelectedClassroom) {
                console.log('Pre-selected classroom detected:', preSelectedClassroom);
                loadStudents(preSelectedClassroom);
            }
        });
    </script>
    @endpush
</x-app-layout>