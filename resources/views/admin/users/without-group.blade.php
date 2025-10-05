<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <i class="fas fa-user-slash mr-2"></i>{{ __('Mahasiswa Tanpa Kelompok') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Mahasiswa yang belum terdaftar di kelompok manapun</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.users.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <a href="{{ route('groups.create') }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                    <i class="fas fa-users mr-2"></i>Buat Kelompok
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg shadow-md">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Statistics Per Class -->
            <div class="bg-gradient-to-r from-orange-500 to-red-500 rounded-lg shadow-lg p-6 mb-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-2xl font-bold">Ringkasan Per Kelas</h3>
                        <p class="text-orange-100">Statistik mahasiswa yang belum masuk kelompok</p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-4 rounded-full">
                        <i class="fas fa-chart-pie text-3xl"></i>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mt-6">
                    @foreach($statsPerClass as $stat)
                        <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-lg p-4 border border-white border-opacity-20">
                            <div class="text-sm font-medium text-orange-100 mb-1">{{ $stat->name }}</div>
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl font-bold">{{ $stat->students_without_group }}</span>
                                <span class="text-sm text-orange-100">/ {{ $stat->total_students }}</span>
                            </div>
                            <div class="mt-2 text-xs text-orange-100">
                                @if($stat->total_students > 0)
                                    {{ number_format(($stat->students_without_group / $stat->total_students) * 100, 1) }}% belum masuk kelompok
                                @else
                                    Belum ada mahasiswa
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Filter -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <form method="GET" action="{{ route('admin.users.without-group') }}">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Search -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-search mr-1"></i>Cari Mahasiswa
                            </label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Nama, email, atau ID..." 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                        </div>

                        <!-- Class Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-school mr-1"></i>Filter Kelas
                            </label>
                            <select name="class_room_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                <option value="">Semua Kelas</option>
                                @foreach($classRooms as $classroom)
                                    <option value="{{ $classroom->id }}" {{ request('class_room_id') == $classroom->id ? 'selected' : '' }}>
                                        {{ $classroom->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mt-4">
                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white py-2 px-6 rounded-md">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                        @if(request()->hasAny(['search', 'class_room_id']))
                            <a href="{{ route('admin.users.without-group') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-6 rounded-md">
                                <i class="fas fa-times mr-2"></i>Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Main Content Card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-table mr-2 text-gray-600"></i>Daftar Mahasiswa Tanpa Kelompok
                        </h3>
                        <div class="text-sm text-gray-600">
                            <span class="font-semibold text-orange-600">{{ $students->total() }}</span> mahasiswa belum masuk kelompok
                        </div>
                    </div>

                    @if($students->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-hashtag mr-1"></i>No
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-user mr-1"></i>Mahasiswa
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-id-card mr-1"></i>ID Politala
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-school mr-1"></i>Kelas
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-book mr-1"></i>Program Studi
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <i class="fas fa-cogs mr-1"></i>Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($students as $index => $student)
                                        <tr class="hover:bg-orange-50 transition duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ ($students->currentPage() - 1) * $students->perPage() + $index + 1 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-orange-400 to-red-600 flex items-center justify-center text-white font-semibold">
                                                            {{ strtoupper(substr($student->name, 0, 2)) }}
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $student->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">
                                                {{ $student->politala_id ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($student->classRoom)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                        <i class="fas fa-school mr-1"></i>{{ $student->classRoom->name }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600">
                                                        <i class="fas fa-exclamation-triangle mr-1"></i>Belum ada kelas
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $student->program_studi ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <div class="flex items-center justify-center space-x-2">
                                                    <a href="{{ route('admin.users.show', $student) }}" 
                                                       class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-100 hover:bg-blue-200 hover:text-blue-900 rounded-lg transition">
                                                        <i class="fas fa-eye mr-1.5"></i>Detail
                                                    </a>
                                                    <a href="{{ route('admin.users.edit', $student) }}" 
                                                       class="inline-flex items-center px-3 py-2 text-sm font-medium text-green-600 bg-green-100 hover:bg-green-200 hover:text-green-900 rounded-lg transition">
                                                        <i class="fas fa-edit mr-1.5"></i>Edit
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $students->appends(request()->except('page'))->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-green-400 mb-4">
                                <i class="fas fa-check-circle text-6xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Semua mahasiswa sudah masuk kelompok!</h3>
                            <p class="text-gray-500 mb-4">Tidak ada mahasiswa yang belum terdaftar di kelompok.</p>
                            @if(request()->has('class_room_id') || request()->has('search'))
                                <a href="{{ route('admin.users.without-group') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-700 text-white font-bold rounded-lg shadow-md transition">
                                    <i class="fas fa-redo mr-2"></i>Lihat Semua Kelas
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Help Card -->
            <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Informasi</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Mahasiswa yang belum masuk kelompok akan ditampilkan di halaman ini</li>
                                <li>Pastikan setiap mahasiswa sudah memiliki kelas sebelum dimasukkan ke kelompok</li>
                                <li>Anda dapat langsung membuat kelompok baru dari halaman ini</li>
                                <li>Sistem hanya akan menampilkan mahasiswa dari kelas yang sama saat membuat kelompok</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
