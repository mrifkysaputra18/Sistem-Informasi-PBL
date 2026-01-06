<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Kode OTP Anda telah diverifikasi. Silakan masukkan password baru Anda.
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('success')" />

    <form method="POST" action="{{ route('password.otp.update') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Password Baru" />
            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full pr-10" 
                              type="password" 
                              name="password" 
                              required />
                <button type="button" 
                        onclick="togglePasswordVisibility('password', 'togglePasswordIcon')"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center mt-1">
                    <i id="togglePasswordIcon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter</p>
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Konfirmasi Password" />
            <div class="relative">
                <x-text-input id="password_confirmation" class="block mt-1 w-full pr-10"
                              type="password"
                              name="password_confirmation"
                              required />
                <button type="button" 
                        onclick="togglePasswordVisibility('password_confirmation', 'toggleConfirmPasswordIcon')"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center mt-1">
                    <i id="toggleConfirmPasswordIcon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                Reset Password
            </x-primary-button>
        </div>
    </form>

    @push('scripts')
    <script>
        // Toggle password visibility
        function togglePasswordVisibility(inputId, iconId) {
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
    </script>
    @endpush
</x-guest-layout>
