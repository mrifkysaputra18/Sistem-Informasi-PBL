<x-guest-layout>
    <!-- Logo Politala -->
    <div class="flex justify-center mb-6">
        <img src="{{ asset('images/logo/politala.png') }}" 
             alt="Logo Politeknik Negeri Tanah Laut" 
             class="h-24 w-auto object-contain">
    </div>

    <!-- Title -->
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-1">Daftar Akun Baru</h2>
        <p class="text-sm text-gray-600">Sistem Informasi PBL - Politeknik Negeri Tanah Laut</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Role -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('Role')" />
            <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm" required onchange="toggleNimField()">
                <option value="">Pilih Role</option>
                <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- NIM (only for mahasiswa) -->
        <div class="mt-4" id="nim-field" style="display: none;">
            <x-input-label for="nim" :value="__('NIM')" />
            <x-text-input id="nim" class="block mt-1 w-full" type="text" name="nim" :value="old('nim')" autocomplete="nim" />
            <x-input-error :messages="$errors->get('nim')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        function toggleNimField() {
            const roleSelect = document.getElementById('role');
            const nimField = document.getElementById('nim-field');
            const nimInput = document.getElementById('nim');
            
            if (roleSelect.value === 'mahasiswa') {
                nimField.style.display = 'block';
                nimInput.required = true;
            } else {
                nimField.style.display = 'none';
                nimInput.required = false;
                nimInput.value = '';
            }
        }

        // Check on page load if role is already selected
        document.addEventListener('DOMContentLoaded', function() {
            toggleNimField();
        });
    </script>
</x-guest-layout>
