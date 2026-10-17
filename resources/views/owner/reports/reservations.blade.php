@extends('layouts.owner')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <h2 class="text-3xl font-bold text-white border-l-4 border-primary pl-4">Laporan Reservasi</h2>
    
    <!-- Filter Periode & Export -->
    <div class="flex flex-wrap items-center gap-3">
        <form action="{{ route('owner.reports.reservations') }}" method="GET" class="flex items-center gap-2">
            <select name="period" onchange="this.form.submit()" class="bg-cardBg border border-borderDark rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary text-sm">
                <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Hari Ini</option>
                <option value="yesterday" {{ $period == 'yesterday' ? 'selected' : '' }}>Kemarin</option>
                <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Bulan Ini</option>
            </select>
        </form>
        
        <button class="bg-red-600 hover:bg-red-700 text-white text-sm font-bold py-2 px-4 rounded-lg transition flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            PDF
        </button>
        <button class="bg-green-600 hover:bg-green-700 text-white text-sm font-bold py-2 px-4 rounded-lg transition flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Excel
        </button>
    </div>
</div>

<!-- Stat Cards Reservasi -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-cardBg border border-borderDark rounded-xl p-6 shadow-lg">
        <p class="text-gray-400 text-sm font-semibold mb-1">Total Reservasi</p>
        <h3 class="text-3xl font-bold text-white">{{ $totalReservations }}</h3>
    </div>
    <div class="bg-cardBg border border-borderDark rounded-xl p-6 shadow-lg">
        <p class="text-gray-400 text-sm font-semibold mb-1">Total Tamu (Pax)</p>
        <h3 class="text-3xl font-bold text-primary">{{ $totalPax }} <span class="text-sm font-normal text-gray-500">Orang</span></h3>
    </div>
    <div class="bg-cardBg border border-borderDark rounded-xl p-6 shadow-lg">
        <p class="text-gray-400 text-sm font-semibold mb-1">Reservasi Selesai</p>
        <h3 class="text-3xl font-bold text-green-500">{{ $completedReservations }}</h3>
    </div>
    <div class="bg-cardBg border border-borderDark rounded-xl p-6 shadow-lg">
        <p class="text-gray-400 text-sm font-semibold mb-1">Tingkat Batal</p>
        <h3 class="text-3xl font-bold text-red-500">{{ number_format($noShowRate, 1) }}%</h3>
    </div>
</div>

<!-- Tabel Data Reservasi -->
<div class="bg-cardBg border border-borderDark rounded-xl p-6 shadow-lg overflow-x-auto">
    <h3 class="text-xl font-bold text-white mb-4">Detail Data Reservasi</h3>
    <table class="w-full text-left text-sm text-gray-400">
        <thead class="text-xs uppercase bg-darkBg text-gray-300">
            <tr>
                <th class="px-4 py-3 rounded-tl-lg">Kode Booking</th>
                <th class="px-4 py-3">Pelanggan</th>
                <th class="px-4 py-3">Meja</th>
                <th class="px-4 py-3">Jadwal</th>
                <th class="px-4 py-3">Tamu</th>
                <th class="px-4 py-3 rounded-tr-lg">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservations as $res)
            <tr class="border-b border-borderDark last:border-0 hover:bg-darkBg transition">
                <td class="px-4 py-3 font-bold text-white">{{ $res->booking_code }}</td>
                <td class="px-4 py-3">{{ $res->user->name ?? 'User Dihapus' }}</td>
                <td class="px-4 py-3 font-semibold text-primary">{{ $res->table->table_number ?? 'N/A' }}</td>
                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($res->date)->format('d M Y') }} | {{ $res->time }}</td>
                <td class="px-4 py-3">{{ $res->pax }} Org</td>
                <td class="px-4 py-3">
                    @if($res->status == 'pending')
                        <span class="text-yellow-500 bg-yellow-500 bg-opacity-20 px-2 py-1 rounded text-xs font-bold uppercase">Pending</span>
                    @elseif($res->status == 'confirmed')
                        <span class="text-blue-500 bg-blue-500 bg-opacity-20 px-2 py-1 rounded text-xs font-bold uppercase">Confirmed</span>
                    @elseif($res->status == 'completed')
                        <span class="text-green-500 bg-green-500 bg-opacity-20 px-2 py-1 rounded text-xs font-bold uppercase">Completed</span>
                    @else
                        <span class="text-red-500 bg-red-500 bg-opacity-20 px-2 py-1 rounded text-xs font-bold uppercase">Cancelled</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-500">Tidak ada data reservasi pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection