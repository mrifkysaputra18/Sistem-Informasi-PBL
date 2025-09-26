<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('groups.index') }}" 
               class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Kelompok Baru') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8">
                    <!-- Header -->
                    <div class="mb-8 text-center">
                        <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-blue-600 text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Buat Kelompok Baru</h3>
                        <p class="text-gray-600">Tambahkan kelompok mahasiswa untuk proyek PBL</p>
                    </div>

                    <form action="{{ route('groups.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Kode Kelompok -->
                        <div class="space-y-2">
                            <label for="kode" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-hashtag mr-2 text-gray-500"></i>Kode Kelompok
                            </label>
                            <input type="text" 
                                   id="kode" 
                                   name="kode" 
                                   value="{{ old('kode') }}"
                                   placeholder="Contoh: PBL-01"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('kode') border-red-500 @enderror">
                            @error('kode')
                                <p class="text-red-500 text-sm mt-1">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Nama Kelompok -->
                        <div class="space-y-2">
                            <label for="nama" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-users mr-2 text-gray-500"></i>Nama Kelompok
                            </label>
                            <input type="text" 
                                   id="nama" 
                                   name="nama" 
                                   value="{{ old('nama') }}"
                                   placeholder="Contoh: Tim Innovators"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('nama') border-red-500 @enderror">
                            @error('nama')
                                <p class="text-red-500 text-sm mt-1">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Tahun Akademik -->
                        <div class="space-y-2">
                            <label for="academic_term_id" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-calendar-alt mr-2 text-gray-500"></i>Tahun Akademik / Semester
                            </label>
                            <select id="academic_term_id" 
                                    name="academic_term_id" 
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('academic_term_id') border-red-500 @enderror">
                                <option value="">Pilih Tahun Akademik</option>
                                @foreach($terms as $term)
                                    <option value="{{ $term->id }}" {{ old('academic_term_id') == $term->id ? 'selected' : '' }}>
                                        {{ $term->tahun_akademik }} - Semester {{ $term->semester }}
                                    </option>
                                @endforeach
                            </select>
                            @error('academic_term_id')
                                <p class="text-red-500 text-sm mt-1">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Judul Proyek -->
                        <div class="space-y-2">
                            <label for="judul_proyek" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-project-diagram mr-2 text-gray-500"></i>Judul Proyek (Opsional)
                            </label>
                            <textarea id="judul_proyek" 
                                      name="judul_proyek" 
                                      rows="3"
                                      placeholder="Contoh: Sistem Informasi Manajemen Perpustakaan"
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('judul_proyek') border-red-500 @enderror">{{ old('judul_proyek') }}</textarea>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-info-circle mr-1"></i>
                                Deskripsi singkat tentang proyek yang akan dikerjakan kelompok ini
                            </p>
                            @error('judul_proyek')
                                <p class="text-red-500 text-sm mt-1">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('groups.index') }}" 
                               class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                                <i class="fas fa-save mr-2"></i>Simpan Kelompok
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
