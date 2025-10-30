<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-white leading-tight">
                    {{ __('Perhitungan Bobot Kriteria (AHP)') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Analytical Hierarchy Process - Menentukan bobot kriteria secara objektif
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('ahp.help') }}" 
                   class="bg-primary-500 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                    <i class="fas fa-question-circle mr-2"></i>Panduan AHP
                </a>
                <a href="{{ route('criteria.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg shadow-md">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg shadow-md">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Segment Selector -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-sliders-h mr-2 text-secondary-500"></i>Pilih Segment Kriteria
                    </h3>
                    <div class="flex gap-4">
                        <a href="{{ route('ahp.index', ['segment' => 'group']) }}" 
                           class="flex-1 p-4 border-2 rounded-lg transition duration-300 {{ $segment == 'group' ? 'border-secondary-500 bg-secondary-50' : 'border-gray-300 hover:border-secondary-300' }}">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="font-semibold text-gray-900">
                                        <i class="fas fa-users-rectangle mr-2"></i>Kriteria Kelompok
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        {{ \App\Models\Criterion::where('segment', 'group')->count() }} kriteria
                                    </div>
                                </div>
                                @if($segment == 'group')
                                    <i class="fas fa-check-circle text-secondary-500 text-2xl"></i>
                                @endif
                            </div>
                        </a>
                        <a href="{{ route('ahp.index', ['segment' => 'student']) }}" 
                           class="flex-1 p-4 border-2 rounded-lg transition duration-300 {{ $segment == 'student' ? 'border-secondary-500 bg-secondary-50' : 'border-gray-300 hover:border-secondary-300' }}">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="font-semibold text-gray-900">
                                        <i class="fas fa-user-graduate mr-2"></i>Kriteria Mahasiswa
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        {{ \App\Models\Criterion::where('segment', 'student')->count() }} kriteria
                                    </div>
                                </div>
                                @if($segment == 'student')
                                    <i class="fas fa-check-circle text-secondary-500 text-2xl"></i>
                                @endif
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- AHP Info -->
            <div class="bg-gradient-to-r from-secondary-50 to-primary-50 border-l-4 border-secondary-600 p-6 rounded-r-xl shadow-lg mb-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="bg-secondary-100 p-3 rounded-full">
                            <i class="fas fa-calculator text-secondary-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-lg font-bold text-secondary-900 mb-2">
                            Metode AHP (Analytical Hierarchy Process)
                        </h3>
                        <p class="text-sm text-secondary-800 mb-3">
                            Metode untuk menentukan bobot kriteria berdasarkan perbandingan berpasangan antar kriteria.
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div class="bg-white bg-opacity-50 p-3 rounded-lg">
                                <div class="font-semibold text-secondary-900 mb-1">
                                    <i class="fas fa-balance-scale mr-1"></i>Perbandingan
                                </div>
                                <div class="text-secondary-700">Bandingkan setiap pasang kriteria</div>
                            </div>
                            <div class="bg-white bg-opacity-50 p-3 rounded-lg">
                                <div class="font-semibold text-secondary-900 mb-1">
                                    <i class="fas fa-percent mr-1"></i>Bobot Otomatis
                                </div>
                                <div class="text-secondary-700">Hitung bobot secara matematis</div>
                            </div>
                            <div class="bg-white bg-opacity-50 p-3 rounded-lg">
                                <div class="font-semibold text-secondary-900 mb-1">
                                    <i class="fas fa-check-double mr-1"></i>Konsistensi
                                </div>
                                <div class="text-secondary-700">Validasi dengan CR ≤ 0.1</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comparison Matrix -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-table mr-2 text-orange-500"></i>Matriks Perbandingan Berpasangan
                        </h3>
                        <form action="{{ route('ahp.reset') }}" method="POST" class="inline" 
                              onsubmit="return confirm('Yakin ingin mereset semua perbandingan?')">
                            @csrf
                            <input type="hidden" name="segment" value="{{ $segment }}">
                            <button type="submit" 
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                                <i class="fas fa-redo mr-2"></i>Reset
                            </button>
                        </form>
                    </div>

                    <p class="text-sm text-gray-600 mb-4">
                        <i class="fas fa-info-circle mr-1"></i>
                        Bandingkan kriteria A dengan kriteria B. Gunakan skala 1-9 (1 = sama penting, 9 = sangat lebih penting).
                    </p>

                    @if(count($comparisons) > 0)
                        <div class="space-y-6">
                            @foreach($comparisons as $index => $comparison)
                                <div class="border-2 border-gray-200 rounded-lg p-6 hover:bg-gray-50 transition duration-200">
                                    <!-- Header Kriteria -->
                                    <div class="grid grid-cols-2 gap-4 mb-6">
                                        <div class="text-center">
                                            <div class="bg-primary-100 rounded-lg p-4 shadow-md">
                                                <div class="font-bold text-primary-900 text-lg">{{ $comparison['criterion_a']->nama }}</div>
                                                <div class="text-xs text-primary-600 mt-1">← Pilih di Kiri jika ini lebih penting</div>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <div class="bg-green-100 rounded-lg p-4 shadow-md">
                                                <div class="font-bold text-green-900 text-lg">{{ $comparison['criterion_b']->nama }}</div>
                                                <div class="text-xs text-green-600 mt-1">Pilih di Kanan jika ini lebih penting →</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Dual-Side Value Selection -->
                                    <div class="bg-gradient-to-r from-blue-50 via-gray-50 to-green-50 rounded-xl p-6">
                                        <div class="grid grid-cols-2 gap-1">
                                            <!-- LEFT SIDE: Kriteria A lebih penting -->
                                            <div class="space-y-2">
                                                <div class="text-center text-xs font-bold text-primary-700 mb-3">
                                                    ← {{ $comparison['criterion_a']->nama }} Lebih Penting
                                                </div>
                                                <button type="button"
                                                        class="ahp-value-btn-left {{ $comparison['value'] == 9 ? 'active' : '' }}"
                                                        data-value="9"
                                                        data-a-id="{{ $comparison['criterion_a']->id }}"
                                                        data-b-id="{{ $comparison['criterion_b']->id }}"
                                                        data-segment="{{ $segment }}"
                                                        onclick="selectValue(this)">
                                                    <div class="text-xl font-bold">9</div>
                                                    <div class="text-xs">Mutlak Lebih Penting</div>
                                                </button>
                                                <button type="button"
                                                        class="ahp-value-btn-left {{ $comparison['value'] == 7 ? 'active' : '' }}"
                                                        data-value="7"
                                                        data-a-id="{{ $comparison['criterion_a']->id }}"
                                                        data-b-id="{{ $comparison['criterion_b']->id }}"
                                                        data-segment="{{ $segment }}"
                                                        onclick="selectValue(this)">
                                                    <div class="text-xl font-bold">7</div>
                                                    <div class="text-xs">Jauh Lebih Penting</div>
                                                </button>
                                                <button type="button"
                                                        class="ahp-value-btn-left {{ $comparison['value'] == 5 ? 'active' : '' }}"
                                                        data-value="5"
                                                        data-a-id="{{ $comparison['criterion_a']->id }}"
                                                        data-b-id="{{ $comparison['criterion_b']->id }}"
                                                        data-segment="{{ $segment }}"
                                                        onclick="selectValue(this)">
                                                    <div class="text-xl font-bold">5</div>
                                                    <div class="text-xs">Sangat Penting</div>
                                                </button>
                                                <button type="button"
                                                        class="ahp-value-btn-left {{ $comparison['value'] == 3 ? 'active' : '' }}"
                                                        data-value="3"
                                                        data-a-id="{{ $comparison['criterion_a']->id }}"
                                                        data-b-id="{{ $comparison['criterion_b']->id }}"
                                                        data-segment="{{ $segment }}"
                                                        onclick="selectValue(this)">
                                                    <div class="text-xl font-bold">3</div>
                                                    <div class="text-xs">Cukup Penting</div>
                                                </button>
                                                <button type="button"
                                                        class="ahp-value-btn-left {{ $comparison['value'] == 2 ? 'active' : '' }}"
                                                        data-value="2"
                                                        data-a-id="{{ $comparison['criterion_a']->id }}"
                                                        data-b-id="{{ $comparison['criterion_b']->id }}"
                                                        data-segment="{{ $segment }}"
                                                        onclick="selectValue(this)">
                                                    <div class="text-xl font-bold">2</div>
                                                    <div class="text-xs">Sedikit Lebih Penting</div>
                                                </button>
                                            </div>

                                            <!-- RIGHT SIDE: Kriteria B lebih penting -->
                                            <div class="space-y-2">
                                                <div class="text-center text-xs font-bold text-green-700 mb-3">
                                                    {{ $comparison['criterion_b']->nama }} Lebih Penting →
                                                </div>
                                                <button type="button"
                                                        class="ahp-value-btn-right {{ abs($comparison['value'] - 0.111) < 0.01 ? 'active' : '' }}"
                                                        data-value="0.111"
                                                        data-a-id="{{ $comparison['criterion_a']->id }}"
                                                        data-b-id="{{ $comparison['criterion_b']->id }}"
                                                        data-segment="{{ $segment }}"
                                                        onclick="selectValue(this)">
                                                    <div class="text-xl font-bold">9</div>
                                                    <div class="text-xs">Mutlak Lebih Penting</div>
                                                </button>
                                                <button type="button"
                                                        class="ahp-value-btn-right {{ abs($comparison['value'] - 0.143) < 0.01 ? 'active' : '' }}"
                                                        data-value="0.143"
                                                        data-a-id="{{ $comparison['criterion_a']->id }}"
                                                        data-b-id="{{ $comparison['criterion_b']->id }}"
                                                        data-segment="{{ $segment }}"
                                                        onclick="selectValue(this)">
                                                    <div class="text-xl font-bold">7</div>
                                                    <div class="text-xs">Jauh Lebih Penting</div>
                                                </button>
                                                <button type="button"
                                                        class="ahp-value-btn-right {{ abs($comparison['value'] - 0.2) < 0.01 ? 'active' : '' }}"
                                                        data-value="0.2"
                                                        data-a-id="{{ $comparison['criterion_a']->id }}"
                                                        data-b-id="{{ $comparison['criterion_b']->id }}"
                                                        data-segment="{{ $segment }}"
                                                        onclick="selectValue(this)">
                                                    <div class="text-xl font-bold">5</div>
                                                    <div class="text-xs">Sangat Penting</div>
                                                </button>
                                                <button type="button"
                                                        class="ahp-value-btn-right {{ abs($comparison['value'] - 0.333) < 0.01 ? 'active' : '' }}"
                                                        data-value="0.333"
                                                        data-a-id="{{ $comparison['criterion_a']->id }}"
                                                        data-b-id="{{ $comparison['criterion_b']->id }}"
                                                        data-segment="{{ $segment }}"
                                                        onclick="selectValue(this)">
                                                    <div class="text-xl font-bold">3</div>
                                                    <div class="text-xs">Cukup Penting</div>
                                                </button>
                                                <button type="button"
                                                        class="ahp-value-btn-right {{ abs($comparison['value'] - 0.5) < 0.01 ? 'active' : '' }}"
                                                        data-value="0.5"
                                                        data-a-id="{{ $comparison['criterion_a']->id }}"
                                                        data-b-id="{{ $comparison['criterion_b']->id }}"
                                                        data-segment="{{ $segment }}"
                                                        onclick="selectValue(this)">
                                                    <div class="text-xl font-bold">2</div>
                                                    <div class="text-xs">Sedikit Lebih Penting</div>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- CENTER: Sama Penting -->
                                        <div class="mt-4">
                                            <button type="button"
                                                    class="ahp-value-btn-center {{ $comparison['value'] == 1 ? 'active' : '' }}"
                                                    data-value="1"
                                                    data-a-id="{{ $comparison['criterion_a']->id }}"
                                                    data-b-id="{{ $comparison['criterion_b']->id }}"
                                                    data-segment="{{ $segment }}"
                                                    onclick="selectValue(this)">
                                                <div class="text-xl font-bold">1</div>
                                                <div class="text-xs">Sama Penting</div>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Current Selection Display -->
                                    <div class="mt-4 text-center">
                                        <div class="inline-block bg-secondary-100 px-4 py-2 rounded-lg">
                                            <span class="text-sm font-semibold text-secondary-900">
                                                Nilai Terpilih: <span class="text-lg">{{ $comparison['value'] }}</span>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Hidden: Old buttons layout (removed) -->
                                    <div class="hidden">
                                            <button type="button"
                                                    class="ahp-value-btn {{ $comparison['value'] == 6 ? 'active' : '' }}"
                                                    data-value="6"
                                                    data-a-id="{{ $comparison['criterion_a']->id }}"
                                                    data-b-id="{{ $comparison['criterion_b']->id }}"
                                                    data-segment="{{ $segment }}"
                                                    onclick="selectValue(this)">
                                                <div class="text-2xl font-bold">6</div>
                                                <div class="text-xs mt-1">Sangat Lebih</div>
                                            </button>
                                            <button type="button"
                                                    class="ahp-value-btn {{ $comparison['value'] == 7 ? 'active' : '' }}"
                                                    data-value="7"
                                                    data-a-id="{{ $comparison['criterion_a']->id }}"
                                                    data-b-id="{{ $comparison['criterion_b']->id }}"
                                                    data-segment="{{ $segment }}"
                                                    onclick="selectValue(this)">
                                                <div class="text-2xl font-bold">7</div>
                                                <div class="text-xs mt-1">Jauh Lebih Penting</div>
                                            </button>
                                            <button type="button"
                                                    class="ahp-value-btn {{ $comparison['value'] == 8 ? 'active' : '' }}"
                                                    data-value="8"
                                                    data-a-id="{{ $comparison['criterion_a']->id }}"
                                                    data-b-id="{{ $comparison['criterion_b']->id }}"
                                                    data-segment="{{ $segment }}"
                                                    onclick="selectValue(this)">
                                                <div class="text-2xl font-bold">8</div>
                                                <div class="text-xs mt-1">Sangat Jauh</div>
                                            </button>
                                            <button type="button"
                                                    class="ahp-value-btn {{ $comparison['value'] == 9 ? 'active' : '' }}"
                                                    data-value="9"
                                                    data-a-id="{{ $comparison['criterion_a']->id }}"
                                                    data-b-id="{{ $comparison['criterion_b']->id }}"
                                                    data-segment="{{ $segment }}"
                                                    onclick="selectValue(this)">
                                                <div class="text-2xl font-bold">9</div>
                                                <div class="text-xs mt-1">Mutlak Lebih Penting</div>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Nilai Terpilih -->
                                    <div class="mt-4 p-3 bg-secondary-50 rounded-lg border border-secondary-200 comparison-result-{{ $index }}">
                                        <div class="text-center">
                                            <span class="text-sm text-secondary-700">Nilai dipilih: </span>
                                            <span class="text-lg font-bold text-secondary-900 selected-value">{{ $comparison['value'] }}</span>
                                            <span class="text-sm text-secondary-700"> - </span>
                                            <span class="text-sm text-secondary-700 selected-description">
                                                {{ \App\Services\AhpService::getScaleDescription($comparison['value']) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 flex justify-end gap-2">
                            <form action="{{ route('ahp.calculate') }}" method="GET">
                                <input type="hidden" name="segment" value="{{ $segment }}">
                                <button type="submit" 
                                        class="bg-secondary-500 hover:bg-secondary-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-300">
                                    <i class="fas fa-calculator mr-2"></i>Hitung Bobot
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>Tidak ada perbandingan yang perlu dilakukan (minimal 2 kriteria)</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Results -->
            @if($result)
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-chart-bar mr-2 text-green-500"></i>Hasil Perhitungan AHP
                        </h3>

                        <!-- Consistency Check -->
                        <div class="mb-6 p-4 rounded-lg {{ $result['is_consistent'] ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="font-semibold {{ $result['is_consistent'] ? 'text-green-900' : 'text-red-900' }}">
                                        @if($result['is_consistent'])
                                            <i class="fas fa-check-circle mr-2"></i>Konsistensi: BAIK ✓
                                        @else
                                            <i class="fas fa-times-circle mr-2"></i>Konsistensi: TIDAK BAIK ✗
                                        @endif
                                    </div>
                                    <div class="text-sm {{ $result['is_consistent'] ? 'text-green-700' : 'text-red-700' }}">
                                        Consistency Ratio (CR) = {{ $result['cr'] }} 
                                        {{ $result['is_consistent'] ? '≤ 0.1 (Konsisten)' : '> 0.1 (Perlu Revisi)' }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-600">Lambda Max: {{ $result['lambda_max'] }}</div>
                                    <div class="text-sm text-gray-600">CI: {{ $result['ci'] }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Weights Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kriteria
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Bobot (Desimal)
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Bobot (%)
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Visualisasi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($result['criteria'] as $index => $criterion)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $criterion->nama }}</div>
                                                <div class="text-xs text-gray-500">{{ ucfirst($criterion->tipe) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="text-lg font-bold text-secondary-600">
                                                    {{ number_format($result['weights'][$index], 4) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="text-lg font-bold text-green-600">
                                                    {{ number_format($result['weights'][$index] * 100, 2) }}%
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="w-full bg-gray-200 rounded-full h-4">
                                                    <div class="bg-gradient-to-r from-secondary-500 to-primary-500 h-4 rounded-full" 
                                                         style="width: {{ $result['weights'][$index] * 100 }}%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Apply Weights Button -->
                        @if($result['is_consistent'])
                            <div class="mt-6 flex justify-end">
                                <form action="{{ route('ahp.apply') }}" method="POST" 
                                      onsubmit="return confirm('Yakin ingin menerapkan bobot ini ke kriteria?')">
                                    @csrf
                                    <input type="hidden" name="segment" value="{{ $segment }}">
                                    <button type="submit" 
                                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-300">
                                        <i class="fas fa-check mr-2"></i>Terapkan Bobot ke Kriteria
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="mt-6 text-center text-red-600">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Bobot tidak dapat diterapkan karena konsistensi buruk. Silakan revisi perbandingan Anda.
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        /* Left buttons (Kriteria A lebih penting) */
        .ahp-value-btn-left {
            width: 100% !important;
            padding: 0.75rem !important;
            border: 2px solid #dbeafe !important;
            border-radius: 0.5rem !important;
            background: linear-gradient(to right, #eff6ff, #ffffff) !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            text-align: center !important;
            pointer-events: auto !important;
        }
        
        .ahp-value-btn-left:hover {
            background: linear-gradient(to right, #bfdbfe, #dbeafe) !important;
            border-color: #3b82f6 !important;
            transform: translateX(-2px) !important;
            box-shadow: -4px 4px 12px rgba(59, 130, 246, 0.3) !important;
        }
        
        .ahp-value-btn-left.active {
            background: linear-gradient(to right, #3b82f6, #60a5fa) !important;
            color: white !important;
            border-color: #2563eb !important;
            box-shadow: -4px 4px 16px rgba(59, 130, 246, 0.5) !important;
            transform: translateX(-4px) !important;
        }
        
        /* Right buttons (Kriteria B lebih penting) */
        .ahp-value-btn-right {
            width: 100% !important;
            padding: 0.75rem !important;
            border: 2px solid #d1fae5 !important;
            border-radius: 0.5rem !important;
            background: linear-gradient(to left, #ecfdf5, #ffffff) !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            text-align: center !important;
            pointer-events: auto !important;
        }
        
        .ahp-value-btn-right:hover {
            background: linear-gradient(to left, #a7f3d0, #d1fae5) !important;
            border-color: #10b981 !important;
            transform: translateX(2px) !important;
            box-shadow: 4px 4px 12px rgba(16, 185, 129, 0.3) !important;
        }
        
        .ahp-value-btn-right.active {
            background: linear-gradient(to left, #10b981, #34d399) !important;
            color: white !important;
            border-color: #059669 !important;
            box-shadow: 4px 4px 16px rgba(16, 185, 129, 0.5) !important;
            transform: translateX(4px) !important;
        }
        
        /* Center button (Sama penting) */
        .ahp-value-btn-center {
            width: 100% !important;
            padding: 1rem !important;
            border: 2px solid #e9d5ff !important;
            border-radius: 0.75rem !important;
            background: linear-gradient(to right, #faf5ff, #ffffff, #faf5ff) !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            text-align: center !important;
            pointer-events: auto !important;
        }
        
        .ahp-value-btn-center:hover {
            background: linear-gradient(to right, #e9d5ff, #f3e8ff, #e9d5ff) !important;
            border-color: #a855f7 !important;
            transform: scale(1.02) !important;
            box-shadow: 0 4px 12px rgba(168, 85, 247, 0.3) !important;
        }
        
        .ahp-value-btn-center.active {
            background: linear-gradient(to right, #a855f7, #c084fc, #a855f7) !important;
            color: white !important;
            border-color: #9333ea !important;
            box-shadow: 0 4px 16px rgba(168, 85, 247, 0.5) !important;
            transform: scale(1.05) !important;
        }
        
        /* Old button style (for backward compatibility) */
        .ahp-value-btn {
            padding: 1rem !important;
            border: 2px solid #e5e7eb !important;
            border-radius: 0.5rem !important;
            background-color: white !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            text-align: center !important;
            position: relative !important;
            z-index: 1 !important;
            pointer-events: auto !important;
        }
        
        .ahp-value-btn:hover {
            border-color: #a855f7 !important;
            background-color: #faf5ff !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
        }
        
        .ahp-value-btn.active {
            border-color: #9333ea !important;
            background-color: #9333ea !important;
            color: white !important;
            transform: scale(1.05) !important;
            box-shadow: 0 6px 12px rgba(147, 51, 234, 0.3) !important;
        }
        
        .ahp-value-btn .text-2xl {
            line-height: 1 !important;
            pointer-events: none !important;
        }
        
        .ahp-value-btn .text-xs {
            opacity: 0.8 !important;
            pointer-events: none !important;
        }
        
        .ahp-value-btn.active .text-xs {
            opacity: 1 !important;
        }

        /* Debug */
        .ahp-value-btn:active {
            background-color: #c084fc !important;
        }
        
        /* Prevent child elements from blocking clicks */
        .ahp-value-btn-left .text-xl,
        .ahp-value-btn-left .text-xs,
        .ahp-value-btn-right .text-xl,
        .ahp-value-btn-right .text-xs,
        .ahp-value-btn-center .text-xl,
        .ahp-value-btn-center .text-xs {
            pointer-events: none !important;
            line-height: 1.2 !important;
        }
        
        .ahp-value-btn-left.active .text-xs,
        .ahp-value-btn-right.active .text-xs,
        .ahp-value-btn-center.active .text-xs {
            opacity: 1 !important;
            font-weight: 600 !important;
        }
    </style>
    
    <script>
        // Debug: Check if script loaded
        console.log('AHP Script loaded successfully');

        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, attaching event listeners...');
            
            // Attach click event to all AHP buttons (new and old styles)
            const buttons = document.querySelectorAll('.ahp-value-btn, .ahp-value-btn-left, .ahp-value-btn-right, .ahp-value-btn-center');
            console.log('Found ' + buttons.length + ' buttons');
            
            buttons.forEach(function(button) {
                button.addEventListener('click', function() {
                    selectValue(this);
                });
            });
        });

        function selectValue(button) {
            console.log('Button clicked!', button);
            
            const value = button.dataset.value;
            const segment = button.dataset.segment;
            const aId = button.dataset.aId;
            const bId = button.dataset.bId;
            
            console.log('Value:', value, 'Segment:', segment, 'A:', aId, 'B:', bId);
            
            // Remove active class from all buttons in this comparison
            const parentDiv = button.closest('.border-2');
            const allButtons = parentDiv.querySelectorAll('.ahp-value-btn, .ahp-value-btn-left, .ahp-value-btn-right, .ahp-value-btn-center');
            allButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            button.classList.add('active');
            
            // Update displayed value in the new UI
            const displayDiv = parentDiv.querySelector('.bg-secondary-100');
            if (displayDiv) {
                const valueSpan = displayDiv.querySelector('.text-lg');
                if (valueSpan) {
                    valueSpan.textContent = value;
                }
            }
            
            // Get original button label
            const originalLabel = button.querySelector('.text-xs') ? button.querySelector('.text-xs').textContent : '';
            const originalValue = button.querySelector('.text-xl') ? button.querySelector('.text-xl').textContent : value;
            
            // Show loading feedback
            button.innerHTML = '<div class="text-xl">⏳</div><div class="text-xs mt-1">Menyimpan...</div>';
            
            // Save to database via AJAX
            const data = {
                segment: segment,
                criterion_a_id: aId,
                criterion_b_id: bId,
                value: parseFloat(value),
                _token: '{{ csrf_token() }}'
            };

            fetch('{{ route("ahp.save") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Restore button content
                    button.innerHTML = '<div class="text-xl font-bold">' + originalValue + '</div><div class="text-xs">' + originalLabel + '</div>';
                    
                    // Show success feedback
                    if (displayDiv) {
                        displayDiv.classList.add('bg-green-100');
                        setTimeout(() => {
                            displayDiv.classList.remove('bg-green-100');
                        }, 1000);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Restore button on error
                button.innerHTML = '<div class="text-2xl font-bold">' + value + '</div><div class="text-xs mt-1">Error!</div>';
                alert('Gagal menyimpan. Silakan coba lagi.');
            });
        }
    </script>
</x-app-layout>

