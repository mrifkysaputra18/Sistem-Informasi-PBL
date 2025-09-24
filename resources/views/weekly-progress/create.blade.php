<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Buat Progress Mingguan - Minggu {{ $weekNumber }}
            </h2>
            <a href="{{ route('weekly-progress.index', $group) }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">{{ $group->name }} - {{ $group->project->title }}</h3>
                <p class="mt-1 text-sm text-gray-500">Minggu {{ $weekNumber }}</p>
            </div>

            <form action="{{ route('weekly-progress.store', $group) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <input type="hidden" name="week_number" value="{{ $weekNumber }}">

                <div class="space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Judul Progress</label>
                        <input type="text" name="title" id="title" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               value="{{ old('title') }}" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="description" id="description" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                  required>{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Activities -->
                    <div>
                        <label for="activities" class="block text-sm font-medium text-gray-700">Aktivitas yang Dilakukan</label>
                        <textarea name="activities" id="activities" rows="4"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                  required>{{ old('activities') }}</textarea>
                        @error('activities')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Achievements -->
                    <div>
                        <label for="achievements" class="block text-sm font-medium text-gray-700">Pencapaian</label>
                        <textarea name="achievements" id="achievements" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('achievements') }}</textarea>
                        @error('achievements')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Challenges -->
                    <div>
                        <label for="challenges" class="block text-sm font-medium text-gray-700">Tantangan/Kendala</label>
                        <textarea name="challenges" id="challenges" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('challenges') }}</textarea>
                        @error('challenges')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Next Week Plan -->
                    <div>
                        <label for="next_week_plan" class="block text-sm font-medium text-gray-700">Rencana Minggu Depan</label>
                        <textarea name="next_week_plan" id="next_week_plan" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('next_week_plan') }}</textarea>
                        @error('next_week_plan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Documents Upload -->
                    <div>
                        <label for="documents" class="block text-sm font-medium text-gray-700">Dokumen Pendukung</label>
                        <input type="file" name="documents[]" id="documents" multiple
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        <p class="mt-1 text-sm text-gray-500">Upload dokumen pendukung (PDF, DOC, DOCX, JPG, PNG). Max 10MB per file.</p>
                        @error('documents')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end space-x-3">
                    <a href="{{ route('weekly-progress.index', $group) }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Batal
                    </a>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Simpan Progress
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>