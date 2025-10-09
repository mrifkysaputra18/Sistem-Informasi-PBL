<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('targets.index') }}" 
               class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Edit Target Mingguan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Display Validation Errors -->
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded relative" role="alert">
                            <div class="font-bold mb-2">
                                <i class="fas fa-exclamation-circle mr-2"></i>Terjadi Kesalahan!
                            </div>
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Info Target -->
                    <div class="mb-6 p-4 bg-primary-50 border border-primary-200 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-primary-600 mt-1 mr-3"></i>
                            <div>
                                <p class="text-sm text-primary-800 font-medium">Kelompok: {{ $target->group->name }}</p>
                                <p class="text-sm text-primary-700">Kelas: {{ $target->group->classRoom->name }}</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('targets.update', $target) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Week Number -->
                        <div class="mb-6">
                            <label for="week_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Minggu Ke <span class="text-red-500">*</span>
                            </label>
                            <select name="week_number" id="week_number" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500">
                                @for($i = 1; $i <= 16; $i++)
                                <option value="{{ $i }}" {{ old('week_number', $target->week_number) == $i ? 'selected' : '' }}>
                                    Minggu {{ $i }}
                                </option>
                                @endfor
                            </select>
                            @error('week_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Title -->
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Target <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" required
                                   value="{{ old('title', $target->title) }}"
                                   placeholder="Contoh: Membuat Use Case Diagram"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('title') border-red-500 @enderror">
                            @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Target <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" id="description" rows="4" required
                                      placeholder="Jelaskan detail target yang harus dikerjakan mahasiswa..."
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $target->description) }}</textarea>
                            @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deadline -->
                        <div class="mb-6">
                            <label for="deadline" class="block text-sm font-medium text-gray-700 mb-2">
                                Deadline Submit <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" name="deadline" id="deadline" required
                                   value="{{ old('deadline', $target->deadline ? $target->deadline->format('Y-m-d\TH:i') : '') }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500 @error('deadline') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Mahasiswa harus submit sebelum deadline ini
                            </p>
                            @error('deadline')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex gap-2">
                            <a href="{{ route('targets.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="bg-primary-500 hover:bg-primary-700 text-white font-bold py-2 px-6 rounded">
                                <i class="fas fa-save mr-2"></i>Update Target
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
