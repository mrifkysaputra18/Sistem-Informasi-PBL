<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Informasi Profil
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Perbarui informasi profil dan alamat email akun Anda.
        </p>
    </header>

    <!-- Profile Photo Upload Section -->
    <div class="mt-6">
        <div class="flex items-center gap-6">
            <!-- Current Photo Preview -->
            <div class="relative group">
                <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-primary-100 shadow-lg transition-all duration-300 group-hover:border-primary-300">
                    <img id="profile-photo-preview" 
                         src="{{ $user->profile_photo_url }}" 
                         alt="{{ $user->name }}" 
                         class="w-full h-full object-cover">
                </div>
                <!-- Hover Overlay -->
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-full transition-all duration-300 flex items-center justify-center">
                    <i class="fas fa-camera text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-xl"></i>
                </div>
            </div>

            <!-- Photo Actions -->
            <div class="flex-1">
                <h3 class="text-sm font-semibold text-gray-900 mb-2">Foto Profil</h3>
                <p class="text-xs text-gray-600 mb-3">JPG, PNG. Max 2MB</p>
                
                <form id="photo-upload-form" method="post" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    
                    <!-- Hidden File Input -->
                    <input type="file" 
                           id="photo-input" 
                           name="photo" 
                           accept="image/jpeg,image/png,image/jpg"
                           class="hidden"
                           onchange="previewPhoto(this)">
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-2">
                        <button type="button" 
                                onclick="document.getElementById('photo-input').click()"
                                class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-lg shadow-md transition-all duration-200 hover:shadow-lg transform hover:scale-105">
                            <i class="fas fa-upload mr-2"></i>Upload Foto
                        </button>
                        
                        @if($user->profile_photo_path)
                        <button type="button"
                                onclick="if(confirm('Hapus foto profil?')) document.getElementById('delete-photo-form').submit()"
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg shadow-md transition-all duration-200 hover:shadow-lg transform hover:scale-105">
                            <i class="fas fa-trash mr-2"></i>Hapus Foto
                        </button>
                        @endif
                    </div>

                    <!-- Upload Button (Hidden by default, shown when file selected) -->
                    <button type="submit" 
                            id="upload-button"
                            class="hidden inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow-md transition-all duration-200">
                        <i class="fas fa-check mr-2"></i>Simpan Foto Baru
                    </button>

                    @error('photo')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </form>

                <!-- Delete Photo Form -->
                @if($user->profile_photo_path)
                <form id="delete-photo-form" method="post" action="{{ route('profile.photo.delete') }}" class="hidden">
                    @csrf
                    @method('delete')
                </form>
                @endif
            </div>
        </div>
    </div>

    <div class="border-t border-gray-200 my-6"></div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Nama" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        @if($user->role === 'mahasiswa')
        <div>
            <x-input-label for="nim" value="NIM" />
            <x-text-input id="nim" name="nim" type="text" class="mt-1 block w-full" :value="old('nim', $user->nim)" autocomplete="nim" />
            <x-input-error class="mt-2" :messages="$errors->get('nim')" />
        </div>
        @endif

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full bg-gray-100 cursor-not-allowed" :value="old('email', $user->email)" required autocomplete="username" readonly disabled />
            <p class="mt-1 text-xs text-gray-500">Email tidak dapat diubah karena digunakan untuk login</p>
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Simpan</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >Tersimpan.</p>
            @endif
        </div>
    </form>

    <!-- Photo Preview Script -->
    <script>
        function previewPhoto(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                
                // Validate file size (max 2MB)
                if (file.size > 2048 * 1024) {
                    alert('Ukuran file terlalu besar! Maksimal 2MB.');
                    input.value = '';
                    return;
                }
                
                // Validate file type
                if (!['image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
                    alert('Format file tidak didukung! Gunakan JPG atau PNG.');
                    input.value = '';
                    return;
                }
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-photo-preview').src = e.target.result;
                    document.getElementById('upload-button').classList.remove('hidden');
                    document.getElementById('upload-button').classList.add('inline-flex');
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</section>
