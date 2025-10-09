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
                @if(!$classRoom->isFull())
                <a href="{{ route('groups.create', ['class_room_id' => $classRoom->id]) }}" class="bg-primary-500 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded">
                    + Buat Kelompok Baru
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
                            <div class="text-3xl font-bold text-primary-600">{{ $classRoom->groups->count() }}</div>
                            <div class="text-sm text-gray-600">Total Kelompok</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded">
                            <div class="text-3xl font-bold text-green-600">{{ $classRoom->max_groups }}</div>
                            <div class="text-sm text-gray-600">Maksimal Kelompok</div>
                        </div>
                        <div class="text-center p-4 bg-secondary-50 rounded">
                            <div class="text-3xl font-bold text-secondary-600">{{ $classRoom->groups->sum(fn($g) => $g->members->count()) }}</div>
                            <div class="text-sm text-gray-600">Total Mahasiswa</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Kelompok -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Daftar Kelompok</h3>
                    
                    @if($classRoom->groups->isEmpty())
                    <div class="text-center py-8 text-gray-500">
                        <p class="mb-4">Belum ada kelompok di kelas ini.</p>
                        <a href="{{ route('groups.create', ['class_room_id' => $classRoom->id]) }}" class="inline-block bg-primary-500 hover:bg-primary-700 text-white font-bold py-2 px-6 rounded">
                            Buat Kelompok Pertama
                        </a>
                    </div>
                    @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($classRoom->groups as $group)
                        <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="text-lg font-bold text-gray-800">{{ $group->name }}</h4>
                                    @if($group->leader)
                                    <p class="text-sm text-gray-600">
                                        <span class="font-semibold">Ketua:</span> {{ $group->leader->name }}
                                    </p>
                                    @endif
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full {{ $group->isFull() ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $group->members->count() }}/{{ $group->max_members }} Anggota
                                </span>
                            </div>

                            <div class="mb-3">
                                <div class="text-xs text-gray-600 mb-1">Anggota:</div>
                                @if($group->members->count() > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($group->members->take(3) as $member)
                                    <span class="px-2 py-1 bg-gray-100 text-xs rounded">
                                        {{ $member->user->name }}
                                        @if($member->user->nim)
                                        <span class="text-blue-600 font-medium"> ({{ $member->user->nim }})</span>
                                        @endif
                                        @if($member->is_leader)
                                        <span class="text-primary-600">★</span>
                                        @endif
                                    </span>
                                    @endforeach
                                    @if($group->members->count() > 3)
                                    <span class="px-2 py-1 text-xs text-gray-600">
                                        +{{ $group->members->count() - 3 }} lainnya
                                    </span>
                                    @endif
                                </div>
                                @else
                                <p class="text-xs text-gray-500 italic">Belum ada anggota</p>
                                @endif
                            </div>

                            <div class="flex gap-2">
                                <a href="{{ route('groups.show', $group) }}" class="flex-1 text-center bg-primary-500 hover:bg-primary-700 text-white text-sm font-bold py-2 px-4 rounded">
                                    Kelola Anggota
                                </a>
                                <a href="{{ route('groups.edit', $group) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white text-sm font-bold py-2 px-3 rounded">
                                    Edit
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
