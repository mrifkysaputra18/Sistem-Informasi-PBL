<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('student-scores.index') }}" 
               class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Input Nilai Mahasiswa') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8">
                    <!-- Header -->
                    <div class="mb-8 text-center">
                        <div class="bg-primary-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-user-graduate text-primary-600 text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Input Nilai Mahasiswa</h3>
                        <p class="text-gray-600">Berikan nilai untuk mahasiswa berdasarkan kriteria yang telah ditetapkan</p>
                    </div>

                    <!-- Info SAW Method -->
                    <div class="mb-6 bg-gradient-to-r from-blue-50 to-primary-50 border-l-4 border-primary-500 p-4 rounded-r-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-primary-500 text-xl mt-1"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-semibold text-primary-900 mb-1">Metode SAW (Simple Additive Weighting)</h4>
                                <p class="text-sm text-primary-800">
                                    Sistem akan menghitung ranking mahasiswa menggunakan metode SAW dengan normalisasi otomatis berdasarkan tipe kriteria (Benefit/Cost).
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('student-scores.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Pilih Mahasiswa -->
                        <div class="space-y-2">
                            <label for="user_id" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-user-graduate mr-2 text-gray-500"></i>Pilih Mahasiswa
                            </label>
                            <select id="user_id" 
                                    name="user_id" 
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-primary-500 transition duration-200 @error('user_id') border-red-500 @enderror">
                                <option value="">Pilih Mahasiswa</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('user_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->politala_id }} - {{ $student->name }} 
                                        @if($student->classRoom) 
                                            ({{ $student->classRoom->name }})
                                        @endif
                                        @if($student->groups->first())
                                            - Kelompok: {{ $student->groups->first()->name }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="text-red-500 text-sm mt-1">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Pilih Kriteria -->
                        <div class="space-y-2">
                            <label for="criterion_id" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-list-check mr-2 text-gray-500"></i>Pilih Kriteria Penilaian
                            </label>
                            <select id="criterion_id" 
                                    name="criterion_id" 
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-primary-500 transition duration-200 @error('criterion_id') border-red-500 @enderror">
                                <option value="">Pilih Kriteria</option>
                                @foreach($criteria as $criterion)
                                    <option value="{{ $criterion->id }}" 
                                            data-type="{{ $criterion->tipe }}"
                                            data-weight="{{ $criterion->bobot }}"
                                            {{ old('criterion_id') == $criterion->id ? 'selected' : '' }}>
                                        {{ $criterion->nama }} 
                                        ({{ ucfirst($criterion->tipe) }} - Bobot: {{ number_format($criterion->bobot * 100, 0) }}%)
                                    </option>
                                @endforeach
                            </select>
                            
                            <!-- Info Kriteria -->
                            <div id="criterion-info" class="hidden mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-start">
                                    <i class="fas fa-lightbulb text-yellow-500 mt-1 mr-2"></i>
                                    <div class="text-sm text-gray-700">
                                        <p id="criterion-type-info"></p>
                                        <p id="criterion-weight-info" class="mt-1 font-medium"></p>
                                    </div>
                                </div>
                            </div>
                            
                            @error('criterion_id')
                                <p class="text-red-500 text-sm mt-1">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Input Skor -->
                        <div class="space-y-2">
                            <label for="skor" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-star mr-2 text-gray-500"></i>Skor (0-100)
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       id="skor" 
                                       name="skor" 
                                       value="{{ old('skor') }}"
                                       min="0" 
                                       max="100"
                                       step="0.01"
                                       placeholder="Masukkan skor 0-100"
                                       class="block w-full px-4 py-3 pr-16 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-primary-500 transition duration-200 @error('skor') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-500 text-sm">/ 100</span>
                                </div>
                            </div>
                            
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-info-circle mr-1"></i>
                                Berikan nilai sesuai dengan performa mahasiswa pada kriteria yang dipilih
                            </p>
                            @error('skor')
                                <p class="text-red-500 text-sm mt-1">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('student-scores.index') }}" 
                               class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                                <i class="fas fa-save mr-2"></i>Simpan Nilai
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Show criterion info when criterion is selected
        document.getElementById('criterion_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const type = selectedOption.dataset.type;
            const weight = selectedOption.dataset.weight;
            const infoDiv = document.getElementById('criterion-info');
            
            if (type && weight) {
                const typeInfo = type === 'benefit' 
                    ? 'Kriteria <strong>Benefit</strong>: Semakin tinggi nilai, semakin baik.' 
                    : 'Kriteria <strong>Cost</strong>: Semakin rendah nilai, semakin baik.';
                
                document.getElementById('criterion-type-info').innerHTML = typeInfo;
                document.getElementById('criterion-weight-info').innerHTML = `Bobot kriteria: <strong>${(weight * 100).toFixed(0)}%</strong>`;
                infoDiv.classList.remove('hidden');
            } else {
                infoDiv.classList.add('hidden');
            }
        });
    </script>
    @endpush
</x-app-layout>

