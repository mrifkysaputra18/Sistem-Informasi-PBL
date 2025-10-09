<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            <i class="fas fa-clipboard-check mr-2"></i>{{ __('Review Target Mingguan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">
                            Target Yang Perlu Direview
                        </h3>
                        <p class="text-sm text-gray-600">
                            Daftar target mingguan yang sudah diselesaikan mahasiswa dan perlu dinilai
                        </p>
                    </div>

                    @if($pendingTargets->count() > 0)
                        <!-- Targets Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kelompok
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Minggu
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Target
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Diselesaikan
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pendingTargets as $target)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $target->group->name }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $target->group->classRoom->name ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                                Minggu {{ $target->week_number }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $target->title }}</div>
                                            @if($target->description)
                                            <div class="text-xs text-gray-500 mt-1">{{ Str::limit($target->description, 50) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div>{{ $target->completed_at->format('d/m/Y H:i') }}</div>
                                            <div class="text-xs text-gray-400">{{ $target->completed_at->diffForHumans() }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <a href="{{ route('target-reviews.show', $target) }}" 
                                               class="bg-green-500 hover:bg-green-600 text-white text-xs px-4 py-2 rounded inline-block transition duration-200">
                                                <i class="fas fa-star mr-1"></i>Review & Nilai
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $pendingTargets->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-clipboard-check text-gray-400 text-5xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada target yang perlu direview</h3>
                            <p class="text-gray-600">Semua target sudah dinilai atau belum ada target yang diselesaikan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

