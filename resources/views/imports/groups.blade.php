<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('classrooms.index') }}" 
               class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Import Kelompok dari Excel') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
            @endif

            @if(session('warning'))
            <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('warning') }}
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-8">
                            <form action="{{ route('import.groups.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <!-- Pilih Kelas -->
                                <div class="mb-6">
                                    <label for="class_room_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Pilih Kelas Tujuan <span class="text-red-500">*</span>
                                    </label>
                                    <select name="class_room_id" 
                                            id="class_room_id" 
                                            required
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500">
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($classRooms as $classRoom)
                                        <option value="{{ $classRoom->id }}">
                                            {{ $classRoom->name }} 
                                            @if($classRoom->subject)
                                                - {{ $classRoom->subject->name }}
                                            @endif
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Upload File -->
                                <div class="mb-6">
                                    <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                                        File Excel <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" 
                                           name="file" 
                                           id="file" 
                                           accept=".xlsx,.xls,.csv"
                                           required
                                           class="w-full text-sm text-gray-500
                                                  file:mr-4 file:py-2 file:px-4
                                                  file:rounded-md file:border-0
                                                  file:text-sm file:font-semibold
                                                  file:bg-primary-50 file:text-primary-700
                                                  hover:file:bg-primary-100">
                                    <p class="mt-1 text-xs text-gray-500">
                                        Format: .xlsx, .xls, .csv (Max 2MB)
                                    </p>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                                    <a href="{{ route('classrooms.index') }}" 
                                       class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                                        <i class="fas fa-times mr-2"></i>Batal
                                    </a>
                                    <button type="submit" 
                                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-green-600 hover:bg-green-700">
                                        <i class="fas fa-file-import mr-2"></i>Import Data
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Download Template -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">
                                <i class="fas fa-download text-primary-600 mr-2"></i>
                                Download Template
                            </h3>
                            <p class="text-sm text-gray-600 mb-4">
                                Download template Excel untuk import kelompok
                            </p>
                            <a href="{{ route('import.groups.template') }}" 
                               class="block w-full text-center bg-primary-500 hover:bg-primary-600 text-white py-2 px-4 rounded-lg">
                                <i class="fas fa-download mr-2"></i>Download Template
                            </a>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">
                                <i class="fas fa-question-circle text-secondary-600 mr-2"></i>
                                Format Excel
                            </h3>
                            <div class="text-sm text-gray-600 space-y-2">
                                <p class="font-medium">Kolom yang diperlukan:</p>
                                <ol class="list-decimal list-inside space-y-1 text-xs">
                                    <li><strong>nama_kelompok</strong> (wajib)</li>
                                    <li><strong>ketua_nim_atau_email</strong> (wajib)</li>
                                    <li>anggota_1_nim_atau_email</li>
                                    <li>anggota_2_nim_atau_email</li>
                                    <li>anggota_3_nim_atau_email</li>
                                    <li>anggota_4_nim_atau_email</li>
                                </ol>
                                <p class="text-xs text-gray-500 mt-2">
                                    ℹ️ Bisa gunakan <strong>NIM</strong> atau <strong>Email</strong>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Important Notes -->
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-semibold text-yellow-800 mb-2">Penting!</h4>
                                <ul class="text-xs text-yellow-700 space-y-1">
                                    <li>• Mahasiswa harus dari kelas yang dipilih</li>
                                    <li>• Mahasiswa belum boleh punya kelompok</li>
                                    <li>• Bisa pakai NIM atau Email mahasiswa</li>
                                    <li>• Max 5 anggota (1 ketua + 4 anggota)</li>
                                    <li>• Nama kelompok harus unik</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
