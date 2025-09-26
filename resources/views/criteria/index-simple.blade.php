<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kriteria Penilaian</h2>
            <a href="{{ route('criteria.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah Kriteria</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('ok'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('ok') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 text-left">No</th>
                            <th class="px-4 py-2 text-left">Nama</th>
                            <th class="px-4 py-2 text-left">Bobot</th>
                            <th class="px-4 py-2 text-left">Tipe</th>
                            <th class="px-4 py-2 text-left">Segment</th>
                            <th class="px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($criteria as $index => $criterion)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $index + 1 }}</td>
                                <td class="px-4 py-2">{{ $criterion->nama }}</td>
                                <td class="px-4 py-2">{{ $criterion->bobot }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 text-xs rounded {{ $criterion->tipe == 'benefit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($criterion->tipe) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 text-xs rounded {{ $criterion->segment == 'group' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ ucfirst($criterion->segment) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <a href="{{ route('criteria.edit', $criterion) }}" class="bg-yellow-500 text-white px-2 py-1 rounded text-sm mr-1">Edit</a>
                                    <form action="{{ route('criteria.destroy', $criterion) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Yakin hapus?')" class="bg-red-500 text-white px-2 py-1 rounded text-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    Belum ada kriteria. <a href="{{ route('criteria.create') }}" class="text-blue-500">Tambah sekarang</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($criteria->hasPages())
                    <div class="mt-4">
                        {{ $criteria->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
