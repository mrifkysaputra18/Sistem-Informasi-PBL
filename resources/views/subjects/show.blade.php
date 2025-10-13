<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <a href="{{ route('projects.index') }}" 
                   class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h2 class="font-semibold text-xl text-white leading-tight">
                        Detail Mata Kuliah
                    </h2>
                    <p class="text-sm text-gray-600">{{ $subject->id }} - {{ $subject->title }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('projects.edit', $subject) }}" 
                   class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <form action="{{ route('projects.destroy', $subject) }}" 
                      method="POST" 
                      class="inline"
                      id="delete-form-subject">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                            onclick="deleteSubject('{{ addslashes($subject->title) }}')"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                        <i class="fas fa-trash mr-2"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Subject Info Card -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-white bg-opacity-20 p-3 rounded-full mr-4">
                                <i class="fas fa-book text-white text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-white text-2xl font-bold">{{ $subject->title }}</h3>
                                <p class="text-primary-100">Kode: {{ $subject->id }}</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            @if(true)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>PBL
                            </span>
                            @endif
                            @if(($subject->status === 'active'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary-100 text-primary-800">
                                <i class="fas fa-check mr-1"></i>Aktif
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <i class="fas fa-ban mr-1"></i>Tidak Aktif
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Description -->
                        <div class="col-span-2">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-align-left text-gray-500 mr-1"></i>Deskripsi
                            </h4>
                            @if($subject->description)
                            <p class="text-gray-600">{{ $subject->description }}</p>
                            @else
                            <p class="text-gray-400 italic">Tidak ada deskripsi</p>
                            @endif
                        </div>

                        <!-- PBL Status -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-project-diagram text-gray-500 mr-1"></i>Status PBL
                            </h4>
                            @if(true)
                            <p class="text-green-600 font-medium">
                                <i class="fas fa-check-circle mr-1"></i>Terkait dengan PBL
                            </p>
                            @else
                            <p class="text-gray-600">
                                <i class="fas fa-times-circle mr-1"></i>Tidak terkait PBL
                            </p>
                            @endif
                        </div>

                        <!-- Active Status -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-toggle-on text-gray-500 mr-1"></i>Status Aktif
                            </h4>
                            @if(($subject->status === 'active'))
                            <p class="text-primary-600 font-medium">
                                <i class="fas fa-check mr-1"></i>Mata kuliah aktif
                            </p>
                            @else
                            <p class="text-red-600">
                                <i class="fas fa-ban mr-1"></i>Tidak aktif
                            </p>
                            @endif
                        </div>

                        <!-- Created At -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-plus text-gray-500 mr-1"></i>Dibuat Pada
                            </h4>
                            <p class="text-gray-600">{{ $subject->created_at->format('d M Y, H:i') }}</p>
                        </div>

                        <!-- Updated At -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt text-gray-500 mr-1"></i>Terakhir Diupdate
                            </h4>
                            <p class="text-gray-600">{{ $subject->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Classes -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-gray-800">
                            <i class="fas fa-school mr-2 text-primary-600"></i>
                            Kelas yang Menggunakan Mata Kuliah Ini
                        </h3>
                        <span class="text-sm text-gray-600">{{ $subject->classRooms->count() }} Kelas</span>
                    </div>
                </div>
                <div class="p-6">
                    @if($subject->classRooms->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($subject->classRooms as $classRoom)
                            <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-semibold text-gray-800">{{ $classRoom->name }}</h4>
                                    <span class="text-xs px-2 py-1 bg-primary-100 text-primary-800 rounded-full">
                                        {{ $classRoom->groups->count() ?? 0 }} Kelompok
                                    </span>
                                </div>
                                <div class="text-sm text-gray-600 mb-2">
                                    <i class="fas fa-graduation-cap mr-1"></i>{{ $classRoom->program_studi }}
                                </div>
                                <div class="text-sm text-gray-600 mb-3">
                                    <i class="fas fa-calendar mr-1"></i>Semester {{ $classRoom->semester }}
                                </div>
                                <a href="{{ url('/classrooms/' . $classRoom->id) }}" 
                                   class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                    Lihat Detail →
                                </a>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-400 mb-4">
                                <i class="fas fa-school text-4xl"></i>
                            </div>
                            <p class="text-gray-600">Belum ada kelas yang menggunakan mata kuliah ini</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Related Criteria (if any) -->
            @if($subject->criteria->count() > 0)
            <div class="bg-white rounded-lg shadow-md overflow-hidden mt-6">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-gray-800">
                            <i class="fas fa-list-check mr-2 text-secondary-600"></i>
                            Kriteria Penilaian
                        </h3>
                        <span class="text-sm text-gray-600">{{ $subject->criteria->count() }} Kriteria</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach($subject->criteria as $criterion)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="bg-secondary-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-check text-secondary-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $criterion->nama }}</p>
                                    <p class="text-xs text-gray-600">
                                        Bobot: {{ $criterion->bobot }} • 
                                        Tipe: {{ ucfirst($criterion->tipe) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        function deleteSubject(subjectTitle) {
            const form = document.getElementById('delete-form-subject');
            
            confirmDelete(
                'Hapus Mata Kuliah?',
                `Apakah Anda yakin ingin menghapus mata kuliah <strong>"${subjectTitle}"</strong>?<br><small class="text-gray-500">Tindakan ini tidak dapat dibatalkan.</small>`,
                form
            );
        }
    </script>
</x-app-layout>


