<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-widest text-primary-100 font-semibold">
                    Navigasi Koordinator
                </p>
                <h1 class="text-2xl font-bold text-white leading-tight">
                    {{ __('Kurasi Kelas & Kelompok') }}
                </h1>
                <p class="text-primary-50/90 text-sm mt-1">
                    Kelola kelas aktif, monitor kapasitas kelompok, dan arahkan dosen dengan cepat.
                </p>
            </div>
            @if(auth()->user()->isAdmin() || auth()->user()->isDosen())
            <a href="{{ route('classrooms.create') }}" 
               class="inline-flex items-center justify-center gap-2 bg-white text-primary-600 font-semibold px-4 py-2 rounded-xl shadow-md hover:shadow-xl hover:bg-primary-50 transition-all duration-200">
                <i class="fas fa-plus-circle"></i>
                <span>Buat Kelas Baru</span>
            </a>
            @endif
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            @if(session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50/70 px-4 py-3 text-sm text-green-800 flex items-start gap-3 shadow-sm">
                    <span class="mt-0.5">
                        <i class="fas fa-check-circle"></i>
                    </span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-xl border border-red-200 bg-red-50/80 px-4 py-3 text-sm text-red-800 flex items-start gap-3 shadow-sm">
                    <span class="mt-0.5">
                        <i class="fas fa-exclamation-triangle"></i>
                    </span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Insight Tiles -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @php
                    $insights = [
                        [
                            'label' => 'Total Kelas',
                            'value' => number_format($stats['total_classes']),
                            'icon' => 'fa-school',
                            'color' => 'primary',
                        ],
                        [
                            'label' => 'Kelas Aktif',
                            'value' => number_format($stats['active_classes']),
                            'icon' => 'fa-bolt',
                            'color' => 'emerald',
                        ],
                        [
                            'label' => 'Total Kelompok',
                            'value' => number_format($stats['total_groups']),
                            'icon' => 'fa-users',
                            'color' => 'secondary',
                        ],
                        [
                            'label' => 'Rata-rata Terisi',
                            'value' => $stats['average_fill'] . '%',
                            'icon' => 'fa-chart-line',
                            'color' => 'slate',
                        ],
                    ];

                    $colorClasses = [
                        'primary' => 'bg-white text-primary-800 border border-primary-100',
                        'emerald' => 'bg-white text-emerald-700 border border-emerald-100',
                        'secondary' => 'bg-white text-secondary-700 border border-secondary-100',
                        'slate' => 'bg-white text-slate-700 border border-slate-100',
                    ];

                    $iconPills = [
                        'primary' => 'bg-primary-50 text-primary-500',
                        'emerald' => 'bg-emerald-50 text-emerald-500',
                        'secondary' => 'bg-secondary-50 text-secondary-500',
                        'slate' => 'bg-slate-100 text-slate-500',
                    ];
                @endphp

                @foreach($insights as $insight)
                    <div class="rounded-2xl {{ $colorClasses[$insight['color']] }} p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                                    {{ $insight['label'] }}
                                </p>
                                <p class="text-3xl font-bold mt-2 text-gray-900">
                                    {{ $insight['value'] }}
                                </p>
                            </div>
                            <span class="rounded-xl px-3 py-2 {{ $iconPills[$insight['color']] }}">
                                <i class="fas {{ $insight['icon'] }} text-lg"></i>
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Filter Panel -->
            <div class="rounded-2xl bg-white shadow-md border border-gray-100 p-6 space-y-5">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                    <div>
                        <h2 class="text-base font-semibold text-gray-900">Filter kelas</h2>
                        <p class="text-xs text-gray-500 mt-1">
                            Saring data seperlunya agar daftar tetap fokus.
                        </p>
                    </div>
                    @if(request()->hasAny(['search','subject_id','semester']))
                        <a href="{{ route('classrooms.index') }}"
                           class="inline-flex items-center gap-2 text-xs font-medium text-gray-500 hover:text-primary-600 transition-colors">
                            <i class="fas fa-undo-alt"></i>
                            Reset filter
                        </a>
                    @endif
                </div>
                <form method="GET" action="{{ route('classrooms.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="col-span-1 md:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">
                            Cari kelas
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Nama atau kode kelas..."
                                   class="pl-10 pr-4 py-2.5 w-full rounded-xl border border-gray-200 focus:border-primary-400 focus:ring-1 focus:ring-primary-200 transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Mata kuliah</label>
                        <select name="subject_id" class="w-full rounded-xl border border-gray-200 py-2.5 px-3 focus:border-secondary-400 focus:ring-1 focus:ring-secondary-200 transition-all">
                            <option value="">Semua Mata Kuliah</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Semester</label>
                        <select name="semester" class="w-full rounded-xl border border-gray-200 py-2.5 px-3 focus:border-secondary-400 focus:ring-1 focus:ring-secondary-200 transition-all">
                            <option value="">Semua Semester</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester }}" {{ request('semester') == $semester ? 'selected' : '' }}>
                                    Semester {{ $semester }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-4 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-2">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm transition-all duration-200">
                            <i class="fas fa-filter"></i>
                            Terapkan filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Class List -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Daftar Kelas</h2>
                    <p class="text-sm text-gray-500">
                        {{ __('Fitts’s Law: setiap kartu dapat diklik untuk aksi utama.') }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
                    @forelse($classRooms as $classRoom)
                        @php
                            $fillPercentage = $classRoom->max_groups > 0
                                ? min(100, max(0, ($classRoom->groups_count / $classRoom->max_groups) * 100))
                                : 0;
                        @endphp
                        <div class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow hover:shadow-lg transition-all duration-200">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-xs uppercase tracking-widest text-gray-400">Kode Kelas</p>
                                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $classRoom->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $classRoom->code }}</p>
                                </div>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $classRoom->is_active ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-gray-100 text-gray-600 border border-gray-200' }}">
                                    {{ $classRoom->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </div>

                            <div class="mt-5 space-y-4">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Kelompok Terdaftar</span>
                                    <span class="font-semibold text-gray-800">{{ $classRoom->groups_count }} / {{ $classRoom->max_groups }}</span>
                                </div>
                                <div class="w-full h-2 rounded-full bg-gray-100 overflow-hidden">
                                    <div class="h-full rounded-full bg-primary-500 transition-all duration-500" style="width: {{ $fillPercentage }}%"></div>
                                </div>
                                <div class="flex items-center justify-between text-xs text-gray-400">
                                    <span>Progres kapasitas</span>
                                    <span>{{ round($fillPercentage) }}%</span>
                                </div>
                            </div>

                            <div class="mt-6 space-y-2">
                                <a href="{{ route('groups.index', ['classroom' => $classRoom->id]) }}"
                                   class="flex items-center justify-center gap-2 rounded-xl bg-secondary-500 hover:bg-secondary-600 text-white font-semibold py-2.5 transition-all duration-200 shadow">
                                    <i class="fas fa-users"></i>
                                    Lihat Kelompok ({{ $classRoom->groups_count }})
                                </a>

                                @if(auth()->user()->isAdmin() || auth()->user()->isDosen())
                                    <div class="grid grid-cols-2 gap-2">
                                        <a href="{{ url('/classrooms/' . $classRoom->id . '/edit') }}"
                                           class="inline-flex items-center justify-center gap-2 rounded-xl bg-yellow-50 text-yellow-700 border border-yellow-100 py-2 text-sm font-medium hover:bg-yellow-100 transition">
                                            <i class="fas fa-edit"></i>Edit
                                        </a>
                                        <button type="button"
                                                onclick="deleteClass({{ $classRoom->id }}, '{{ addslashes($classRoom->name) }}')"
                                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-50 text-red-600 border border-red-100 py-2 text-sm font-medium hover:bg-red-100 transition">
                                            <i class="fas fa-trash-alt"></i>Hapus
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full">
                            <div class="rounded-2xl border border-dashed border-gray-200 bg-white p-10 text-center shadow-sm">
                                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-primary-50 text-primary-500">
                                    <i class="fas fa-layer-group text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Belum ada kelas</h3>
                                <p class="text-sm text-gray-500 mt-2">
                                    Gunakan tombol aksi utama untuk menambahkan kelas pertama Anda.
                                </p>
                                @if(auth()->user()->isAdmin() || auth()->user()->isDosen())
                                    <a href="{{ route('classrooms.create') }}"
                                       class="inline-flex items-center justify-center gap-2 mt-4 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl shadow-md transition-all">
                                        <i class="fas fa-plus"></i>
                                        Tambah Kelas Sekarang
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforelse
                </div>

                @if($classRooms->hasPages())
                    <div class="pt-4">
                        {{ $classRooms->onEachSide(1)->links() }}
                    </div>
                @endif
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
