<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('groups.index') }}" class="mr-4 text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Edit Kelompok: ') }} {{ $group->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('groups.update', $group) }}" id="editGroupForm">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- LEFT COLUMN: Group Info -->
                            <div>
                                <h3 class="text-lg font-semibold mb-4 text-gray-800">📋 Informasi Kelompok</h3>
                                
                                <!-- Pilih Kelas (Disabled/Readonly) -->
                                <div class="mb-4">
                                    <label for="class_room_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Kelas <span class="text-red-500">*</span>
                                        <span class="text-xs text-gray-500 ml-2">
                                            <i class="fas fa-lock"></i> Tidak bisa diubah
                                        </span>
                                    </label>
                                    <input type="hidden" name="class_room_id" value="{{ $group->class_room_id }}">
                                    <input type="text" 
                                           value="{{ $group->classRoom->name ?? '-' }}" 
                                           disabled
                                           class="w-full rounded-md border-gray-300 bg-gray-100 text-gray-600 cursor-not-allowed">
                                </div>

                                <!-- Nama Kelompok -->
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Kelompok <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" required
                                        value="{{ old('name', $group->name) }}"
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
                                        value="{{ old('max_members', $group->max_members) }}"
                                        min="1" max="10"
                                        onchange="checkMaxMembers()"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('max_members') border-red-500 @enderror">
                                    <p class="mt-1 text-xs text-gray-500">Maksimal anggota yang bisa bergabung (default: 5)</p>
                                    @error('max_members')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- RIGHT COLUMN: Members Selection -->
                            <div>
                                <h3 class="text-lg font-semibold mb-4 text-gray-800">👥 Kelola Anggota Kelompok</h3>
                                
                                <!-- Current Members Info -->
                                <div class="bg-blue-50 border-l-4 border-blue-400 p-3 mb-3 rounded-r">
                                    <p class="text-xs text-blue-800">
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Anggota saat ini:</strong> {{ $group->members->count() }} mahasiswa
                                    </p>
                                </div>

                                <div class="mb-4">
                                    <p class="text-xs text-gray-600 mb-2">
                                        <i class="fas fa-hand-pointer text-blue-500"></i>
                                        Centang untuk menambahkan mahasiswa, atau hilangkan centang untuk menghapus
                                    </p>
                                    
                                    <div class="flex gap-2 mb-3">
                                        <input type="text" id="searchMember" placeholder="🔍 Cari mahasiswa..." 
                                            class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500">
                                        <button type="button" id="selectAllBtn" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md text-xs font-medium transition-colors">
                                            <i class="fas fa-check-double mr-1"></i>Pilih Semua
                                        </button>
                                    </div>
                                    
                                    <div class="border-2 border-gray-300 rounded-lg p-3 max-h-96 overflow-y-auto bg-white shadow-inner">
                                        <div id="studentsList">
                                            <div class="text-center py-8">
                                                <i class="fas fa-spinner fa-spin text-blue-500 text-3xl mb-3"></i>
                                                <p class="text-sm text-gray-600">Memuat daftar mahasiswa...</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3 flex items-center justify-between bg-blue-50 border border-blue-200 rounded-lg p-2 transition-colors">
                                        <p class="text-sm text-blue-800">
                                            <i class="fas fa-check-circle"></i>
                                            <span class="font-semibold"><span id="selectedCount">0</span>/{{ $group->max_members }} mahasiswa</span> dipilih
                                        </p>
                                    </div>
                                </div>

                                <!-- Leader Selection -->
                                <div class="mb-4" id="leaderSection">
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
                                <i class="fas fa-save mr-2"></i>Update Kelompok
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
                        <h4 class="font-semibold text-blue-900 mb-2">💡 Panduan Edit Kelompok</h4>
                        <ul class="text-sm text-blue-800 space-y-1 list-disc list-inside">
                            <li>Centang mahasiswa untuk menambahkan atau menghapus dari kelompok</li>
                            <li>Pilih ketua dari anggota yang sudah dicentang</li>
                            <li>Kelas tidak dapat diubah setelah kelompok dibuat</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const studentsList = document.getElementById('studentsList');
            const selectAllBtn = document.getElementById('selectAllBtn');
            const searchMemberInput = document.getElementById('searchMember');
            const leaderSelect = document.getElementById('leader_id');
            const selectedCountSpan = document.getElementById('selectedCount');
            
            const groupId = {{ $group->id }};
            const classRoomId = {{ $group->class_room_id }};
            
            console.log('Loading students for class:', classRoomId);
            
            // Load all students from this class
            const url = `{{ route('groups.available-students') }}?class_room_id=${classRoomId}`;
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    // Get current members
                    const currentMembers = @json($group->members->pluck('user_id')->toArray());
                    const currentLeaderId = {{ $group->leader_id ?? 'null' }};
                    
                    console.log('Current members:', currentMembers);
                    console.log('Current leader:', currentLeaderId);
                    
                    // Add current members to available students if not already there
                    const currentMembersData = {!! json_encode($group->members->map(function($m) {
                        return [
                            'id' => $m->user->id,
                            'name' => $m->user->name,
                            'email' => $m->user->email,
                            'nim' => $m->user->nim
                        ];
                    })->values()) !!};
                    
                    // Merge current members with available students
                    const allStudents = [...currentMembersData];
                    data.students.forEach(student => {
                        if (!currentMembers.includes(student.id)) {
                            allStudents.push(student);
                        }
                    });
                    
                    // Sort by name
                    allStudents.sort((a, b) => a.name.localeCompare(b.name));
                    
                    if (allStudents.length === 0) {
                        studentsList.innerHTML = '<div class="text-center py-8"><i class="fas fa-user-slash text-gray-300 text-4xl mb-3"></i><p class="text-sm font-medium text-gray-600">Tidak Ada Mahasiswa</p></div>';
                        return;
                    }
                    
                    // Build students list HTML
                    let html = '';
                    allStudents.forEach((student, index) => {
                        const isChecked = currentMembers.includes(student.id) ? 'checked' : '';
                        html += `
                            <label class="flex items-start p-3 mb-2 hover:bg-blue-50 border border-transparent hover:border-blue-300 rounded-lg cursor-pointer transition-all duration-200 student-item" data-name="${student.name.toLowerCase()}">
                                <input type="checkbox" name="members[]" value="${student.id}" ${isChecked}
                                    class="mt-1 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 member-checkbox w-4 h-4">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-semibold text-gray-800">${student.name}</span>
                                        <span class="text-xs text-gray-400 ml-2">#${index + 1}</span>
                                    </div>
                                    <div class="mt-0.5 flex items-center gap-2">
                                        <span class="text-xs text-blue-600 font-medium">${student.nim || 'N/A'}</span>
                                        <span class="text-xs text-gray-400">•</span>
                                        <span class="text-xs text-gray-500">${student.email}</span>
                                    </div>
                                </div>
                            </label>
                        `;
                    });
                    
                    studentsList.innerHTML = html;
                    
                    // Attach event listeners
                    attachCheckboxListeners();
                    
                    // Initial update of leader options
                    updateLeaderOptions();
                })
                .catch(error => {
                    console.error('Error:', error);
                    studentsList.innerHTML = '<div class="text-center py-8"><i class="fas fa-exclamation-triangle text-red-500 text-3xl mb-3"></i><p class="text-sm text-red-600">Error loading students</p></div>';
                });
            
            function attachCheckboxListeners() {
                const memberCheckboxes = document.querySelectorAll('.member-checkbox');
                memberCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        checkMaxMembers();
                        updateLeaderOptions();
                    });
                });
                updateLeaderOptions();
                checkMaxMembers();
            }
            
            function checkMaxMembers() {
                const maxMembers = parseInt(document.getElementById('max_members').value) || 5;
                const memberCheckboxes = document.querySelectorAll('.member-checkbox');
                const checked = Array.from(memberCheckboxes).filter(cb => cb.checked);
                
                // Update counter dengan warna
                selectedCountSpan.textContent = checked.length;
                const counterContainer = selectedCountSpan.closest('.bg-blue-50');
                
                if (checked.length > maxMembers) {
                    // Over limit - red warning
                    counterContainer.classList.remove('bg-blue-50', 'border-blue-200');
                    counterContainer.classList.add('bg-red-50', 'border-red-300');
                    selectedCountSpan.closest('p').classList.remove('text-blue-800');
                    selectedCountSpan.closest('p').classList.add('text-red-800');
                    selectedCountSpan.closest('p').innerHTML = `<i class="fas fa-exclamation-triangle"></i> <span class="font-semibold"><span id="selectedCount">${checked.length}</span>/${maxMembers} mahasiswa</span> - <strong>Melebihi batas!</strong>`;
                    
                    // Disable unchecked checkboxes
                    memberCheckboxes.forEach(cb => {
                        if (!cb.checked) {
                            cb.disabled = true;
                            cb.closest('.student-item').classList.add('opacity-50', 'cursor-not-allowed');
                        }
                    });
                } else if (checked.length === maxMembers) {
                    // At limit - yellow warning
                    counterContainer.classList.remove('bg-blue-50', 'border-blue-200', 'bg-red-50', 'border-red-300');
                    counterContainer.classList.add('bg-yellow-50', 'border-yellow-300');
                    selectedCountSpan.closest('p').classList.remove('text-blue-800', 'text-red-800');
                    selectedCountSpan.closest('p').classList.add('text-yellow-800');
                    selectedCountSpan.closest('p').innerHTML = `<i class="fas fa-check-circle"></i> <span class="font-semibold"><span id="selectedCount">${checked.length}</span>/${maxMembers} mahasiswa</span> - <strong>Penuh!</strong>`;
                    
                    // Disable unchecked checkboxes
                    memberCheckboxes.forEach(cb => {
                        if (!cb.checked) {
                            cb.disabled = true;
                            cb.closest('.student-item').classList.add('opacity-50', 'cursor-not-allowed');
                        }
                    });
                } else {
                    // Under limit - normal
                    counterContainer.classList.remove('bg-yellow-50', 'border-yellow-300', 'bg-red-50', 'border-red-300');
                    counterContainer.classList.add('bg-blue-50', 'border-blue-200');
                    selectedCountSpan.closest('p').classList.remove('text-yellow-800', 'text-red-800');
                    selectedCountSpan.closest('p').classList.add('text-blue-800');
                    selectedCountSpan.closest('p').innerHTML = `<i class="fas fa-check-circle"></i> <span class="font-semibold"><span id="selectedCount">${checked.length}</span>/${maxMembers} mahasiswa</span> dipilih`;
                    
                    // Enable all checkboxes
                    memberCheckboxes.forEach(cb => {
                        cb.disabled = false;
                        cb.closest('.student-item').classList.remove('opacity-50', 'cursor-not-allowed');
                    });
                }
            }
            
            // Select All functionality
            selectAllBtn.addEventListener('click', function() {
                const maxMembers = parseInt(document.getElementById('max_members').value) || 5;
                const visibleCheckboxes = Array.from(document.querySelectorAll('.student-item'))
                    .filter(item => item.style.display !== 'none')
                    .map(item => item.querySelector('.member-checkbox'));
                
                const allChecked = visibleCheckboxes.every(cb => cb.checked);
                
                if (!allChecked) {
                    // Check only up to max_members
                    let count = Array.from(document.querySelectorAll('.member-checkbox:checked')).length;
                    visibleCheckboxes.forEach(checkbox => {
                        if (!checkbox.checked && count < maxMembers) {
                            checkbox.checked = true;
                            count++;
                        }
                    });
                    
                    if (count >= maxMembers) {
                        alert(`⚠️ Maksimal ${maxMembers} anggota. Tidak bisa menambah lebih banyak.`);
                    }
                } else {
                    visibleCheckboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });
                }
                
                const anyChecked = visibleCheckboxes.some(cb => cb.checked);
                this.innerHTML = anyChecked 
                    ? '<i class="fas fa-times mr-1"></i>Batal Pilih'
                    : '<i class="fas fa-check-double mr-1"></i>Pilih Semua';
                
                checkMaxMembers();
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
                
                selectAllBtn.style.display = visibleCount > 0 ? 'block' : 'none';
            });

            function updateLeaderOptions() {
                const memberCheckboxes = document.querySelectorAll('.member-checkbox');
                const checked = Array.from(memberCheckboxes).filter(cb => cb.checked);
                const currentLeaderId = {{ $group->leader_id ?? 'null' }};
                
                selectedCountSpan.textContent = checked.length;
                
                leaderSelect.innerHTML = '<option value="">-- Pilih Ketua --</option>';
                
                if (checked.length > 0) {
                    checked.forEach(checkbox => {
                        const label = checkbox.closest('.student-item');
                        const studentName = label.querySelector('.text-sm.font-semibold').textContent;
                        const option = document.createElement('option');
                        option.value = checkbox.value;
                        option.textContent = studentName;
                        
                        // Select current leader
                        if (currentLeaderId && checkbox.value == currentLeaderId) {
                            option.selected = true;
                        }
                        
                        leaderSelect.appendChild(option);
                    });
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
