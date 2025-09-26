<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <i class="fas fa-tachometer-alt mr-2 text-blue-600"></i>Dashboard PBL
                </h2>
                <p class="text-sm text-gray-600 mt-1">Sistem Penilaian Project Based Learning</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center bg-blue-100 px-3 py-2 rounded-lg">
                    <i class="{{ Auth::user()->role_icon ?? 'fas fa-chalkboard-teacher' }} text-blue-600 mr-2"></i>
                    <span class="text-blue-800 font-medium">{{ Auth::user()->role_display ?? 'Dosen' }}</span>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">Selamat datang,</div>
                    <div class="font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg p-8 mb-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">
                            <i class="fas fa-graduation-cap mr-3"></i>Selamat Datang di Sistem PBL
                        </h1>
                        <p class="text-blue-100 text-lg">Kelola penilaian Project Based Learning dengan mudah dan efisien</p>
                        <div class="mt-4 flex items-center space-x-4 text-blue-100">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                <span>{{ now()->format('d F Y') }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-2"></i>
                                <span id="current-time">{{ now()->format('H:i:s') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="hidden lg:block">
                        <div class="bg-white bg-opacity-20 p-6 rounded-lg">
                            <i class="fas fa-chart-line text-6xl text-white opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Kelompok -->
                <div class="bg-white overflow-hidden shadow-xl rounded-xl">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-blue-500 rounded-lg p-3">
                                    <i class="fas fa-users text-white text-2xl"></i>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Kelompok</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">{{ $totalKelompok }}</div>
                                        <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                            <i class="fas fa-arrow-up mr-1"></i>
                                            <span class="sr-only">Increased by</span>
                                            Aktif
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3">
                        <div class="text-sm">
                            <a href="{{ route('groups.index') }}" class="font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">
                                Kelola kelompok <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Total Kriteria -->
                <div class="bg-white overflow-hidden shadow-xl rounded-xl">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-green-500 rounded-lg p-3">
                                    <i class="fas fa-list-check text-white text-2xl"></i>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Kriteria Penilaian</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">{{ $totalKriteria }}</div>
                                        <div class="ml-2 flex items-baseline text-sm font-semibold text-blue-600">
                                            <i class="fas fa-layer-group mr-1"></i>
                                            <span class="sr-only">Group criteria</span>
                                            Group
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3">
                        <div class="text-sm">
                            <a href="{{ route('criteria.index') }}" class="font-medium text-green-600 hover:text-green-500 transition duration-150 ease-in-out">
                                Kelola kriteria <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Total Input Nilai -->
                <div class="bg-white overflow-hidden shadow-xl rounded-xl">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-purple-500 rounded-lg p-3">
                                    <i class="fas fa-star text-white text-2xl"></i>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Input Nilai</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">{{ $totalInputNilai }}</div>
                                        <div class="ml-2 flex items-baseline text-sm font-semibold text-purple-600">
                                            <i class="fas fa-chart-bar mr-1"></i>
                                            <span class="sr-only">Scores entered</span>
                                            Score
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3">
                        <div class="text-sm">
                            <a href="{{ route('scores.index') }}" class="font-medium text-purple-600 hover:text-purple-500 transition duration-150 ease-in-out">
                                Input nilai <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow-xl rounded-xl overflow-hidden mb-8">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-bolt mr-2 text-yellow-500"></i>Aksi Cepat
                    </h3>
                    <p class="text-sm text-gray-600">Akses fitur utama dengan cepat</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <a href="{{ route('groups.create') }}" 
                           class="group bg-gradient-to-br from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 p-6 rounded-lg transition duration-300 transform hover:scale-105 border border-blue-200">
                            <div class="flex items-center">
                                <div class="bg-blue-500 group-hover:bg-blue-600 p-3 rounded-lg transition duration-300">
                                    <i class="fas fa-plus text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-semibold text-blue-800">Tambah Kelompok</h4>
                                    <p class="text-sm text-blue-600">Buat kelompok baru</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('criteria.create') }}" 
                           class="group bg-gradient-to-br from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 p-6 rounded-lg transition duration-300 transform hover:scale-105 border border-green-200">
                            <div class="flex items-center">
                                <div class="bg-green-500 group-hover:bg-green-600 p-3 rounded-lg transition duration-300">
                                    <i class="fas fa-plus text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-semibold text-green-800">Tambah Kriteria</h4>
                                    <p class="text-sm text-green-600">Buat kriteria baru</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('scores.create') }}" 
                           class="group bg-gradient-to-br from-purple-50 to-purple-100 hover:from-purple-100 hover:to-purple-200 p-6 rounded-lg transition duration-300 transform hover:scale-105 border border-purple-200">
                            <div class="flex items-center">
                                <div class="bg-purple-500 group-hover:bg-purple-600 p-3 rounded-lg transition duration-300">
                                    <i class="fas fa-edit text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-semibold text-purple-800">Input Nilai</h4>
                                    <p class="text-sm text-purple-600">Beri nilai kelompok</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('scores.index') }}" 
                           class="group bg-gradient-to-br from-orange-50 to-orange-100 hover:from-orange-100 hover:to-orange-200 p-6 rounded-lg transition duration-300 transform hover:scale-105 border border-orange-200">
                            <div class="flex items-center">
                                <div class="bg-orange-500 group-hover:bg-orange-600 p-3 rounded-lg transition duration-300">
                                    <i class="fas fa-trophy text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-semibold text-orange-800">Lihat Ranking</h4>
                                    <p class="text-sm text-orange-600">Peringkat kelompok</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tips untuk User -->
            <div class="bg-white shadow-xl rounded-xl overflow-hidden">
                <div class="px-6 py-4 bg-blue-50 border-b border-blue-200">
                    <h3 class="text-lg font-semibold text-blue-800">
                        <i class="fas fa-lightbulb mr-2 text-yellow-500"></i>Tips untuk {{ Auth::user()->role_display ?? 'Dosen' }}
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="flex items-start">
                            <div class="bg-blue-100 p-2 rounded-full mr-3 mt-1">
                                <i class="fas fa-check text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Atur Kriteria</h4>
                                <p class="text-sm text-gray-600">Buat kriteria penilaian terlebih dahulu</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="bg-green-100 p-2 rounded-full mr-3 mt-1">
                                <i class="fas fa-users text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Kelola Kelompok</h4>
                                <p class="text-sm text-gray-600">Atur kelompok sesuai proyek PBL</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="bg-purple-100 p-2 rounded-full mr-3 mt-1">
                                <i class="fas fa-chart-line text-purple-600 text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Pantau Ranking</h4>
                                <p class="text-sm text-gray-600">Sistem otomatis menghitung peringkat</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Real-time Clock -->
    <script>
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { hour12: false });
            const timeElement = document.getElementById('current-time');
            if (timeElement) {
                timeElement.textContent = timeString;
            }
        }
        
        setInterval(updateTime, 1000);
        updateTime();
    </script>
</x-app-layout>
