<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('scores.index') }}" 
               class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Input Nilai Kelompok') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8">
                    <!-- Header -->
                    <div class="mb-8 text-center">
                        <div class="bg-secondary-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-star text-secondary-600 text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Input Nilai Kelompok</h3>
                        <p class="text-gray-600">Berikan nilai untuk kelompok berdasarkan kriteria yang telah ditetapkan</p>
                    </div>

                    <form action="{{ route('scores.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Pilih Kelompok -->
                        <div class="space-y-2">
                            <label for="group_id" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-users mr-2 text-gray-500"></i>Pilih Kelompok
                            </label>
                            <select id="group_id" 
                                    name="group_id" 
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-secondary-500 focus:border-secondary-500 transition duration-200 @error('group_id') border-red-500 @enderror">
                                <option value="">Pilih Kelompok</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>
                                        {{ $group->name }} @if($group->classRoom) - {{ $group->classRoom->name }} @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('group_id')
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
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-secondary-500 focus:border-secondary-500 transition duration-200 @error('criterion_id') border-red-500 @enderror">
                                <option value="">Pilih Kriteria</option>
                                @foreach($criteria as $criterion)
                                    <option value="{{ $criterion->id }}" 
                                            data-type="{{ $criterion->tipe }}"
                                            data-weight="{{ $criterion->bobot }}"
                                            {{ old('criterion_id') == $criterion->id ? 'selected' : '' }}>
                                        {{ $criterion->nama }} ({{ ucfirst($criterion->tipe) }} - Bobot: {{ $criterion->bobot }})
                                    </option>
                                @endforeach
                            </select>
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
                                       class="block w-full px-4 py-3 pr-16 border border-gray-300 rounded-lg shadow-sm focus:ring-secondary-500 focus:border-secondary-500 transition duration-200 @error('skor') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-500 text-sm">/ 100</span>
                                </div>
                            </div>
                            
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-info-circle mr-1"></i>
                                Berikan nilai sesuai dengan performa kelompok pada kriteria yang dipilih
                            </p>
                            @error('skor')
                                <p class="text-red-500 text-sm mt-1">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('scores.index') }}" 
                               class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500 transition duration-200">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-secondary-600 hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500 transition duration-200">
                                <i class="fas fa-save mr-2"></i>Simpan Nilai
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
