<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('ahp.index') }}" 
               class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Panduan Metode AHP') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8">
                    <!-- Intro -->
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">
                            <i class="fas fa-book mr-2 text-purple-600"></i>
                            Apa itu AHP?
                        </h3>
                        <p class="text-gray-700 mb-4">
                            <strong>AHP (Analytical Hierarchy Process)</strong> adalah metode pengambilan keputusan yang dikembangkan oleh Thomas L. Saaty. Metode ini membantu menentukan bobot kriteria secara objektif berdasarkan perbandingan berpasangan.
                        </p>
                    </div>

                    <!-- Skala Perbandingan -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">
                            <i class="fas fa-balance-scale mr-2 text-orange-600"></i>
                            Skala Perbandingan AHP
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nilai</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Penjelasan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr><td class="px-4 py-2 font-bold">1</td><td class="px-4 py-2">Sama penting</td><td class="px-4 py-2">Kedua kriteria sama pentingnya</td></tr>
                                    <tr><td class="px-4 py-2 font-bold">3</td><td class="px-4 py-2">Cukup penting</td><td class="px-4 py-2">Kriteria A sedikit lebih penting dari B</td></tr>
                                    <tr><td class="px-4 py-2 font-bold">5</td><td class="px-4 py-2">Sangat penting</td><td class="px-4 py-2">Kriteria A lebih penting dari B</td></tr>
                                    <tr><td class="px-4 py-2 font-bold">7</td><td class="px-4 py-2">Jauh lebih penting</td><td class="px-4 py-2">Kriteria A jauh lebih penting dari B</td></tr>
                                    <tr><td class="px-4 py-2 font-bold">9</td><td class="px-4 py-2">Mutlak lebih penting</td><td class="px-4 py-2">Kriteria A mutlak lebih penting dari B</td></tr>
                                    <tr><td class="px-4 py-2 font-bold">2, 4, 6, 8</td><td class="px-4 py-2">Nilai antara</td><td class="px-4 py-2">Nilai kompromi antara dua penilaian</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Langkah-langkah -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">
                            <i class="fas fa-list-ol mr-2 text-blue-600"></i>
                            Langkah-Langkah Menggunakan AHP
                        </h3>
                        <div class="space-y-4">
                            <div class="flex">
                                <div class="flex-shrink-0 w-8 h-8 bg-purple-500 text-white rounded-full flex items-center justify-center font-bold mr-3">1</div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">Pilih Segment</h4>
                                    <p class="text-gray-600 text-sm">Pilih apakah ingin menghitung bobot untuk Kriteria Kelompok atau Kriteria Mahasiswa.</p>
                                </div>
                            </div>
                            <div class="flex">
                                <div class="flex-shrink-0 w-8 h-8 bg-purple-500 text-white rounded-full flex items-center justify-center font-bold mr-3">2</div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">Bandingkan Kriteria</h4>
                                    <p class="text-gray-600 text-sm">Geser slider untuk membandingkan setiap pasang kriteria. Pilih nilai 1-9 sesuai tingkat kepentingan.</p>
                                </div>
                            </div>
                            <div class="flex">
                                <div class="flex-shrink-0 w-8 h-8 bg-purple-500 text-white rounded-full flex items-center justify-center font-bold mr-3">3</div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">Hitung Bobot</h4>
                                    <p class="text-gray-600 text-sm">Klik tombol "Hitung Bobot" untuk menghitung bobot setiap kriteria.</p>
                                </div>
                            </div>
                            <div class="flex">
                                <div class="flex-shrink-0 w-8 h-8 bg-purple-500 text-white rounded-full flex items-center justify-center font-bold mr-3">4</div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">Cek Konsistensi</h4>
                                    <p class="text-gray-600 text-sm">Periksa nilai CR (Consistency Ratio). Jika CR ≤ 0.1, hasil konsisten dan dapat diterapkan.</p>
                                </div>
                            </div>
                            <div class="flex">
                                <div class="flex-shrink-0 w-8 h-8 bg-purple-500 text-white rounded-full flex items-center justify-center font-bold mr-3">5</div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">Terapkan Bobot</h4>
                                    <p class="text-gray-600 text-sm">Jika konsisten, klik "Terapkan Bobot ke Kriteria" untuk mengupdate bobot di database.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Consistency Ratio -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">
                            <i class="fas fa-check-double mr-2 text-green-600"></i>
                            Consistency Ratio (CR)
                        </h3>
                        <p class="text-gray-700 mb-4">
                            CR adalah ukuran konsistensi perbandingan yang Anda buat. Nilai CR dihitung dari:
                        </p>
                        <div class="bg-gray-50 p-4 rounded-lg mb-4">
                            <code class="text-sm">CR = CI / RI</code><br>
                            <code class="text-sm">CI = (λmax - n) / (n - 1)</code>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <span class="w-32 text-green-600 font-semibold">CR ≤ 0.1</span>
                                <span class="text-gray-700">: Konsisten (Baik) ✓</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-32 text-red-600 font-semibold">CR > 0.1</span>
                                <span class="text-gray-700">: Tidak Konsisten (Perlu Revisi) ✗</span>
                            </div>
                        </div>
                    </div>

                    <!-- Contoh -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">
                            <i class="fas fa-lightbulb mr-2 text-yellow-600"></i>
                            Contoh Kasus
                        </h3>
                        <p class="text-gray-700 mb-4">
                            Misalnya ada 3 kriteria: <strong>Kecepatan Progres</strong>, <strong>Kualitas Output</strong>, dan <strong>Keterlambatan</strong>.
                        </p>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="font-semibold mb-2">Perbandingan:</p>
                            <ul class="list-disc list-inside space-y-1 text-sm">
                                <li>Kecepatan Progres vs Kualitas Output = <strong>3</strong> (Kecepatan sedikit lebih penting)</li>
                                <li>Kecepatan Progres vs Keterlambatan = <strong>5</strong> (Kecepatan lebih penting)</li>
                                <li>Kualitas Output vs Keterlambatan = <strong>3</strong> (Kualitas sedikit lebih penting)</li>
                            </ul>
                            <p class="mt-3 font-semibold">Hasil Bobot:</p>
                            <ul class="list-disc list-inside space-y-1 text-sm">
                                <li>Kecepatan Progres: <strong>0.55</strong> (55%)</li>
                                <li>Kualitas Output: <strong>0.30</strong> (30%)</li>
                                <li>Keterlambatan: <strong>0.15</strong> (15%)</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Tips -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">
                            <i class="fas fa-star mr-2 text-yellow-500"></i>
                            Tips & Trik
                        </h3>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                <span class="text-gray-700">Lakukan perbandingan dengan hati-hati dan konsisten</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                <span class="text-gray-700">Jika CR > 0.1, revisi perbandingan yang tidak konsisten</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                <span class="text-gray-700">Gunakan nilai ganjil (1, 3, 5, 7, 9) untuk kemudahan</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                <span class="text-gray-700">Simpan hasil perhitungan untuk dokumentasi</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Back Button -->
                    <div class="text-center pt-4 border-t">
                        <a href="{{ route('ahp.index') }}" 
                           class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Perhitungan AHP
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

