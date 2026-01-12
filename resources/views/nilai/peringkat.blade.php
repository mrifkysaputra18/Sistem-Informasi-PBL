{{-- View: Peringkat Kelompok | Controller: NilaiKelompokController@recalc --}}
<x-app-layout>
  <x-slot name="header"><h2 class="font-semibold text-xl text-white">Ranking Kelompok</h2></x-slot>
  <div class="py-6">
    {{-- Tabel hasil ranking kelompok --}}
    <table class="min-w-full bg-white shadow rounded">
      <thead>
        <tr>
          <th class="p-3 text-left">Peringkat</th>
          <th class="p-3 text-left">Kelompok</th>
          <th class="p-3 text-left">Total</th>
        </tr>
      </thead>
      <tbody>
        @php $i=1; @endphp
        {{-- Loop data ranking dari controller --}}
        @foreach($ranking as $gid => $total)
        <tr class="border-t">
          <td class="p-3">{{ $i++ }}</td>
          <td class="p-3">{{ $groups[$gid] ?? ('#'.$gid) }}</td>
          <td class="p-3">{{ number_format($total, 4, ',', '.') }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</x-app-layout>
