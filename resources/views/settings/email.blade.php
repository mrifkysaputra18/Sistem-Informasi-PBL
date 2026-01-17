<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="space-y-1">
                <h2 class="font-bold text-2xl text-white leading-tight flex items-center gap-2">
                    📧 Pengaturan Email
                </h2>
                <p class="text-sm text-white/90">Kelola pengiriman email sistem via SMTP</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Success/Error Messages -->
            @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl shadow-md">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-md">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            <!-- SMTP Settings Form -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Konfigurasi SMTP</h3>
                    
                    <form method="POST" action="{{ route('settings.email.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Email Pengirim (Gmail Account) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Akun Gmail</label>
                            <input type="email" name="username" value="{{ old('username', $username) }}" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="">
                            @error('username')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Akun Gmail yang akan digunakan untuk mengirim email</p>
                        </div>

                        <!-- Password (App Password) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                App Password
                                @if($hasPassword)
                                    <span class="text-green-600 text-xs">(✓ Tersimpan)</span>
                                @endif
                            </label>
                            <input type="password" name="password" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="{{ $hasPassword ? 'Kosongkan jika tidak ingin mengubah' : 'Masukkan App Password dari Gmail' }}">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-xs text-yellow-800">
                                    <strong>Cara buat App Password:</strong><br>
                                    1. Buka <a href="https://myaccount.google.com/security" target="_blank" class="text-blue-600 underline">Google Account Security</a><br>
                                    2. Enable <strong>2-Step Verification</strong><br>
                                    3. Klik <strong>App passwords</strong> → Generate → Copy password<br>
                                    4. <strong class="text-red-700">PENTING: Hapus semua spasi sebelum paste!</strong>
                                </p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Pengirim</label>
                            <input type="text" name="from_name" value="{{ old('from_name', $fromName) }}" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="SMART PBL System">
                            @error('from_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Nama yang ditampilkan di email</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3 pt-4">
                            <button type="button" onclick="testConnection()"
                                    class="px-6 py-2.5 bg-white border-2 border-blue-600 text-blue-600 font-medium rounded-lg hover:bg-blue-50 transition-colors">
                                Test Koneksi
                            </button>
                            <button type="submit"
                                    class="flex-1 px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Card -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                <h3 class="font-semibold text-blue-900 mb-3">💡 Tips Penggunaan Gmail</h3>
                <div class="space-y-2 text-sm text-blue-800">
                    <p>✅ <strong>Akun Gmail:</strong> Bisa pakai email @politala.ac.id atau @gmail.com</p>
                    <p>✅ <strong>App Password:</strong> Wajib! Bukan password login Gmail biasa. <strong>Hapus semua spasi!</strong></p>
                    <p>✅ <strong>2-Step Verification:</strong> Harus aktif untuk generate App Password</p>
                    <p>✅ <strong>Test Koneksi:</strong> Simpan pengaturan terlebih dahulu, baru test koneksi</p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function testConnection() {
            const button = event.target;
            button.disabled = true;
            button.textContent = 'Mengirim test email...';

            fetch('{{ route("settings.email.test") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    test_email: '{{ auth()->user()->email }}'
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Koneksi Berhasil!',
                        text: data.message,
                        confirmButtonColor: '#3b82f6'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Koneksi Gagal',
                        text: data.error || 'Periksa kembali konfigurasi SMTP Anda',
                        confirmButtonColor: '#ef4444'
                    });
                }
            })
            .catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan: ' + err.message,
                    confirmButtonColor: '#ef4444'
                });
            })
            .finally(() => {
                button.disabled = false;
                button.textContent = 'Test Koneksi';
            });
        }
    </script>
    @endpush
</x-app-layout>
