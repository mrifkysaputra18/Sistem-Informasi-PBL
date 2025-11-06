<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-white leading-tight">
                    <i class="fas fa-edit mr-2"></i>Input Nilai Mahasiswa
                </h2>
                <p class="text-sm text-white/90 mt-1">Form input nilai mahasiswa dengan perhitungan ranking otomatis</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Info User -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 mb-6 text-white">
                <div class="flex items-center gap-4">
                    <div class="bg-white/20 p-4 rounded-full">
                        <i class="fas {{ $dosen->role === 'admin' ? 'fa-user-shield' : 'fa-user-tie' }} text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold">{{ $dosen->name }}</h3>
                        <p class="text-blue-100">{{ $dosen->email }}</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/20 mt-2">
                            @if($dosen->role === 'admin')
                                <i class="fas fa-user-shield mr-1"></i> Admin
                            @else
                                <i class="fas fa-chalkboard-teacher mr-1"></i> Dosen
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Form Pilih Kelas -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-filter text-blue-600"></i>
                    Pilih Kelas
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="class_room_id" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                        <select id="class_room_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($classRooms as $classRoom)
                                <option value="{{ $classRoom->id }}">{{ $classRoom->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button onclick="loadStudents()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition-all duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-search"></i>
                            Tampilkan Mahasiswa
                        </button>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div id="loading-state" class="hidden bg-white rounded-xl shadow-lg p-12 text-center">
                <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-blue-600 mx-auto mb-4"></div>
                <p class="text-gray-600">Memuat data mahasiswa...</p>
            </div>

            <!-- Tabel Input Nilai -->
            <div id="score-table-container" class="hidden">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                        <h3 class="font-bold text-white flex items-center gap-2">
                            <i class="fas fa-table"></i>
                            Tabel Input Nilai Mahasiswa
                        </h3>
                        <p class="text-indigo-100 text-sm mt-1">Masukkan nilai untuk setiap kriteria (0-100)</p>
                    </div>
                    
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="score-table">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">NIM</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama Mahasiswa</th>
                                        @foreach($criteria as $criterion)
                                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider" title="{{ $criterion->nama }}">
                                                {{ Str::limit($criterion->nama, 20) }}
                                                <br>
                                                <span class="text-indigo-600 font-semibold">({{ number_format($criterion->bobot * 100, 0, ',', '.') }}%)</span>
                                            </th>
                                        @endforeach
                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="score-table-body">
                                    <!-- Data akan diisi via JavaScript -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="mt-6 flex gap-4">
                            <button onclick="saveScores()" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                                <i class="fas fa-save"></i>
                                Simpan Nilai
                            </button>
                            <button onclick="calculateRanking()" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                                <i class="fas fa-calculator"></i>
                                Hitung Ranking Otomatis
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hasil Perhitungan Ranking -->
            <div id="ranking-result" class="hidden">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-yellow-500 to-orange-600 px-6 py-4">
                        <h3 class="font-bold text-white flex items-center gap-2">
                            <i class="fas fa-trophy"></i>
                            Hasil Perhitungan Ranking (Metode SAW)
                        </h3>
                        <p class="text-yellow-100 text-sm mt-1">Ranking mahasiswa berdasarkan nilai yang diinput</p>
                    </div>
                    
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Rank</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">NIM</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama Mahasiswa</th>
                                        @foreach($criteria as $criterion)
                                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                {{ Str::limit($criterion->nama, 15) }}
                                            </th>
                                        @endforeach
                                        <th class="px-4 py-3 text-center text-xs font-bold text-indigo-700 uppercase tracking-wider bg-indigo-50">Total Skor</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="ranking-table-body">
                                    <!-- Data akan diisi via JavaScript -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Keterangan -->
                        <div class="mt-6 bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <h4 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                                <i class="fas fa-info-circle text-indigo-600"></i>
                                Keterangan Perhitungan:
                            </h4>
                            <ul class="text-sm text-gray-600 space-y-1 ml-6">
                                <li class="flex items-start gap-2">
                                    <span class="text-indigo-600 font-bold">•</span>
                                    <span><strong>Metode SAW</strong> (Simple Additive Weighting) digunakan untuk menghitung ranking</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-indigo-600 font-bold">•</span>
                                    <span><strong>Normalisasi</strong>: Nilai / 100</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-indigo-600 font-bold">•</span>
                                    <span><strong>Weighted Score</strong>: Nilai Ternormalisasi × Bobot Kriteria</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-indigo-600 font-bold">•</span>
                                    <span><strong>Total Skor</strong>: Jumlah dari semua weighted score</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- JavaScript -->
    <script>
        const criteria = @json($criteria);
        let studentsData = [];

        // Load mahasiswa berdasarkan kelas
        async function loadStudents() {
            const classRoomId = document.getElementById('class_room_id').value;
            
            if (!classRoomId) {
                showNotification('Peringatan!', 'Pilih kelas terlebih dahulu!', 'warning');
                return;
            }

            // Show loading
            document.getElementById('loading-state').classList.remove('hidden');
            document.getElementById('score-table-container').classList.add('hidden');
            document.getElementById('ranking-result').classList.add('hidden');

            try {
                const response = await fetch(`/scores/students-by-class?class_room_id=${classRoomId}`, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    studentsData = data.students;
                    renderScoreTable();
                    document.getElementById('score-table-container').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal memuat data mahasiswa');
            } finally {
                document.getElementById('loading-state').classList.add('hidden');
            }
        }

        // Render tabel input nilai
        function renderScoreTable() {
            const tbody = document.getElementById('score-table-body');
            tbody.innerHTML = '';

            studentsData.forEach((student, index) => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50 transition-colors';
                
                let html = `
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">${index + 1}</td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${student.nim || '-'}</td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold">
                                    ${student.name.charAt(0).toUpperCase()}
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-bold text-gray-900">${student.name}</div>
                                <div class="text-xs text-gray-500">${student.email}</div>
                            </div>
                        </div>
                    </td>
                `;

                criteria.forEach(criterion => {
                    const existingScore = student.student_scores?.find(s => s.criterion_id === criterion.id);
                    const score = existingScore ? existingScore.skor : '';
                    // Format score dengan koma untuk tampilan
                    const formattedScore = score ? String(score).replace('.', ',') : '';
                    
                    html += `
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            <input 
                                type="text" 
                                min="0" 
                                max="100" 
                                value="${formattedScore}"
                                data-student-id="${student.id}"
                                data-criterion-id="${criterion.id}"
                                class="score-input w-20 text-center rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="0-100"
                                pattern="[0-9]+([,][0-9]{1,2})?"
                            />
                        </td>
                    `;
                });

                // Tombol Hapus
                html += `
                    <td class="px-4 py-4 whitespace-nowrap text-center">
                        <button 
                            onclick="deleteStudentScores(${student.id}, '${student.name}')"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-200 flex items-center gap-1 mx-auto"
                            title="Hapus semua nilai mahasiswa ini">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                    </td>
                `;

                row.innerHTML = html;
                tbody.appendChild(row);
            });
        }

        // Hapus semua nilai mahasiswa
        async function deleteStudentScores(studentId, studentName) {
            if (!confirm(`Apakah Anda yakin ingin menghapus SEMUA nilai untuk mahasiswa ${studentName}?\n\nNilai yang dihapus tidak dapat dikembalikan!`)) {
                return;
            }

            try {
                const response = await fetch('/scores/student-input/delete-student', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ user_id: studentId })
                });

                const data = await response.json();

                if (data.success) {
                    showNotification('Berhasil!', data.message, 'success');
                    // Reload data mahasiswa
                    loadStudents();
                    // Hide ranking result
                    document.getElementById('ranking-result').classList.add('hidden');
                } else {
                    showNotification('Gagal!', data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error!', 'Gagal menghapus nilai', 'error');
            }
        }

        // Parse angka Indonesia (koma sebagai desimal)
        function parseIndonesianNumber(value) {
            if (!value) return null;
            // Ubah koma menjadi titik untuk parsing
            const normalized = String(value).replace(',', '.');
            return parseFloat(normalized);
        }

        // Simpan nilai
        async function saveScores() {
            const inputs = document.querySelectorAll('.score-input');
            const scores = [];

            inputs.forEach(input => {
                const value = parseIndonesianNumber(input.value);
                if (!isNaN(value) && value >= 0 && value <= 100) {
                    scores.push({
                        user_id: input.dataset.studentId,
                        criterion_id: input.dataset.criterionId,
                        skor: value
                    });
                }
            });

            if (scores.length === 0) {
                showNotification('Peringatan!', 'Tidak ada nilai yang valid untuk disimpan!', 'warning');
                return false;
            }

            try {
                const response = await fetch('/scores/student-input/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ scores })
                });

                const data = await response.json();

                if (data.success) {
                    showNotification('Berhasil!', 'Nilai berhasil disimpan', 'success');
                    return true;
                } else {
                    showNotification('Gagal!', data.message, 'error');
                    return false;
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error!', 'Gagal menyimpan nilai: ' + error.message, 'error');
                return false;
            }
        }

        // Hitung ranking (menyimpan nilai terlebih dahulu, lalu menghitung)
        async function calculateRanking() {
            const classRoomId = document.getElementById('class_room_id').value;

            if (!classRoomId) {
                showNotification('Peringatan!', 'Pilih kelas terlebih dahulu!', 'warning');
                return;
            }

            // Cek apakah ada nilai yang diinput
            const inputs = document.querySelectorAll('.score-input');
            let hasValue = false;
            inputs.forEach(input => {
                if (input.value && input.value.trim() !== '') {
                    hasValue = true;
                }
            });

            if (!hasValue) {
                showNotification('Peringatan!', 'Belum ada nilai yang diinput!', 'warning');
                return;
            }

            try {
                // Step 1: Simpan nilai terlebih dahulu
                showNotification('Menyimpan...', 'Menyimpan nilai mahasiswa...', 'info');
                const saved = await saveScores();
                
                if (!saved) {
                    return; // Jika gagal simpan, stop
                }

                // Step 2: Hitung ranking setelah nilai tersimpan
                const response = await fetch('/scores/student-input/calculate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ class_room_id: classRoomId })
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    renderRankingTable(data.rankings);
                    document.getElementById('ranking-result').classList.remove('hidden');
                    
                    // Scroll to result
                    document.getElementById('ranking-result').scrollIntoView({ behavior: 'smooth' });
                    
                    showNotification('Berhasil!', 'Ranking berhasil dihitung', 'success');
                } else {
                    showNotification('Gagal!', data.message || 'Gagal menghitung ranking', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error!', 'Gagal menghitung ranking: ' + error.message, 'error');
            }
        }

        // Render tabel ranking
        function renderRankingTable(rankings) {
            const tbody = document.getElementById('ranking-table-body');
            tbody.innerHTML = '';

            rankings.forEach((ranking, index) => {
                const row = document.createElement('tr');
                row.className = `hover:bg-gray-50 transition-colors ${index < 3 ? 'bg-yellow-50' : ''}`;
                
                let medalIcon = '';
                if (index === 0) medalIcon = '🥇';
                else if (index === 1) medalIcon = '🥈';
                else if (index === 2) medalIcon = '🥉';
                else medalIcon = ranking.rank;

                let html = `
                    <td class="px-4 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full ${index < 3 ? 'bg-gradient-to-br from-yellow-400 to-yellow-500 text-white font-bold shadow-lg' : 'bg-gray-200 text-gray-700 font-bold'} text-sm">
                            ${medalIcon}
                        </span>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${ranking.student_nim || '-'}</td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm font-bold text-gray-900">${ranking.student_name}</td>
                `;

                ranking.criteria_scores.forEach(cs => {
                    html += `
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            <div class="text-sm font-bold text-gray-900">${formatNumber(cs.raw_score, 2)}</div>
                            <div class="text-xs text-gray-500">W: ${formatNumber(cs.weighted_score, 4)}</div>
                        </td>
                    `;
                });

                html += `
                    <td class="px-4 py-4 whitespace-nowrap text-center bg-indigo-50">
                        <div class="text-lg font-black text-indigo-700">${formatNumber(ranking.total_score, 4)}</div>
                        <div class="text-xs text-indigo-600 font-semibold">${formatNumber(ranking.total_score * 100, 2)}%</div>
                    </td>
                `;

                row.innerHTML = html;
                tbody.appendChild(row);
            });
        }

        // Format angka dengan koma sebagai pemisah desimal
        function formatNumber(number, decimals = 2) {
            return number.toFixed(decimals).replace('.', ',');
        }

        // Show notification
        function showNotification(title, message, type = 'info') {
            // Tentukan warna dan icon berdasarkan tipe
            let bgColor, icon;
            
            switch(type) {
                case 'success':
                    bgColor = 'bg-gradient-to-r from-green-500 to-green-600';
                    icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                    break;
                case 'error':
                    bgColor = 'bg-gradient-to-r from-red-500 to-red-600';
                    icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                    break;
                case 'warning':
                    bgColor = 'bg-gradient-to-r from-yellow-500 to-orange-500';
                    icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>';
                    break;
                default:
                    bgColor = 'bg-gradient-to-r from-blue-500 to-blue-600';
                    icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>';
            }
            
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 z-50 animate-slide-in max-w-md`;
            notification.innerHTML = `
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${icon}
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="font-bold text-lg">${title}</div>
                    <div class="text-sm opacity-90">${message}</div>
                </div>
                <button onclick="this.parentElement.remove()" class="flex-shrink-0 ml-2 hover:bg-white/20 rounded-lg p-1 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            `;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }, 4000);
        }

        // CSS Animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slide-in {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            .animate-slide-in {
                animation: slide-in 0.3s ease-out;
            }
        `;
        document.head.appendChild(style);
    </script>
</x-app-layout>
