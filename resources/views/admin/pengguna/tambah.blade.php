<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Tambah User Baru') }}
            </h2>
            <a href="{{ route('admin.users.index') }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form action="{{ route('admin.users.store') }}" method="POST" class="p-6">
                    @csrf

                    <!-- Personal Information Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-user-circle mr-2 text-primary-500"></i>
                            Informasi Pribadi
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- NIM (Only for students) -->
                            <div id="nim-field">
                                <label for="nim" class="block text-sm font-medium text-gray-700 mb-1">
                                    NIM <span class="text-red-500" id="nim-required">*</span>
                                </label>
                                <input type="text" name="nim" id="nim" value="{{ old('nim') }}"
                                       placeholder="Contoh: 2341080001"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('nim') border-red-500 @enderror">
                                @error('nim')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Wajib untuk mahasiswa</p>
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                       placeholder="nama@mhs.politala.ac.id"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Account Information Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-key mr-2 text-green-500"></i>
                            Informasi Akun
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password" id="password" required
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter</p>
                            </div>

                            <!-- Password Confirmation -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                    Konfirmasi Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500">
                            </div>

                            <!-- Role -->
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                                    Role <span class="text-red-500">*</span>
                                </label>
                                <select name="role" id="role" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('role') border-red-500 @enderror">
                                    <option value="">Pilih Role</option>
                                    <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                    <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                                    <option value="koordinator" {{ old('role') == 'koordinator' ? 'selected' : '' }}>Koordinator</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                @error('role')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="is_active" class="block text-sm font-medium text-gray-700 mb-1">
                                    Status
                                </label>
                                <select name="is_active" id="is_active"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500">
                                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information Section (For Students) -->
                    <div class="mb-8" id="academic-section">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-graduation-cap mr-2 text-secondary-500"></i>
                            Informasi Akademik
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Program Studi -->
                            <div>
                                <label for="program_studi" class="block text-sm font-medium text-gray-700 mb-1">
                                    Program Studi
                                </label>
                                <input type="text" name="program_studi" id="program_studi" value="{{ old('program_studi', 'Teknik Informatika') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('program_studi') border-red-500 @enderror">
                                @error('program_studi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Class Room (Only for students) -->
                            <div id="class-field">
                                <label for="class_room_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Kelas
                                </label>
                                <select name="class_room_id" id="class_room_id"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('class_room_id') border-red-500 @enderror">
                                    <option value="">Pilih Kelas (Opsional)</option>
                                    @foreach($classRooms as $classroom)
                                        <option value="{{ $classroom->id }}" {{ old('class_room_id') == $classroom->id ? 'selected' : '' }}>
                                            {{ $classroom->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_room_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Wajib untuk mahasiswa</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t">
                        <a href="{{ route('admin.users.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg shadow-md transition">
                            <i class="fas fa-times mr-2"></i>Batal
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 bg-primary-500 hover:bg-primary-600 text-white font-medium rounded-lg shadow-md transition">
                            <i class="fas fa-save mr-2"></i>Simpan User
                        </button>
                    </div>
                </form>
            </div>

            <!-- Help Card -->
            <div class="mt-6 bg-primary-50 border-l-4 border-primary-400 p-4 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-primary-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-primary-800">Tips</h3>
                        <div class="mt-2 text-sm text-primary-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Untuk mahasiswa, pastikan memilih kelas yang sesuai</li>
                                <li>Email harus unik dan valid</li>
                                <li>Password default yang aman: <code class="bg-primary-100 px-2 py-1 rounded">password</code> (minimal 8 karakter)</li>
                                <li>Dosen dan staff tidak memerlukan kelas</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Show/hide class field and NIM field based on role
        document.getElementById('role').addEventListener('change', function() {
            const classField = document.getElementById('class-field');
            const classRoomSelect = document.getElementById('class_room_id');
            const nimField = document.getElementById('nim-field');
            const nimInput = document.getElementById('nim');
            const nimRequired = document.getElementById('nim-required');
            
            if (this.value === 'mahasiswa') {
                classField.style.display = 'block';
                classRoomSelect.required = true;
                nimField.style.display = 'block';
                nimInput.required = true;
                nimRequired.style.display = 'inline';
            } else {
                classField.style.display = 'block'; // Still show but not required
                classRoomSelect.required = false;
                nimField.style.display = 'none';
                nimInput.required = false;
            }
        });

        // Trigger on page load
        document.getElementById('role').dispatchEvent(new Event('change'));
    </script>
    @endpush
</x-app-layout>
