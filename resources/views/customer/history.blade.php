@extends('layouts.customer')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-white border-l-4 border-primary pl-4">Riwayat & Status Pesanan</h2>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
    
    <!-- ========================================== -->
    <!-- KOLOM KIRI: RESERVASI SAYA -->
    <!-- ========================================== -->
    <div class="bg-cardBg border border-borderDark rounded-xl p-6 shadow-lg">
        <h3 class="text-xl font-bold text-white mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Reservasi Saya
        </h3>
        
        <div class="space-y-4">
            @forelse($reservations as $res)
                @php
                    // Logika Diperbarui: Cukup cek kesamaan tanggal tanpa mempedulikan reservation_id
                    $linkedOrdersCount = $orders->filter(function($ord) use ($res) {
                        return \Carbon\Carbon::parse($ord->serve_date)->toDateString() == \Carbon\Carbon::parse($res->date)->toDateString();
                    })->count();
                @endphp
                <div class="bg-darkBg border border-borderDark rounded-lg p-4">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h4 class="font-bold text-white text-sm">{{ $res->booking_code }}</h4>
                            <p class="text-xs text-gray-300 font-bold mt-1">Meja {{ $res->table->table_number ?? '-' }} <span class="font-normal text-gray-400">({{ $res->pax }} Orang)</span></p>
                            <p class="text-xs text-gray-400 mt-1">Tanggal: {{ \Carbon\Carbon::parse($res->date)->format('d M Y') }} | Jam: {{ \Carbon\Carbon::parse($res->time)->format('H:i') }} WIB</p>
                        </div>
                        <div>
                            @if($res->status == 'pending')
                                <span class="bg-yellow-500 bg-opacity-20 text-yellow-500 text-[10px] font-bold px-2 py-1 rounded uppercase">Menunggu</span>
                            @elseif($res->status == 'confirmed')
                                <span class="bg-blue-500 bg-opacity-20 text-blue-500 text-[10px] font-bold px-2 py-1 rounded uppercase">Dikonfirmasi</span>
                            @elseif($res->status == 'completed')
                                <span class="bg-green-500 bg-opacity-20 text-green-500 text-[10px] font-bold px-2 py-1 rounded uppercase">Selesai</span>
                            @else
                                <span class="bg-red-500 bg-opacity-20 text-red-500 text-[10px] font-bold px-2 py-1 rounded uppercase">Batal</span>
                            @endif
                        </div>
                    </div>
                    <div class="border-t border-borderDark mt-3 pt-3 flex justify-between items-center">
                        <span class="text-xs text-gray-400">Status Pre-Order:</span>
                        @if($linkedOrdersCount > 0)
                            <span class="text-xs font-bold text-green-500">{{ $linkedOrdersCount }} Pesanan Terhubung</span>
                        @else
                            <span class="text-xs font-medium text-gray-500">Tidak ada pesanan</span>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-sm text-center py-4">Belum ada riwayat reservasi.</p>
            @endforelse
        </div>
    </div>

    <!-- ========================================== -->
    <!-- KOLOM KANAN: PESANAN MAKANAN & MINUMAN -->
    <!-- ========================================== -->
    <div class="bg-cardBg border border-borderDark rounded-xl p-6 shadow-lg">
        <h3 class="text-xl font-bold text-white mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            Pesanan Makanan & Minuman
        </h3>
        
        <div class="space-y-4">
            @forelse($orders as $order)
                @php
                    // Logika Diperbarui: Cari reservasi milik user ini yang tanggalnya sama dengan serve_date pesanan
                    $matchingReservation = $reservations->first(function($res) use ($order) {
                        return \Carbon\Carbon::parse($res->date)->toDateString() == \Carbon\Carbon::parse($order->serve_date)->toDateString();
                    });
                @endphp
                <div class="bg-darkBg border border-borderDark rounded-lg p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div class="space-y-1">
                            <h4 class="font-bold text-white text-sm">{{ $order->order_number }}</h4>
                            <p class="text-xs text-gray-400">Dibuat: {{ $order->created_at->format('d M Y, H:i') }} WIB</p>
                            <p class="text-xs text-primary font-bold">Untuk: {{ \Carbon\Carbon::parse($order->serve_date)->format('d M Y') }}</p>
                            
                            <!-- Menampilkan status koneksi ke reservasi berdasarkan pencocokan tanggal -->
                            @if($matchingReservation)
                                <p class="text-xs text-green-500 font-semibold pt-1">
                                    Terhubung ke: {{ $matchingReservation->booking_code }} (Meja {{ $matchingReservation->table->table_number ?? '-' }})
                                </p>
                            @else
                                <p class="text-xs text-gray-500 pt-1">Tidak terhubung ke reservasi</p>
                            @endif
                        </div>
                        
                        <div class="text-right">
                            <p class="font-bold text-primary mb-2">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                            @if($order->payment_status == 'paid')
                                <span class="bg-green-500 bg-opacity-20 text-green-500 text-[10px] font-bold px-2 py-1 rounded uppercase">LUNAS</span>
                            @else
                                <span class="bg-red-500 bg-opacity-20 text-red-500 text-[10px] font-bold px-2 py-1 rounded uppercase">BELUM BAYAR</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="border-t border-borderDark pt-3 mt-1">
                        <p class="text-xs text-gray-400 mb-2">Rincian Item:</p>
                        @foreach($order->orderItems as $item)
                            <div class="flex justify-between text-sm mb-1.5">
                                <span class="text-gray-300">{{ $item->menu->name ?? 'Item' }} <span class="text-gray-500 font-semibold text-xs ml-1">x{{ $item->quantity }}</span></span>
                                <span class="text-gray-300">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-sm text-center py-4">Belum ada riwayat pesanan.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection