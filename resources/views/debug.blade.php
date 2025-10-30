<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">Debug Data</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Groups Data</h3>
                @php 
                    $groups = \App\Models\Kelompok::with('term')->get();
                    $criteria = \App\Models\Criterion::all();
                    $scores = \App\Models\KelompokScore::all();
                @endphp
                
                <p>Total Groups: {{ $groups->count() }}</p>
                <p>Total Criteria: {{ $criteria->count() }}</p>
                <p>Total Scores: {{ $scores->count() }}</p>

                @if($groups->count() > 0)
                    <h4 class="font-semibold mt-4">Groups:</h4>
                    <ul>
                        @foreach($groups as $group)
                            <li>{{ $group->kode }} - {{ $group->nama }}</li>
                        @endforeach
                    </ul>
                @endif

                @if($criteria->count() > 0)
                    <h4 class="font-semibold mt-4">Criteria:</h4>
                    <ul>
                        @foreach($criteria as $criterion)
                            <li>{{ $criterion->nama }} ({{ $criterion->tipe }}, {{ $criterion->segment }})</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

