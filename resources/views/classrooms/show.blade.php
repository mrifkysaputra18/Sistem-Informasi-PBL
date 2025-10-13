<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-white leading-tight">
                    Kelas {{ $classRoom->name }}
                </h2>
                <p class="text-sm text-gray-600">{{ $classRoom->program_studi }} - Semester {{ $classRoom->semester }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('classrooms.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    ← Kembali
                </a>
                @if(auth()->user()->isAdmin() || auth()->user()->isDosen())
                <a href="{{ route('classrooms.students.create', $classRoom->id) }}" class="bg-primary-500 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded">
                    + Tambah Mahasiswa
                </a>
                @endif
            </div>
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

            <!-- Info Kelas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center p-4 bg-primary-50 rounded">
                            <div class="text-3xl font-bold text-primary-600">{{ $students->count() }}</div>
                            <div class="text-sm text-gray-600">Total Mahasiswa</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded">
                            <div class="text-3xl font-bold text-green-600">{{ $classRoom->code }}</div>
                            <div class="text-sm text-gray-600">Kode Kelas</div>
                        </div>
                        <div class="text-center p-4 bg-secondary-50 rounded">
                            <div class="text-3xl font-bold text-secondary-600">{{ $classRoom->program_studi }}</div>
                            <div class="text-sm text-gray-600">Program Studi</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Mahasiswa -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Daftar Mahasiswa</h3>
                    
                    @if($students->isEmpty())
                    <div class="text-center py-8 text-gray-500">
                        <p class="mb-4">Belum ada mahasiswa terdaftar di kelas ini.</p>
                    </div>
                    @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        NIM
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Mahasiswa
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    @if(auth()->user()->isAdmin() || auth()->user()->isDosen())
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($students as $index => $student)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary-600">
                                        {{ $student->politala_id ?? $student->nim ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $student->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $student->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($student->is_active)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Aktif
                                        </span>
                                        @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Tidak Aktif
                                        </span>
                                        @endif
                                    </td>
                                    @if(auth()->user()->isAdmin() || auth()->user()->isDosen())
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('classrooms.students.edit', [$classRoom->id, $student->id]) }}" 
                                               class="text-yellow-600 hover:text-yellow-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('classrooms.students.destroy', [$classRoom->id, $student->id]) }}" 
                                                  method="POST"
                                                  id="remove-student-form-{{ $student->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        onclick="removeStudent({{ $student->id }}, '{{ addslashes($student->name) }}')"
                                                        class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function removeStudent(studentId, studentName) {
            const form = document.getElementById('remove-student-form-' + studentId);
            
            confirmDelete(
                'Hapus Mahasiswa?',
                `Apakah Anda yakin ingin menghapus mahasiswa <strong>"${studentName}"</strong> dari kelas ini?<br><small class="text-gray-500">Tindakan ini tidak dapat dibatalkan.</small>`,
                form
            );
        }
    </script>
</x-app-layout>
