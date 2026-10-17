@extends('layouts.cashier')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-white border-l-4 border-primary pl-4">Daftar Reservasi Meja</h2>
</div>

<div class="bg-cardBg border border-borderDark rounded-xl p-6 shadow-lg overflow-x-auto">
    <table class="w-full text-left text-sm text-gray-400">
        <thead class="text-xs uppercase bg-darkBg text-gray-300">
            <tr>
                <th class="px-4 py-3 rounded-tl-lg">Kode Booking</th>
                <th class="px-4 py-3">Customer</th>
                <th class="px-4 py-3">Meja</th>
                <th class="px-4 py-3">Jadwal Kedatangan</th>
                <th class="px-4 py-3">Jumlah Tamu</th>
                <th class="px-4 py-3 rounded-tr-lg">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservations as $res)
            <tr class="border-b border-borderDark last:border-0 hover:bg-darkBg transition">
                <td class="px-4 py-4 font-bold text-white">{{ $res->booking_code }}</td>
                <td class="px-4 py-4">
                    <p class="text-white font-semibold">{{ $res->user->name ?? 'Guest' }}</p>
                    <p class="text-xs">{{ $res->user->phone ?? '-' }}</p>
                </td>
                <td class="px-4 py-4 font-semibold text-primary">{{ $res->table->table_number ?? 'Belum Pilih' }}</td>
                <td class="px-4 py-4">
                    <p class="text-white">{{ \Carbon\Carbon::parse($res->date)->format('d M Y') }}</p>
                    <p class="text-xs text-primary">{{ \Carbon\Carbon::parse($res->time)->format('H:i') }} WIB</p>
                </td>
                <td class="px-4 py-4">{{ $res->pax }} Orang</td>
                <td class="px-4 py-4">
                    @if($res->status == 'pending')
                        <span class="text-yellow-500 bg-yellow-500 bg-opacity-20 px-2 py-1 rounded text-xs font-bold uppercase">Pending</span>
                    @elseif($res->status == 'confirmed')
                        <span class="text-blue-500 bg-blue-500 bg-opacity-20 px-2 py-1 rounded text-xs font-bold uppercase">Confirmed</span>
                    @elseif($res->status == 'completed')
                        <span class="text-green-500 bg-green-500 bg-opacity-20 px-2 py-1 rounded text-xs font-bold uppercase">Selesai</span>
                    @else
                        <span class="text-red-500 bg-red-500 bg-opacity-20 px-2 py-1 rounded text-xs font-bold uppercase">Batal</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-500">Belum ada data reservasi pelanggan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection