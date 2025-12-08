<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="space-y-1">
                <h2 class="font-bold text-2xl text-white leading-tight flex items-center gap-2">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#fff"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#fff"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#fff"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#fff"/>
                    </svg>
                    Pengaturan Google Drive
                </h2>
                <p class="text-sm text-white/90">Kelola penyimpanan file ke Google Drive</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
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

            <!-- Main Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                @if($authType === 'oauth' && $oauthEmail)
                    <!-- Connected State -->
                    <div class="p-8">
                        <!-- Account Info -->
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-14 h-14 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-lg font-semibold text-gray-900">{{ $oauthName ?? 'Google Drive' }}</p>
                                <p class="text-sm text-gray-500">{{ $oauthEmail }}</p>
                            </div>
                            <form action="{{ route('settings.google-drive.disconnect') }}" method="POST" id="form-putuskan">
                                @csrf
                                <button type="button" onclick="konfirmasiPutuskan()" class="px-4 py-2 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">
                                    Putuskan
                                </button>
                            </form>
                        </div>

                        <!-- Storage Info -->
                        @if($storageInfo && $storageInfo['success'])
                        @php
                            $quota = $storageInfo['quota'];
                            $percentUsed = $quota['percentage_used'] ?? 0;
                            $isUnlimited = $quota['limit'] === null;
                        @endphp
                        <div class="bg-gray-50 rounded-xl p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-semibold text-gray-900">Penyimpanan</h3>
                                <span class="text-sm text-gray-500">
                                    {{ $quota['usage_formatted'] }} / {{ $quota['limit_formatted'] }}
                                </span>
                            </div>
                            
                            <!-- Progress Bar -->
                            @if(!$isUnlimited)
                            <div class="w-full bg-gray-200 rounded-full h-3 mb-3">
                                <div class="h-3 rounded-full transition-all duration-500 {{ $percentUsed > 80 ? 'bg-red-500' : ($percentUsed > 60 ? 'bg-yellow-500' : 'bg-blue-500') }}"
                                     style="width: {{ min($percentUsed, 100) }}%"></div>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Terpakai: {{ $quota['usage_in_drive_formatted'] }}</span>
                                <span class="text-gray-600">Tersedia: {{ $quota['available_formatted'] }}</span>
                            </div>
                            @else
                            <div class="w-full bg-gray-200 rounded-full h-3 mb-3">
                                <div class="h-3 rounded-full bg-blue-500" style="width: 5%"></div>
                            </div>
                            <p class="text-sm text-gray-600">
                                Terpakai: {{ $quota['usage_in_drive_formatted'] }} 
                                <span class="text-gray-400">(Penyimpanan tidak terbatas)</span>
                            </p>
                            @endif
                        </div>
                        @endif

                        <!-- Folder Info -->
                        @if($folderInfo)
                        <div class="mt-4 flex items-center gap-3 text-sm text-gray-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                            </svg>
                            <span>Folder: <strong class="text-gray-700">{{ $folderInfo['name'] }}</strong></span>
                        </div>
                        @endif
                    </div>
                @else
                    <!-- Not Connected State -->
                    <div class="p-12 text-center">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-gray-400" viewBox="0 0 24 24">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Hubungkan Google Drive</h3>
                        <p class="text-gray-500 mb-8 max-w-sm mx-auto">
                            Simpan file progress mahasiswa langsung ke akun Google Drive Anda
                        </p>
                        
                        @if($oauthConfigured)
                        <a href="{{ route('settings.google-drive.connect') }}" 
                           class="inline-flex items-center gap-3 px-8 py-3 bg-white border-2 border-gray-200 hover:border-gray-300 hover:shadow-md rounded-full font-medium text-gray-700 transition-all">
                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                            Hubungkan dengan Google
                        </a>
                        @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-left max-w-md mx-auto">
                            <p class="text-sm text-yellow-800">
                                <strong>OAuth belum dikonfigurasi.</strong><br>
                                Tambahkan <code class="bg-yellow-100 px-1 rounded text-xs">GOOGLE_DRIVE_CLIENT_ID</code> dan 
                                <code class="bg-yellow-100 px-1 rounded text-xs">GOOGLE_DRIVE_CLIENT_SECRET</code> di file .env
                            </p>
                        </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function konfirmasiPutuskan() {
            Swal.fire({
                title: 'Putuskan Koneksi?',
                text: 'Akun Google Drive akan terputus dari sistem',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Putuskan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-lg px-5 py-2.5',
                    cancelButton: 'rounded-lg px-5 py-2.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-putuskan').submit();
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
