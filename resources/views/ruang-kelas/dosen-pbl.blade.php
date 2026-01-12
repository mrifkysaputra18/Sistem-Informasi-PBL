<x-app-layout>
    <div class="py-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- HEADER -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                <div>
                    <nav class="flex items-center space-x-2 text-sm bg-white/80 backdrop-blur-sm px-4 py-2.5 rounded-xl shadow-sm border border-gray-100 mb-3">
                        <a href="{{ route('classrooms.index') }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 font-semibold transition-all duration-200 group">
                            <i class="fa-solid fa-school mr-2 text-indigo-500 group-hover:scale-110 transition-transform"></i>
                            Kelas
                        </a>
                        <span class="text-gray-300">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </span>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gray-100 text-gray-700 font-medium">
                            {{ $classRoom->name }}
                        </span>
                        <span class="text-gray-300">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </span>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-semibold shadow-sm">
                            <i class="fa-solid fa-chalkboard-user mr-2"></i>
                            Dosen PBL
                        </span>
                    </nav>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">DOSEN PBL KELAS</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">
                        Kelola dosen PBL untuk kelas <strong>{{ $classRoom->name }}</strong>
                    </p>
                </div>
                <a href="{{ route('classrooms.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-bold rounded-lg shadow transition-all">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>

            <!-- Alert Messages -->
            @if(session('ok'))
                <div x-data="{ show: true }" x-show="show" class="bg-emerald-100 border-l-8 border-emerald-600 text-emerald-800 px-6 py-4 rounded-lg shadow-md mb-8 flex items-start justify-between">
                    <div class="flex items-center">
                        <i class="fa-solid fa-check-circle text-2xl mr-4 text-emerald-600"></i>
                        <span class="font-bold text-lg">{{ session('ok') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-600 hover:text-emerald-800"><i class="fa-solid fa-times text-xl"></i></button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" class="bg-red-100 border-l-8 border-red-600 text-red-800 px-6 py-4 rounded-lg shadow-md mb-8 flex items-start justify-between">
                    <div class="flex items-center">
                        <i class="fa-solid fa-exclamation-triangle text-2xl mr-4 text-red-600"></i>
                        <span class="font-bold text-lg">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-times text-xl"></i></button>
                </div>
            @endif

            <!-- Info Kelas -->
            <div class="bg-white rounded-xl shadow-md border-l-4 border-indigo-600 p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="h-14 w-14 rounded-lg bg-indigo-600 flex items-center justify-center text-white text-xl font-bold shadow-lg mr-4">
                            {{ strtoupper(substr($classRoom->name, 0, 2)) }}
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-gray-900">{{ $classRoom->name }}</h3>
                            <p class="text-sm text-gray-500">
                                <span class="font-mono bg-gray-100 px-2 py-0.5 rounded">{{ $classRoom->code }}</span>
                                · {{ $classRoom->academicPeriod?->name ?? '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-gray-400 uppercase">Total Dosen PBL</p>
                        <p class="text-3xl font-black text-indigo-600">{{ $dosenSebelumUts->count() + $dosenSesudahUts->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Daftar Dosen PBL -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Sebelum UTS -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="bg-blue-600 px-6 py-4">
                            <h3 class="text-lg font-bold text-white flex items-center">
                                <i class="fa-solid fa-calendar-minus mr-3"></i>
                                Dosen PBL - Sebelum UTS
                            </h3>
                        </div>
                        
                        @if($dosenSebelumUts->count() > 0)
                            <div class="divide-y divide-gray-200">
                                @foreach($dosenSebelumUts as $dosen)
                                    <div class="p-4 flex items-center justify-between hover:bg-gray-50">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold mr-3">
                                                {{ strtoupper(substr($dosen->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900">{{ $dosen->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $dosen->email }}</p>
                                            </div>
                                        </div>
                                        <form action="{{ route('dosen-pbl.destroy', $dosen->pivot->id) }}" method="POST" 
                                              onsubmit="return confirm('Hapus dosen PBL ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded shadow">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="py-8 text-center text-gray-500">
                                <i class="fa-solid fa-user-slash text-3xl mb-2"></i>
                                <p>Belum ada Dosen PBL untuk periode ini</p>
                            </div>
                        @endif
                    </div>

                    <!-- Sesudah UTS -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="bg-emerald-600 px-6 py-4">
                            <h3 class="text-lg font-bold text-white flex items-center">
                                <i class="fa-solid fa-calendar-plus mr-3"></i>
                                Dosen PBL - Sesudah UTS
                            </h3>
                        </div>
                        
                        @if($dosenSesudahUts->count() > 0)
                            <div class="divide-y divide-gray-200">
                                @foreach($dosenSesudahUts as $dosen)
                                    <div class="p-4 flex items-center justify-between hover:bg-gray-50">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold mr-3">
                                                {{ strtoupper(substr($dosen->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900">{{ $dosen->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $dosen->email }}</p>
                                            </div>
                                        </div>
                                        <form action="{{ route('dosen-pbl.destroy', $dosen->pivot->id) }}" method="POST" 
                                              onsubmit="return confirm('Hapus dosen PBL ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded shadow">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="py-8 text-center text-gray-500">
                                <i class="fa-solid fa-user-slash text-3xl mb-2"></i>
                                <p>Belum ada Dosen PBL untuk periode ini</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Form Tambah Dosen PBL -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden sticky top-8">
                        <div class="bg-indigo-600 px-6 py-4">
                            <h3 class="text-lg font-bold text-white flex items-center">
                                <i class="fa-solid fa-user-plus mr-3"></i>
                                Tambah Dosen PBL
                            </h3>
                        </div>
                        
                        <form action="{{ route('classrooms.dosen-pbl.store', $classRoom) }}" method="POST" class="p-6">
                            @csrf
                            
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Pilih Dosen</label>
                                <select name="dosen_id" required
                                        class="w-full h-12 bg-gray-50 border-2 border-gray-200 rounded-lg text-sm font-semibold focus:border-indigo-600 focus:ring-0">
                                    <option value="">-- Pilih Dosen --</option>
                                    @foreach($availableDosens as $dosen)
                                        <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                                    @endforeach
                                </select>
                                @error('dosen_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-6">
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Periode</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="flex items-center p-3 border-2 rounded-lg cursor-pointer hover:border-blue-400 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                                        <input type="radio" name="periode" value="sebelum_uts" class="text-blue-600 mr-2" required>
                                        <span class="text-sm font-semibold">Sebelum UTS</span>
                                    </label>
                                    <label class="flex items-center p-3 border-2 rounded-lg cursor-pointer hover:border-emerald-400 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50">
                                        <input type="radio" name="periode" value="sesudah_uts" class="text-emerald-600 mr-2">
                                        <span class="text-sm font-semibold">Sesudah UTS</span>
                                    </label>
                                </div>
                                @error('periode')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <button type="submit" 
                                    class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-lg transition-all">
                                <i class="fa-solid fa-plus mr-2"></i> Tambahkan
                            </button>
                        </form>
                        
                        <div class="px-6 pb-6">
                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-xs text-yellow-700">
                                    <i class="fa-solid fa-info-circle mr-1"></i>
                                    <strong>Info:</strong> Hanya Dosen PBL yang bisa membuat target mingguan untuk kelompok di kelas ini.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
