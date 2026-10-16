@extends('layouts.customer')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h2 class="text-3xl font-bold text-white mb-8 border-l-4 border-primary pl-4">Riwayat Aktivitas</h2>

    @if(session('success'))
        <div class="bg-green-500 bg-opacity-20 border border-green-500 text-green-500 p-4 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Panel Reservasi -->
        <div class="bg-cardBg border border-borderDark rounded-xl p-6">
            <h3 class="text-xl font-bold text-white mb-4">Reservasi Saya</h3>
            <div class="space-y-4">
                <!-- Simulasi Data Reservasi -->
                <div class="bg-darkBg border border-borderDark p-4 rounded-lg flex justify-between items-center">
                    <div>
                        <p class="text-primary font-bold">#RES-20261015-001</p>
                        <p class="text-white text-lg">Meja 5 <span class="text-sm text-gray-400">(4 Orang)</span></p>
                        <p class="text-gray-400 text-sm">15 Okt 2026, 17:00 WIB</p>
                    </div>
                    <div class="text-right">
                        <span class="bg-green-500 bg-opacity-20 text-green-500 text-xs px-2 py-1 rounded font-bold uppercase tracking-wider mb-2 inline-block">Confirmed</span>
                        <!-- Tombol Pre-Order sesuai FR-CUST-005 -->
                        <a href="{{ route('menu') }}" class="block text-sm bg-primary hover:bg-orange-600 text-white px-3 py-1 rounded transition">Pre-Order Menu</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Pesanan -->
        <div class="bg-cardBg border border-borderDark rounded-xl p-6">
            <h3 class="text-xl font-bold text-white mb-4">Riwayat Pesanan</h3>
            <div class="space-y-4">
                <!-- Simulasi Data Pesanan -->
                <div class="bg-darkBg border border-borderDark p-4 rounded-lg flex justify-between items-center">
                    <div>
                        <p class="text-primary font-bold">#ORD-20261015-001</p>
                        <p class="text-white">3 Items (Rp 125.000)</p>
                        <p class="text-gray-400 text-sm">Pre-Order untuk 15 Okt 2026</p>
                    </div>
                    <div class="text-right">
                        <span class="bg-red-500 bg-opacity-20 text-red-500 text-xs px-2 py-1 rounded font-bold uppercase tracking-wider mb-2 inline-block">Unpaid</span>
                        <button class="block text-sm border border-primary text-primary hover:bg-primary hover:text-white px-3 py-1 rounded transition w-full">Detail</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection