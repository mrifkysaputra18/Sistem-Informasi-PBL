<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-2 px-4 py-2 text-white bg-white/20 hover:bg-white/30 rounded-lg transition-colors">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Sinkronkan ke Kriteria') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded relative" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
            @endif

            @if (session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded relative" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Sync Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fa-solid fa-arrows-rotate mr-2 text-indigo-600"></i>
                            Sinkronkan Nilai Target ke Kriteria
                        </h3>

                        <p class="text-sm text-gray-500 mb-6">
                            Fitur ini akan menghitung rata-rata nilai weekly target dan mengisinya ke kriteria penilaian AHP secara otomatis.
                        </p>

                        <form action="{{ route('sync-kriteria.sync') }}" method="POST" id="syncForm">
                            @csrf

                            <!-- Info about syncing all classes -->
                            <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <div class="flex items-center gap-2 text-blue-800">
                                    <i class="fa-solid fa-info-circle"></i>
                                    <span class="font-medium">Sinkronisasi akan dilakukan untuk semua kelas Anda</span>
                                </div>
                            </div>

                            <!-- Criteria Selection -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Pilih Kriteria untuk Disinkronkan <span class="text-red-500">*</span>
                                </label>
                                <div class="space-y-3 bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    @foreach($criteria as $criterion)
                                    <label class="flex items-center gap-3 p-3 rounded-lg border bg-white cursor-pointer hover:border-indigo-300 transition-colors">
                                        <input type="checkbox" 
                                               name="criteria_ids[]" 
                                               value="{{ $criterion->id }}"
                                               class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <div class="flex-1">
                                            <span class="font-medium text-gray-900">{{ $criterion->nama }}</span>
                                            <span class="ml-2 text-xs text-gray-500">(Bobot: {{ number_format($criterion->bobot * 100, 1) }}%)</span>
                                        </div>
                                        <!-- Source info -->
                                        <span class="text-xs px-2 py-1 rounded bg-indigo-50 text-indigo-700">
                                            @if(str_contains(strtolower($criterion->nama), 'kecepatan'))
                                                ← Avg % Todo Verified
                                            @elseif(str_contains(strtolower($criterion->nama), 'nilai') && str_contains(strtolower($criterion->nama), 'akhir'))
                                                ← Avg Quality Score
                                            @elseif(str_contains(strtolower($criterion->nama), 'ketepatan'))
                                                ← % On-time Submit
                                            @else
                                                ← Avg Final Score
                                            @endif
                                        </span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-3">
                                <button type="submit" 
                                        class="flex-1 py-3 px-6 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg flex items-center justify-center gap-2 transition-colors">
                                    <i class="fa-solid fa-arrows-rotate"></i>
                                    Sinkronkan Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sync History -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fa-solid fa-clock-rotate-left mr-2 text-gray-600"></i>
                            Riwayat Sinkronisasi
                        </h3>

                        @if($syncLogs->isEmpty())
                        <div class="text-center py-8 text-gray-400">
                            <i class="fa-solid fa-inbox text-4xl mb-2"></i>
                            <p class="text-sm">Belum ada riwayat sinkronisasi</p>
                        </div>
                        @else
                        <div class="space-y-3 max-h-[500px] overflow-y-auto">
                            @foreach($syncLogs as $log)
                            <div class="p-3 rounded-lg border {{ $log->is_reverted ? 'bg-gray-50 border-gray-200' : 'bg-green-50 border-green-200' }}">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-medium text-sm text-gray-900">
                                        {{ $log->classRoom->name ?? 'Semua Kelas' }}
                                    </span>
                                    @if($log->is_reverted)
                                    <span class="text-xs px-2 py-1 rounded bg-gray-200 text-gray-600">Dibatalkan</span>
                                    @else
                                    <form action="{{ route('sync-kriteria.unsync', $log->id) }}" method="POST" 
                                          onsubmit="return confirm('Yakin ingin membatalkan sinkronisasi ini? Nilai akan dikembalikan ke sebelumnya.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-xs px-2 py-1 rounded bg-red-100 text-red-600 hover:bg-red-200 transition-colors">
                                            <i class="fa-solid fa-undo mr-1"></i>Batalkan
                                        </button>
                                    </form>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500">
                                    <div><i class="fa-solid fa-user mr-1"></i>{{ $log->syncedByUser->name ?? 'Unknown' }}</div>
                                    <div><i class="fa-solid fa-calendar mr-1"></i>{{ $log->synced_at->format('d/m/Y H:i') }}</div>
                                    <div><i class="fa-solid fa-list mr-1"></i>{{ count($log->criteria_synced) }} kriteria</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
