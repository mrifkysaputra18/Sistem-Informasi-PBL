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
            </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($myGroup)

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

