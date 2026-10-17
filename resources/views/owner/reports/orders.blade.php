@extends('layouts.owner')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <h2 class="text-3xl font-bold text-white border-l-4 border-primary pl-4">Laporan Pemesanan</h2>
    
    <!-- Filter Periode & Export -->
    <div class="flex flex-wrap items-center gap-3">
        <form action="{{ route('owner.reports.orders') }}" method="GET" class="flex items-center gap-2">
            <select name="period" onchange="this.form.submit()" class="bg-cardBg border border-borderDark rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary text-sm">
                <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Hari Ini</option>
                <option value="yesterday" {{ $period == 'yesterday' ? 'selected' : '' }}>Kemarin</option>
                <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Bulan Ini</option>
            </select>
        </form>
        
        <!-- Tombol Export (UI disiapkan, routing backend menyesuaikan package PDF/Excel) -->
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

<!-- Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-cardBg border border-borderDark rounded-xl p-6 shadow-lg">
        <p class="text-gray-400 text-sm font-semibold mb-1">Total Pendapatan</p>
        <h3 class="text-3xl font-bold text-green-500">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
    </div>
    <div class="bg-cardBg border border-borderDark rounded-xl p-6 shadow-lg">
        <p class="text-gray-400 text-sm font-semibold mb-1">Total Pesanan Selesai</p>
        <h3 class="text-3xl font-bold text-white">{{ $totalOrders }} <span class="text-sm font-normal text-gray-500">Transaksi</span></h3>
    </div>
    <div class="bg-cardBg border border-borderDark rounded-xl p-6 shadow-lg">
        <p class="text-gray-400 text-sm font-semibold mb-1">Rata-rata Nilai Pesanan</p>
        <h3 class="text-3xl font-bold text-primary">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</h3>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Chart Section -->
    <div class="lg:col-span-2 bg-cardBg border border-borderDark rounded-xl p-6 shadow-lg">
        <h3 class="text-xl font-bold text-white mb-4">Grafik Pendapatan (7 Hari Terakhir)</h3>
        <div class="w-full h-72">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Tabel Rincian Transaksi -->
    <div class="bg-cardBg border border-borderDark rounded-xl p-6 shadow-lg overflow-hidden flex flex-col h-full">
        <h3 class="text-xl font-bold text-white mb-4">Riwayat Transaksi</h3>
        <div class="overflow-y-auto flex-1 max-h-72 pr-2 space-y-3">
            @forelse($orders as $order)
                <div class="bg-darkBg p-3 rounded-lg border border-borderDark flex justify-between items-center">
                    <div>
                        <p class="text-sm font-bold text-white">{{ $order->order_number }}</p>
                        <p class="text-xs text-gray-400">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-green-400">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                    </div>
                </div>
            @empty
                <p class="text-gray-400 text-sm text-center py-4">Belum ada data transaksi pada periode ini.</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Script Chart.js -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const rawData = @json($chartData);
        
        const labels = rawData.map(item => item.date);
        const dataPoints = rawData.map(item => item.total);

        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: dataPoints,
                    borderColor: '#D97D54',
                    backgroundColor: 'rgba(217, 125, 84, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#D97D54'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#4D4D4D' },
                        ticks: { color: '#9CA3AF' }
                    },
                    x: {
                        grid: { color: '#4D4D4D', display: false },
                        ticks: { color: '#9CA3AF' }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });
</script>
@endsection