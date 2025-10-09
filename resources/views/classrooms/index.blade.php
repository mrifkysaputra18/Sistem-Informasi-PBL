<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Pilih Kelas') }}
            </h2>
            @if(auth()->user()->isAdmin() || auth()->user()->isDosen())
            <a href="{{ route('classrooms.create') }}" class="bg-primary-500 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded">
                + Buat Kelas Baru
            </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
            @endif

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <form method="GET" action="{{ route('classrooms.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cari Kelas</label>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Nama atau kode kelas..."
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500">
                    </div>

                    <!-- Subject Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mata Kuliah</label>
                        <select name="subject_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500">
                            <option value="">Semua Mata Kuliah</option>
                            @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Semester Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                        <select name="semester" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500">
                            <option value="">Semua Semester</option>
                            @foreach($semesters as $semester)
                            <option value="{{ $semester }}" {{ request('semester') == $semester ? 'selected' : '' }}>
                                Semester {{ $semester }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-primary-500 hover:bg-primary-600 text-white py-2 px-4 rounded-md">
                            <i class="fas fa-search mr-2"></i>Filter
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Daftar Kelas</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($classRooms as $classRoom)
                        <div class="border rounded-lg p-6 hover:shadow-lg transition-shadow">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="text-xl font-bold text-gray-800">{{ $classRoom->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $classRoom->code }}</p>
                                </div>
                                <span class="px-3 py-1 text-xs rounded-full {{ $classRoom->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $classRoom->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </div>

                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Kelompok:</span>
                                    <span class="font-semibold">{{ $classRoom->groups_count }} / {{ $classRoom->max_groups }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                    @php
                                        $percentage = $classRoom->max_groups > 0 
                                            ? min(100, max(0, ($classRoom->groups_count / $classRoom->max_groups) * 100))
                                            : 0;
                                    @endphp
                                    <div class="bg-primary-600 h-full transition-all duration-300" style="width: <?php echo $percentage; ?>%"></div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <!-- Lihat Kelompok Button -->
                                <a href="{{ route('groups.index', ['classroom' => $classRoom->id]) }}" 
                                   class="block w-full text-center bg-secondary-500 hover:bg-secondary-700 text-white font-bold py-2 px-4 rounded">
                                    <i class="fas fa-users mr-2"></i>Lihat Kelompok ({{ $classRoom->groups_count }})
                                </a>
                                
                                @if(auth()->user()->isAdmin() || auth()->user()->isDosen())
                                <div class="flex gap-2 mt-2">
                                    <!-- Edit Button -->
                                    <a href="{{ url('/classrooms/' . $classRoom->id . '/edit') }}" 
                                       class="flex-1 inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-yellow-600 bg-yellow-100 hover:bg-yellow-200 hover:text-yellow-900 rounded-lg transition duration-200 ease-in-out"
                                       title="Edit Kelas">
                                        <i class="fas fa-edit mr-1"></i>
                                        Edit
                                    </a>
                                    
                                    <!-- Delete Button -->
                                    <button type="button"
                                            onclick="deleteClass({{ $classRoom->id }}, '{{ addslashes($classRoom->name) }}')"
                                            class="flex-1 inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-red-600 bg-red-100 hover:bg-red-200 hover:text-red-900 rounded-lg transition duration-200 ease-in-out"
                                            title="Hapus Kelas">
                                        <i class="fas fa-trash mr-1"></i>
                                        Hapus
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="col-span-3 text-center py-8 text-gray-500">
                            Belum ada kelas. Silakan buat kelas terlebih dahulu.
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($classRooms->hasPages())
                    <div class="mt-6">
                        {{ $classRooms->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Delete Form -->
    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function deleteClass(classId, className) {
            if (confirm('Yakin ingin menghapus kelas "' + className + '"?')) {
                const form = document.getElementById('delete-form');
                form.action = '/classrooms/' + classId;
                form.submit();
            }
        }
    </script>
</x-app-layout>
