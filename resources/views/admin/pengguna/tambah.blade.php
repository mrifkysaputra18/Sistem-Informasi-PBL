<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header Sederhana -->
            <div class="mb-8 text-center">
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Tambah User Baru</h2>
                <p class="mt-2 text-sm text-gray-600">Lengkapi formulir di bawah ini untuk mendaftarkan pengguna baru.</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    <div class="p-8 space-y-8">
                        
                        <!-- 1. Informasi Dasar -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-3 mb-6">
                                Informasi Dasar
                            </h3>
                            <div class="grid grid-cols-1 gap-6">
                                <!-- Nama -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors @error('name') border-red-500 @enderror"
                                           placeholder="Masukkan nama lengkap">
                                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors @error('email') border-red-500 @enderror"
                                           placeholder="nama@email.com">
                                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- 2. Peran & Identitas -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-3 mb-6">
                                Peran & Identitas
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Role -->
                                <div>
                                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role Pengguna</label>
                                    <select name="role" id="role" required
                                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm cursor-pointer @error('role') border-red-500 @enderror">
                                        <option value="">Pilih Role...</option>
                                        <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                        <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                                        <option value="koordinator" {{ old('role') == 'koordinator' ? 'selected' : '' }}>Koordinator</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    @error('role') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- NIM (Conditional) -->
                                <div id="nim-container" class="hidden">
                                    <label for="nim" class="block text-sm font-medium text-gray-700 mb-1">Nomor Induk (NIM)</label>
                                    <input type="text" name="nim" id="nim" value="{{ old('nim') }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors @error('nim') border-red-500 @enderror"
                                           placeholder="Contoh: 234108...">
                                    @error('nim') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Kelas (Conditional) -->
                                <div id="class-container" class="hidden">
                                    <label for="class_room_id" class="block text-sm font-medium text-gray-700 mb-1">Kelas Akademik</label>
                                    <select name="class_room_id" id="class_room_id"
                                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm cursor-pointer @error('class_room_id') border-red-500 @enderror">
                                        <option value="">Pilih Kelas...</option>
                                        @foreach($classRooms as $classroom)
                                            <option value="{{ $classroom->id }}" {{ old('class_room_id') == $classroom->id ? 'selected' : '' }}>
                                                {{ $classroom->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('class_room_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Program Studi -->
                                <div>
                                    <label for="program_studi" class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                                    <input type="text" name="program_studi" id="program_studi" value="{{ old('program_studi', 'Teknik Informatika') }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm bg-gray-50">
                                </div>
                            </div>
                        </div>

                        <!-- 3. Keamanan -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-3 mb-6">
                                Keamanan Akun
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Password -->
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                    <input type="password" name="password" id="password" required
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors @error('password') border-red-500 @enderror">
                                    <p class="mt-1 text-xs text-gray-500">Min. 8 karakter.</p>
                                    @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Konfirmasi Password -->
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Ulangi Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" required
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors">
                                </div>
                                
                                <!-- Status -->
                                <div class="md:col-span-2">
                                    <div class="flex items-center">
                                        <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} 
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded cursor-pointer">
                                        <label for="is_active" class="ml-2 block text-sm text-gray-900 cursor-pointer">
                                            Aktifkan akun pengguna ini segera setelah dibuat.
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="bg-gray-50 px-8 py-5 flex items-center justify-between border-t border-gray-200">
                        <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                            Kembali
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-lg shadow-sm hover:shadow transition-all transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Simple Helper Text -->
            <p class="mt-6 text-center text-xs text-gray-400">
                Pastikan seluruh data yang bertanda wajib diisi dengan benar sebelum menyimpan.
            </p>
        </div>
    </div>

    @push('scripts')
    <script>
        const roleSelect = document.getElementById('role');
        const nimContainer = document.getElementById('nim-container');
        const classContainer = document.getElementById('class-container');
        const nimInput = document.getElementById('nim');
        const classSelect = document.getElementById('class_room_id');

        function updateFields() {
            const role = roleSelect.value;
            
            if (role === 'mahasiswa') {
                nimContainer.classList.remove('hidden');
                classContainer.classList.remove('hidden');
                nimInput.required = true;
                // classSelect.required = true; // Opsional sesuai kebutuhan
            } else {
                nimContainer.classList.add('hidden');
                classContainer.classList.add('hidden');
                nimInput.required = false;
                // classSelect.required = false;
                nimInput.value = ''; // Reset value
                classSelect.value = '';
            }
        }

        roleSelect.addEventListener('change', updateFields);
        // Run on load in case of validation error redirect
        updateFields();
    </script>
    @endpush
</x-app-layout>