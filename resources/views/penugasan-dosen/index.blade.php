<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- HEADER -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">DOSEN PBL</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">
                        Kelola penugasan Dosen Pembimbing PBL per Kelas
                    </p>
                </div>
                
                <!-- Filter Periode -->
                <form method="GET" action="{{ route('penugasan-dosen.index') }}" class="flex items-center gap-3">
                    <label class="text-sm font-bold text-gray-700">Periode:</label>
                    <select name="periode_id" onchange="this.form.submit()"
                            class="h-10 bg-white border-2 border-gray-200 rounded-lg text-sm font-semibold focus:border-indigo-600 focus:ring-0">
                        @foreach($allPeriodes as $periode)
                            <option value="{{ $periode->id }}" {{ $selectedPeriodeId == $periode->id ? 'selected' : '' }}>
                                {{ $periode->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <!-- Alert Messages -->
            @if(session('ok'))
                <div x-data="{ show: true }" x-show="show" class="bg-emerald-100 border-l-8 border-emerald-600 text-emerald-800 px-6 py-4 rounded-lg shadow-md mb-6 flex items-start justify-between">
                    <div class="flex items-center">
                        <i class="fa-solid fa-check-circle text-2xl mr-4 text-emerald-600"></i>
                        <span class="font-bold">{{ session('ok') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-600 hover:text-emerald-800"><i class="fa-solid fa-times"></i></button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" class="bg-red-100 border-l-8 border-red-600 text-red-800 px-6 py-4 rounded-lg shadow-md mb-6 flex items-start justify-between">
                    <div class="flex items-center">
                        <i class="fa-solid fa-exclamation-triangle text-2xl mr-4 text-red-600"></i>
                        <span class="font-bold">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-times"></i></button>
                </div>
            @endif

            <!-- Content -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-indigo-600 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                        </svg>
                        Daftar Penugasan Dosen PBL
                    </h3>
                </div>

                <div class="p-6">
                    @include('penugasan-dosen._tab_pbl')
                </div>
            </div>

            <!-- Info Card -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fa-solid fa-info-circle text-blue-600 text-xl mr-3 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-bold text-blue-800">Dosen Mata Kuliah</p>
                        <p class="text-sm text-blue-700 mt-1">
                            Untuk mengelola penugasan Dosen Mata Kuliah, buka halaman 
                            <a href="{{ route('classrooms.index') }}" class="underline font-semibold hover:text-blue-900">Kelas</a> 
                            → pilih kelas → klik "Mata Kuliah" → tombol "Edit Dosen".
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
