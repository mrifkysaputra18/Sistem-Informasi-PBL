<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Kami telah mengirimkan kode OTP 6-digit ke email Anda. Silakan periksa inbox atau folder spam.
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('success')" />

    <form method="POST" action="{{ route('password.otp.verify.submit') }}">
        @csrf

        <!-- OTP Code -->
       <div>
            <x-input-label for="otp_code" value="Kode OTP" />
            <x-text-input id="otp_code" class="block mt-1 w-full text-center text-2xl tracking-widest" 
                          type="text" 
                          name="otp_code" 
                          :value="old('otp_code')" 
                          required 
                          autofocus 
                          maxlength="6"
                          pattern="[0-9]{6}"
                          placeholder="000000" />
            <x-input-error :messages="$errors->get('otp_code')" class="mt-2" />
            <p class="mt-1 text-xs text-gray-500">Masukkan 6-digit kode OTP yang dikirim ke email Anda</p>
        </div>

        <div class="flex items-center justify-between mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.otp.request') }}">
                Request OTP Baru
            </a>

            <x-primary-button>
                Verifikasi
            </x-primary-button>
        </div>
    </form>

    <!-- Countdown Timer -->
    <div class="mt-4 text-center">
        <p class="text-sm text-gray-600">
            Kode OTP berlaku selama <span id="countdown" class="font-semibold text-red-600">10:00</span>
        </p>
    </div>

    @push('scripts')
    <script>
        // Countdown timer (10 minutes)
        let timeLeft = 600; // 10 minutes in seconds
        const countdownEl = document.getElementById('countdown');
        
        const timer = setInterval(() => {
            if (timeLeft <= 0) {
                clearInterval(timer);
                countdownEl.textContent = '00:00';
                countdownEl.classList.add('text-gray-400');
                countdownEl.classList.remove('text-red-600');
                return;
            }
            
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            countdownEl.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            timeLeft--;
        }, 1000);

        // Auto-focus and format OTP input
        const otpInput = document.getElementById('otp_code');
        otpInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
        });
    </script>
    @endpush
</x-guest-layout>
