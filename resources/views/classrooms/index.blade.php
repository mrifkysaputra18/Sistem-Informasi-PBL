<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pilih Kelas') }}
            </h2>
            @if(auth()->user()->isAdmin() || auth()->user()->isDosen())
            <a href="{{ route('classrooms.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Teknik Informatika - Semester 3</h3>
                    
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
                                    <div class="bg-blue-600 h-full transition-all duration-300" style="width: <?php echo $percentage; ?>%"></div>
                                </div>
                            </div>

                            <a href="{{ route('classrooms.show', $classRoom) }}" class="block w-full text-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Kelola Kelompok
                            </a>
                        </div>
                        @empty
                        <div class="col-span-3 text-center py-8 text-gray-500">
                            Belum ada kelas. Silakan buat kelas terlebih dahulu.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
