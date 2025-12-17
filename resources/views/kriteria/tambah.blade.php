{{-- View: Form Tambah Kriteria | Controller: KriteriaController@create/store | Route: criteria.create --}}
<x-app-layout>
    {{-- Header dengan tombol kembali --}}
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('criteria.index') }}" class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">{{ __('Tambah Kriteria Penilaian') }}</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8">
                    <div class="mb-8 text-center">
                        <div class="bg-primary-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-plus text-primary-600 text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Buat Kriteria Baru</h3>
                        <p class="text-gray-600">Definisikan kriteria penilaian untuk evaluasi kelompok PBL</p>
                    </div>

                    {{-- Form: POST ke criteria.store --}}
                    <form action="{{ route('criteria.store') }}" method="POST" class="space-y-6">
                        @csrf {{-- Token CSRF wajib untuk keamanan --}}

                        {{-- Input: Nama Kriteria --}}
                        <div class="space-y-2">
                            <label for="nama" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-tag mr-2 text-gray-500"></i>Nama Kriteria
                            </label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                                   placeholder="Contoh: Kualitas Presentasi"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-primary-500 transition duration-200 @error('nama') border-red-500 @enderror">
                            @error('nama')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Input: Bobot (0.0 - 1.0) --}}
                        <div class="space-y-2">
                            <label for="bobot" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-weight-hanging mr-2 text-gray-500"></i>Bobot Kriteria
                            </label>
                            <div class="relative">
                                <input type="number" id="bobot" name="bobot" value="{{ old('bobot') }}"
                                       step="0.01" min="0" max="1" placeholder="0.25"
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-primary-500 transition duration-200 @error('bobot') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-500 text-sm">0.0 - 1.0</span>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600"><i class="fas fa-info-circle mr-1"></i>Nilai antara 0.0 sampai 1.0. Contoh: 0.25 = 25%</p>
                            @error('bobot')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Radio: Tipe Kriteria (benefit/cost) --}}
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-chart-line mr-2 text-gray-500"></i>Tipe Kriteria
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Benefit: makin tinggi makin baik --}}
                                <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition duration-200">
                                    <input type="radio" name="tipe" value="benefit" {{ old('tipe') == 'benefit' ? 'checked' : '' }} class="sr-only">
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 border-2 border-green-500 rounded-full mr-3 flex items-center justify-center">
                                            <div class="w-2 h-2 bg-green-500 rounded-full hidden benefit-indicator"></div>
                                        </div>
                                        <div>
                                            <div class="flex items-center text-green-700 font-medium"><i class="fas fa-arrow-up mr-2"></i>Benefit</div>
                                            <div class="text-sm text-gray-600">Semakin tinggi semakin baik</div>
                                        </div>
                                    </div>
                                </label>
                                {{-- Cost: makin rendah makin baik --}}
                                <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition duration-200">
                                    <input type="radio" name="tipe" value="cost" {{ old('tipe') == 'cost' ? 'checked' : '' }} class="sr-only">
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 border-2 border-red-500 rounded-full mr-3 flex items-center justify-center">
                                            <div class="w-2 h-2 bg-red-500 rounded-full hidden cost-indicator"></div>
                                        </div>
                                        <div>
                                            <div class="flex items-center text-red-700 font-medium"><i class="fas fa-arrow-down mr-2"></i>Cost</div>
                                            <div class="text-sm text-gray-600">Semakin rendah semakin baik</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @error('tipe')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Radio: Segment (group/student) --}}
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-users mr-2 text-gray-500"></i>Segment Penilaian
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Group: untuk kelompok --}}
                                <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition duration-200">
                                    <input type="radio" name="segment" value="group" {{ old('segment', 'group') == 'group' ? 'checked' : '' }} class="sr-only">
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 border-2 border-primary-500 rounded-full mr-3 flex items-center justify-center">
                                            <div class="w-2 h-2 bg-primary-500 rounded-full group-indicator"></div>
                                        </div>
                                        <div>
                                            <div class="flex items-center text-primary-700 font-medium"><i class="fas fa-users mr-2"></i>Group</div>
                                            <div class="text-sm text-gray-600">Penilaian untuk kelompok</div>
                                        </div>
                                    </div>
                                </label>
                                {{-- Student: untuk individu --}}
                                <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition duration-200">
                                    <input type="radio" name="segment" value="student" {{ old('segment') == 'student' ? 'checked' : '' }} class="sr-only">
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 border-2 border-secondary-500 rounded-full mr-3 flex items-center justify-center">
                                            <div class="w-2 h-2 bg-secondary-500 rounded-full hidden student-indicator"></div>
                                        </div>
                                        <div>
                                            <div class="flex items-center text-secondary-700 font-medium"><i class="fas fa-user mr-2"></i>Student</div>
                                            <div class="text-sm text-gray-600">Penilaian untuk individu</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @error('segment')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('criteria.index') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition duration-200">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-primary-600 hover:bg-primary-700 transition duration-200">
                                <i class="fas fa-save mr-2"></i>Simpan Kriteria
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk custom radio button styling --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radioInputs = document.querySelectorAll('input[type="radio"]');
            radioInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const groupName = this.name;
                    document.querySelectorAll(`input[name="${groupName}"]`).forEach(radio => {
                        const indicators = radio.parentElement.querySelectorAll('div > div > div');
                        indicators.forEach(indicator => { if (indicator.classList.contains('w-2')) indicator.classList.add('hidden'); });
                    });
                    const indicators = this.parentElement.querySelectorAll('div > div > div');
                    indicators.forEach(indicator => { if (indicator.classList.contains('w-2')) indicator.classList.remove('hidden'); });
                });
                if (input.checked) {
                    const indicators = input.parentElement.querySelectorAll('div > div > div');
                    indicators.forEach(indicator => { if (indicator.classList.contains('w-2')) indicator.classList.remove('hidden'); });
                }
            });
        });
    </script>
</x-app-layout>
