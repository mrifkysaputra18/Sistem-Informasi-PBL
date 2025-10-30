<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-white leading-tight">
                    {{ $group->name }}
                </h2>
                <p class="text-sm text-gray-600">{{ $group->classRoom->name ?? 'Tanpa Kelas' }}</p>
            </div>
            <div class="flex gap-2">
                @if($group->classRoom)
                <a href="{{ url('/classrooms/' . $group->classRoom->id) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    ← Kembali ke Kelas
                </a>
                @else
                <a href="{{ route('groups.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    ← Kembali
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Info Kelompok -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Info Kelompok</h3>
                            
                            <div class="space-y-3">
                                <div>
                                    <div class="text-sm text-gray-600">Nama Kelompok</div>
                                    <div class="font-semibold">{{ $group->name }}</div>
                                </div>

                                @if($group->leader)
                                <div>
                                    <div class="text-sm text-gray-600">Ketua Kelompok</div>
                                    <div class="font-semibold flex items-center">
                                        <span class="text-primary-600 mr-1">★</span>
                                        {{ $group->leader->name }}
                                    </div>
                                </div>
                                @else
                                <div class="bg-yellow-50 border border-yellow-200 rounded p-3">
                                    <div class="text-sm text-yellow-800">
                                        ⚠️ Belum ada ketua kelompok
                                    </div>
                                </div>
                                @endif

                                <div>
                                    <div class="text-sm text-gray-600">Jumlah Anggota</div>
                                    <div class="flex items-center justify-between">
                                        <span class="font-semibold">{{ $group->members->count() }} / {{ $group->max_members }}</span>
                                        <span class="px-2 py-1 text-xs rounded-full {{ $group->isFull() ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $group->isFull() ? 'PENUH' : 'Tersedia' }}
                                        </span>
                                    </div>
                                    <div class="mt-2 w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                        @php
                                            $memberPercentage = $group->max_members > 0 
                                                ? min(100, max(0, ($group->members->count() / $group->max_members) * 100))
                                                : 0;
                                        @endphp
                                        <div class="bg-primary-600 h-full transition-all duration-300" style="width: <?php echo $memberPercentage; ?>%"></div>
                                    </div>
                                </div>

                                @if($group->classRoom)
                                <div>
                                    <div class="text-sm text-gray-600">Kelas</div>
                                    <div class="font-semibold">{{ $group->classRoom->name }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Daftar Anggota & Tambah Anggota -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Daftar Anggota Saat Ini -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Anggota Kelompok ({{ $group->members->count() }})</h3>
                            
                            @if($group->members->isEmpty())
                            <div class="text-center py-8 text-gray-500">
                                <p>Belum ada anggota dalam kelompok ini.</p>
                                <p class="text-sm mt-2">Silakan tambahkan anggota menggunakan form di bawah.</p>
                            </div>
                            @else
                            <div class="space-y-2">
                                @foreach($group->members as $member)
                                <div class="flex items-center justify-between p-3 border rounded-lg {{ $member->is_leader ? 'bg-primary-50 border-primary-200' : 'bg-gray-50' }}">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center font-bold text-gray-600">
                                            {{ strtoupper(substr($member->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold flex items-center gap-2">
                                                {{ $member->user->name }}
                                                @if($member->is_leader)
                                                <span class="px-2 py-0.5 bg-primary-600 text-white text-xs rounded-full">Ketua</span>
                                                @endif
                                            </div>
                                            <div class="text-sm text-gray-600">{{ $member->user->email }}</div>
                                            @if($member->user->nim)
                                            <div class="text-xs text-blue-600 font-medium">
                                                <i class="fas fa-graduation-cap mr-1"></i>NIM: {{ $member->user->nim }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <form method="POST" action="{{ route('groups.remove-member', [$group, $member->id]) }}"
                                          id="remove-member-form-{{ $member->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                onclick="removeMember({{ $member->id }}, '{{ addslashes($member->user->name) }}')"
                                                class="text-red-600 hover:text-red-800 font-semibold text-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Form Tambah Anggota -->
                    @if(!$group->isFull())
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Tambah Anggota Baru</h3>
                            
                            @if($availableStudents->isEmpty())
                            <div class="text-center py-8 text-gray-500">
                                <p>Tidak ada mahasiswa yang tersedia untuk ditambahkan.</p>
                                <p class="text-sm mt-2">Semua mahasiswa sudah menjadi anggota kelompok ini.</p>
                            </div>
                            @else
                            <form method="POST" action="{{ route('groups.add-member', $group) }}">
                                @csrf
                                
                                <div class="mb-4">
                                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Pilih Mahasiswa <span class="text-red-500">*</span>
                                    </label>
                                    <select name="user_id" id="user_id" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-blue-500">
                                        <option value="">-- Pilih Mahasiswa --</option>
                                        @foreach($availableStudents as $student)
                                        <option value="{{ $student->id }}">
                                            {{ $student->name }} @if($student->nim) - NIM: {{ $student->nim }} @endif ({{ $student->email }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="is_leader" value="1"
                                            class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700">
                                            Jadikan sebagai <strong>Ketua Kelompok</strong>
                                        </span>
                                    </label>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Jika dicentang, ketua lama akan diganti dengan anggota ini
                                    </p>
                                </div>

                                <button type="submit" class="w-full bg-primary-500 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded">
                                    + Tambah Anggota
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h4 class="font-semibold text-yellow-800 mb-2">⚠️ Kelompok Penuh</h4>
                        <p class="text-sm text-yellow-700">
                            Kelompok ini sudah mencapai batas maksimal {{ $group->max_members }} anggota.
                            Hapus anggota terlebih dahulu jika ingin menambahkan yang baru.
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function removeMember(memberId, memberName) {
            const form = document.getElementById('remove-member-form-' + memberId);
            
            confirmDelete(
                'Hapus Anggota?',
                `Apakah Anda yakin ingin menghapus <strong>"${memberName}"</strong> dari kelompok ini?<br><small class="text-gray-500">Tindakan ini tidak dapat dibatalkan.</small>`,
                form
            );
        }
    </script>
</x-app-layout>
