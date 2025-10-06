<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Dashboard Mahasiswa
                </h2>
                <p class="text-sm text-gray-600">Selamat datang, {{ auth()->user()->name }}</p>
            </div>
            @if($myGroup)
            <div class="flex gap-2">
                <span class="bg-purple-100 text-purple-800 font-semibold py-2 px-4 rounded">
                    <i class="fas fa-users mr-2"></i>{{ $myGroup->name }}
                </span>
                <a href="{{ route('targets.submissions.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-bullseye mr-2"></i>Target Mingguan
                </a>
            </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($myGroup)
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <!-- Total Targets -->
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">Total Target</p>
                                <p class="text-3xl font-bold mt-2">{{ $stats['totalTargets'] }}</p>
                            </div>
                            <div class="bg-blue-400 bg-opacity-50 p-3 rounded-full">
                                <i class="fas fa-bullseye text-3xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Completed Targets -->
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-sm font-medium">Target Selesai</p>
                                <p class="text-3xl font-bold mt-2">{{ $stats['completedTargets'] }}</p>
                            </div>
                            <div class="bg-green-400 bg-opacity-50 p-3 rounded-full">
                                <i class="fas fa-check-circle text-3xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Completion Rate -->
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-100 text-sm font-medium">Tingkat Selesai</p>
                                <p class="text-3xl font-bold mt-2">{{ $stats['completionRate'] }}%</p>
                            </div>
                            <div class="bg-purple-400 bg-opacity-50 p-3 rounded-full">
                                <i class="fas fa-chart-pie text-3xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Targets -->
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-orange-100 text-sm font-medium">Target Pending</p>
                                <p class="text-3xl font-bold mt-2">{{ $stats['pendingTargets'] }}</p>
                            </div>
                            <div class="bg-orange-400 bg-opacity-50 p-3 rounded-full">
                                <i class="fas fa-hourglass-half text-3xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Group Info Card -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
                        <h3 class="font-semibold text-white text-lg">
                            <i class="fas fa-users mr-2"></i>
                            Informasi Kelompok
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Nama Kelompok</p>
                                <p class="font-semibold text-gray-800 text-lg">{{ $myGroup->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Kelas</p>
                                <p class="font-semibold text-gray-800 text-lg">
                                    @if($myGroup->classRoom)
                                    {{ $myGroup->classRoom->name }}
                                    @else
                                    -
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Ketua Kelompok</p>
                                <p class="font-semibold text-gray-800">
                                    @if($myGroup->leader)
                                    {{ $myGroup->leader->name }}
                                    @if($myGroup->leader->id === auth()->id())
                                    <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Anda
                                    </span>
                                    @endif
                                    @else
                                    Belum ada ketua
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Jumlah Anggota</p>
                                <p class="font-semibold text-gray-800">
                                    {{ $myGroup->members->count() }} / {{ $myGroup->max_members }} Anggota
                                </p>
                            </div>
                        </div>

                        <!-- Members List -->
                        <div class="mt-6">
                            <p class="text-sm text-gray-600 mb-3 font-medium">Anggota Kelompok:</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($myGroup->members as $member)
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="bg-purple-100 p-2 rounded-full mr-3">
                                        <i class="fas fa-user text-purple-600 text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800">
                                            {{ $member->user->name }}
                                            @if($member->user->id === auth()->id())
                                            <span class="ml-1 text-purple-600 text-xs">(Anda)</span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-600">{{ $member->user->email }}</p>
                                    </div>
                                    @if($member->is_leader)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-star mr-1"></i>Ketua
                                    </span>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="grid grid-cols-1 gap-6">
                    <!-- Weekly Targets -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="font-semibold text-gray-800">
                                <i class="fas fa-tasks mr-2 text-blue-600"></i>
                                Target Mingguan
                            </h3>
                            <a href="{{ route('weekly-targets.create', ['group_id' => $myGroup->id]) }}" 
                               class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded inline-block">
                                + Tambah Target
                            </a>
                        </div>
                        <div class="p-6">
                            @if($weeklyTargets->count() > 0)
                                <!-- Target List in Table Format -->
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">
                                                    Status
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                                                    Minggu
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Target
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Bukti
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Waktu Selesai
                                                </th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-48">
                                                    Aksi
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($weeklyTargets as $target)
                                            <tr class="hover:bg-gray-50 {{ $target->is_completed ? 'bg-green-50' : '' }}">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($target->is_completed)
                                                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                                    @else
                                                    <i class="far fa-circle text-gray-400 text-xl"></i>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        Minggu {{ $target->week_number }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div>
                                                        <p class="font-medium text-gray-900 {{ $target->is_completed ? 'line-through' : '' }}">
                                                            {{ $target->title }}
                                                        </p>
                                                        @if($target->description)
                                                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($target->description, 80) }}</p>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($target->is_checked_only)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            <i class="fas fa-check mr-1"></i>Hanya Centang
                                                        </span>
                                                    @elseif($target->evidence_files && count($target->evidence_files) > 0)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <i class="fas fa-file mr-1"></i>{{ count($target->evidence_files) }} file
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400 text-sm">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($target->completed_at)
                                                        <p class="text-sm text-gray-600">{{ $target->completed_at->format('d/m/Y') }}</p>
                                                        <p class="text-xs text-gray-500">{{ $target->completed_at->diffForHumans() }}</p>
                                                    @else
                                                        <span class="text-gray-400 text-sm">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <div class="flex items-center justify-center gap-2">
                                                        @if($target->isReviewed())
                                                            <!-- Target sudah dinilai - tidak bisa diubah -->
                                                            <span class="text-xs text-gray-500 italic bg-gray-100 px-3 py-1.5 rounded">
                                                                <i class="fas fa-lock mr-1"></i>Sudah Dinilai
                                                            </span>
                                                        @else
                                                            <!-- Target belum dinilai - bisa diubah -->
                                                            @if(!$target->is_completed)
                                                            <form action="{{ route('weekly-targets.complete', $target) }}" method="POST" class="inline">
                                                                @csrf
                                                                <button type="submit" 
                                                                        class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1.5 rounded transition duration-200"
                                                                        title="Tandai Selesai">
                                                                    <i class="fas fa-check mr-1"></i>Selesai
                                                                </button>
                                                            </form>
                                                            @else
                                                            <form action="{{ route('weekly-targets.uncomplete', $target) }}" method="POST" class="inline">
                                                                @csrf
                                                                <button type="submit" 
                                                                        class="bg-gray-500 hover:bg-gray-600 text-white text-xs px-3 py-1.5 rounded transition duration-200"
                                                                        title="Batal Selesai">
                                                                    <i class="fas fa-undo mr-1"></i>Batal
                                                                </button>
                                                            </form>
                                                            @endif
                                                            
                                                            <a href="{{ route('weekly-targets.edit', $target) }}" 
                                                               class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1.5 rounded inline-block transition duration-200"
                                                               title="Edit Target">
                                                                <i class="fas fa-edit mr-1"></i>Edit
                                                            </a>
                                                            
                                                            <form action="{{ route('weekly-targets.destroy', $target) }}" 
                                                                  method="POST" 
                                                                  class="inline"
                                                                  onsubmit="return confirm('Yakin ingin menghapus target ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1.5 rounded transition duration-200"
                                                                        title="Hapus Target">
                                                                    <i class="fas fa-trash mr-1"></i>Hapus
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <i class="fas fa-tasks text-gray-400 text-5xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada target mingguan</h3>
                                    <p class="text-gray-600 mb-6">Buat target mingguan pertama Anda untuk memulai tracking progress</p>
                                    <a href="{{ route('weekly-targets.create', ['group_id' => $myGroup->id]) }}" 
                                       class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-6 py-3 rounded-lg inline-block">
                                        <i class="fas fa-plus mr-2"></i>Tambah Target Pertama
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Progress Completion Chart -->
                <div class="mt-6 bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-800">
                            <i class="fas fa-chart-line mr-2 text-green-600"></i>
                            Progress Penyelesaian
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="mb-2 flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Tingkat Penyelesaian Target</span>
                            <span class="text-sm font-bold text-gray-900">{{ $stats['completionRate'] }}%</span>
                        </div>
                        @php
                            $completionWidth = min(100, max(0, $stats['completionRate']));
                            $widthStyle = 'width: ' . $completionWidth . '%';
                        @endphp
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="bg-gradient-to-r from-green-400 to-green-600 h-4 rounded-full transition-all duration-500" 
                                 style="{{ $widthStyle }}">
                            </div>
                        </div>
                        <div class="mt-4 grid grid-cols-3 gap-4 text-center">
                            <div class="p-3 bg-green-50 rounded-lg">
                                <p class="text-2xl font-bold text-green-600">{{ $stats['completedTargets'] }}</p>
                                <p class="text-xs text-gray-600">Selesai</p>
                            </div>
                            <div class="p-3 bg-orange-50 rounded-lg">
                                <p class="text-2xl font-bold text-orange-600">{{ $stats['pendingTargets'] }}</p>
                                <p class="text-xs text-gray-600">Pending</p>
                            </div>
                            <div class="p-3 bg-blue-50 rounded-lg">
                                <p class="text-2xl font-bold text-blue-600">{{ $stats['totalTargets'] }}</p>
                                <p class="text-xs text-gray-600">Total</p>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <!-- No Group Message -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-12 text-center">
                        <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-users text-gray-400 text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Anda Belum Memiliki Kelompok</h3>
                        <p class="text-gray-600 mb-6">Silakan hubungi koordinator atau admin untuk ditambahkan ke kelompok.</p>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 max-w-md mx-auto">
                            <p class="text-sm text-blue-800">
                                <i class="fas fa-info-circle mr-2"></i>
                                Hanya koordinator dan admin yang dapat menambahkan Anda ke kelompok.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

