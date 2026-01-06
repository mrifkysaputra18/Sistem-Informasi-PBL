<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Edit User') }}: {{ $user->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.users.show', $user) }}" 
                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                    <i class="fas fa-eye mr-2"></i>Detail
                </a>
                <a href="{{ route('admin.users.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg shadow-md">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-6">
                    @csrf
                    @method('PATCH')

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
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
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
                                <input type="text" name="nim" id="nim" value="{{ old('nim', $user->nim) }}"
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
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
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
                                    Password Baru
                                </label>
                                <div class="relative">
                                    <input type="password" name="password" id="password"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('password') border-red-500 @enderror pr-10">
                                    <button type="button" onclick="togglePassword('password', 'togglePasswordIcon')"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i id="togglePasswordIcon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password</p>
                            </div>

                            <!-- Password Confirmation -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                    Konfirmasi Password
                                </label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 pr-10">
                                    <button type="button" onclick="togglePassword('password_confirmation', 'toggleConfirmPasswordIcon')"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i id="toggleConfirmPasswordIcon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Role -->
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                                    Role <span class="text-red-500">*</span>
                                </label>
                                <select name="role" id="role" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('role') border-red-500 @enderror">
                                    <option value="mahasiswa" {{ old('role', $user->role) == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                    <option value="dosen" {{ old('role', $user->role) == 'dosen' ? 'selected' : '' }}>Dosen</option>
                                    <option value="koordinator" {{ old('role', $user->role) == 'koordinator' ? 'selected' : '' }}>Koordinator</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
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
                                    <option value="1" {{ old('is_active', $user->is_active) == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('is_active', $user->is_active) == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information Section -->
                    <div class="mb-8">
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
                                <input type="text" name="program_studi" id="program_studi" value="{{ old('program_studi', $user->program_studi) }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('program_studi') border-red-500 @enderror">
                                @error('program_studi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Class Room -->
                            <div>
                                <label for="class_room_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Kelas
                                </label>
                                <select name="class_room_id" id="class_room_id"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('class_room_id') border-red-500 @enderror">
                                    <option value="">Tidak ada kelas</option>
                                    @foreach($classRooms as $classroom)
                                        <option value="{{ $classroom->id }}" {{ old('class_room_id', $user->class_room_id) == $classroom->id ? 'selected' : '' }}>
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
                            <i class="fas fa-save mr-2"></i>Update User
                        </button>
                    </div>
                </form>
            </div>

            <!-- Warning Card -->
            @if($user->id === auth()->id())
            <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Perhatian!</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            Anda sedang mengedit akun Anda sendiri. Berhati-hatilah saat mengubah role atau status.
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        // Toggle password visibility
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Show/hide NIM field based on role
        document.getElementById('role').addEventListener('change', function() {
            const nimField = document.getElementById('nim-field');
            const nimInput = document.getElementById('nim');
            const nimRequired = document.getElementById('nim-required');
            
            if (this.value === 'mahasiswa') {
                nimField.style.display = 'block';
                nimInput.required = true;
                nimRequired.style.display = 'inline';
            } else {
                nimField.style.display = 'none';
                nimInput.required = false;
            }
        });

        // Trigger on page load
        document.getElementById('role').dispatchEvent(new Event('change'));
    </script>
    @endpush
</x-app-layout>
