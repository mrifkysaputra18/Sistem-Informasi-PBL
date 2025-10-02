<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Dashboard Dosen
                </h2>
                <p class="text-sm text-gray-600">Selamat datang, {{ auth()->user()->name }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('classrooms.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-school mr-2"></i>Kelas
                </a>
                <a href="{{ route('scores.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-star mr-2"></i>Input Nilai
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total Class Rooms -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Kelas</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['totalClassRooms'] }}</p>
                        </div>
                        <div class="bg-blue-400 bg-opacity-50 p-3 rounded-full">
                            <i class="fas fa-school text-3xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Groups -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Total Kelompok</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['totalGroups'] }}</p>
                        </div>
                        <div class="bg-purple-400 bg-opacity-50 p-3 rounded-full">
                            <i class="fas fa-users text-3xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Scores -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Nilai Tersimpan</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['totalScores'] }}</p>
                        </div>
                        <div class="bg-green-400 bg-opacity-50 p-3 rounded-full">
                            <i class="fas fa-star text-3xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Pending Reviews -->
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm font-medium">Perlu Review</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['pendingReviews'] }}</p>
                        </div>
                        <div class="bg-orange-400 bg-opacity-50 p-3 rounded-full">
                            <i class="fas fa-clock text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Classes with Groups -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-800">
                            <i class="fas fa-school mr-2 text-blue-600"></i>
                            Kelas & Kelompok
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($classRooms->count() > 0)
                            <div class="space-y-3">
                                @foreach($classRooms as $classRoom)
                                <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center flex-1">
                                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                                <i class="fas fa-school text-blue-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800">{{ $classRoom->name }}</p>
                                                <p class="text-xs text-gray-600">
                                                    @if($classRoom->subject)
                                                    {{ $classRoom->subject->name }} • 
                                                    @endif
                                                    {{ $classRoom->program_studi }}
                                                </p>
                                            </div>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ $classRoom->groups_count }} Kelompok
                                        </span>
                                    </div>
                                    <a href="{{ route('classrooms.show', $classRoom) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                        Lihat Detail →
                                    </a>
                                </div>
                                @endforeach
                            </div>
                            <div class="mt-4 text-center">
                                <a href="{{ route('classrooms.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Lihat Semua Kelas →
                                </a>
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">Belum ada kelas</p>
                        @endif
                    </div>
                </div>

                <!-- Progress to Review -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-800">
                            <i class="fas fa-file-alt mr-2 text-orange-600"></i>
                            Progress Perlu Review
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($progressToReview->count() > 0)
                            <div class="space-y-3">
                                @foreach($progressToReview as $progress)
                                <div class="p-3 bg-orange-50 rounded-lg border border-orange-200">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start flex-1">
                                            <div class="bg-orange-100 p-2 rounded-full mr-3 mt-0.5">
                                                <i class="fas fa-file text-orange-600 text-sm"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-medium text-gray-800">{{ $progress->title }}</p>
                                                <p class="text-xs text-gray-600 mt-1">
                                                    @if($progress->group)
                                                    {{ $progress->group->name }}
                                                    @if($progress->group->classRoom)
                                                    • {{ $progress->group->classRoom->name }}
                                                    @endif
                                                    @endif
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    Minggu {{ $progress->week_number }} • 
                                                    Submit: {{ $progress->submitted_at ? $progress->submitted_at->diffForHumans() : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2 flex gap-2">
                                        <button class="flex-1 bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1 rounded">
                                            Review
                                        </button>
                                        <button class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded">
                                            Detail
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-check-circle text-green-500 text-4xl mb-3"></i>
                                <p class="text-gray-600">Tidak ada progress yang perlu direview</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <a href="{{ route('scores.create') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-full mr-4">
                            <i class="fas fa-star text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Input Nilai</h3>
                            <p class="text-sm text-gray-600">Berikan nilai kelompok</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('scores.index') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center">
                        <div class="bg-purple-100 p-3 rounded-full mr-4">
                            <i class="fas fa-trophy text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Lihat Ranking</h3>
                            <p class="text-sm text-gray-600">Monitor peringkat</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('groups.index') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Lihat Kelompok</h3>
                            <p class="text-sm text-gray-600">Monitor kelompok</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>


