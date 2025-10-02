<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('rubric-components.index') }}" 
                   class="mr-4 text-gray-600 hover:text-gray-800 transition duration-200">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Detail Komponen Rubrik') }}
                </h2>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('rubric-components.edit', $rubricComponent) }}" 
                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <a href="{{ route('rubric-components.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-list mr-2"></i>Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Component Details -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-8">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $rubricComponent->name }}</h3>
                            <p class="text-gray-600">{{ $rubricComponent->subject->name }} ({{ $rubricComponent->subject->code }})</p>
                        </div>
                        <div class="flex gap-2">
                            @if($rubricComponent->is_active)
                                <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                                    <i class="fas fa-check-circle mr-1"></i>Aktif
                                </span>
                            @else
                                <span class="bg-gray-100 text-gray-800 text-sm font-medium px-3 py-1 rounded-full">
                                    <i class="fas fa-pause-circle mr-1"></i>Nonaktif
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($rubricComponent->description)
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Deskripsi</h4>
                        <p class="text-gray-600">{{ $rubricComponent->description }}</p>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-percentage text-blue-600 text-2xl mr-3"></i>
                                <div>
                                    <p class="text-sm text-blue-600 font-medium">Bobot</p>
                                    <p class="text-2xl font-bold text-blue-900">{{ $rubricComponent->weight }}%</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-star text-green-600 text-2xl mr-3"></i>
                                <div>
                                    <p class="text-sm text-green-600 font-medium">Nilai Maksimal</p>
                                    <p class="text-2xl font-bold text-green-900">{{ $rubricComponent->max_score }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-purple-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-sort text-purple-600 text-2xl mr-3"></i>
                                <div>
                                    <p class="text-sm text-purple-600 font-medium">Urutan</p>
                                    <p class="text-2xl font-bold text-purple-900">{{ $rubricComponent->order }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scores History -->
            @if($rubricComponent->scores->count() > 0)
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">
                        <i class="fas fa-history mr-2"></i>Riwayat Penilaian
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kelompok
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nilai
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Persentase
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nilai Tertimbang
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Penilai
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Feedback
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($rubricComponent->scores->sortByDesc('scored_at') as $score)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $score->group->name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $score->score }} / {{ $rubricComponent->max_score }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm text-gray-900 mr-2">
                                                {{ number_format($score->percentage, 1) }}%
                                            </div>
                                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full" 
                                                     style="width: {{ min($score->percentage, 100) }}%;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ number_format($score->weighted_score, 2) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $score->scorer->name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">
                                            {{ $score->scored_at->format('d/m/Y H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($score->feedback)
                                            <div class="text-sm text-gray-600 max-w-xs truncate" title="{{ $score->feedback }}">
                                                {{ $score->feedback }}
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-sm">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8 text-center">
                    <i class="fas fa-clipboard-list text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada penilaian</h3>
                    <p class="text-gray-600">Komponen rubrik ini belum memiliki riwayat penilaian.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
