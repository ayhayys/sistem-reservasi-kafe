@extends('layouts.cashier')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-white border-l-4 border-primary pl-4">Pesanan Masuk</h2>
</div>

<div class="space-y-6">
    @forelse($orders as $order)
    <div class="bg-cardBg border border-borderDark rounded-xl p-6 shadow-lg flex flex-col md:flex-row justify-between gap-6">
        
        <!-- Info Kiri: Detail Pesanan & Customer -->
        <div class="flex-1 space-y-3">
            <div class="flex items-center space-x-4">
                <h3 class="text-xl font-bold text-white">{{ $order->order_number }}</h3>
                @if($order->payment_status == 'paid')
                    <span class="bg-green-500 bg-opacity-20 text-green-500 text-xs font-bold px-2 py-1 rounded uppercase border border-green-500">LUNAS</span>
                @else
                    <span class="bg-red-500 bg-opacity-20 text-red-500 text-xs font-bold px-2 py-1 rounded uppercase border border-red-500">BELUM BAYAR</span>
                @endif
                <span class="bg-gray-600 bg-opacity-50 text-gray-300 text-xs font-bold px-2 py-1 rounded uppercase">{{ $order->payment_method }}</span>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-gray-400">Customer</p>
                    <p class="font-semibold text-white">{{ $order->user->name ?? 'Guest' }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Tgl Dihidangkan</p>
                    <p class="font-semibold text-primary">{{ \Carbon\Carbon::parse($order->serve_date)->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Reservasi Terhubung</p>
                    <p class="font-semibold text-white">{{ $order->reservation->booking_code ?? 'Tidak ada' }}</p>
                </div>
            </div>

            <!-- List Menu -->
            <div class="mt-4 pt-4 border-t border-borderDark">
                <p class="text-xs text-gray-400 mb-2">Rincian Menu:</p>
                <div class="space-y-1">
                    @foreach($order->orderItems as $item)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-300">- {{ $item->menu->name ?? 'Menu Dihapus' }} <span class="text-gray-500 font-bold ml-1">x{{ $item->quantity }}</span></span>
                        <span class="text-gray-400">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Info Kanan: Total & Aksi Kasir -->
        <div class="w-full md:w-64 bg-darkBg rounded-lg p-4 border border-borderDark flex flex-col justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-1">Total Tagihan:</p>
                <h4 class="text-2xl font-bold text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</h4>
            </div>
            
            <div class="mt-6">
                @if($order->payment_status !== 'paid')
                <form action="{{ route('cashier.orders.pay', $order->id) }}" method="POST" onsubmit="return confirm('Konfirmasi bahwa pesanan ini sudah dibayar lunas?');">
                    @csrf
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition shadow flex justify-center items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Konfirmasi Pembayaran
                    </button>
                </form>
                @else
                <button disabled class="w-full bg-gray-600 text-gray-400 font-bold py-3 px-4 rounded-lg cursor-not-allowed flex justify-center items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Sudah Dibayar
                </button>
                @endif
            </div>
        </div>
        
    </div>
    @empty
    <div class="bg-cardBg border border-borderDark rounded-xl p-8 text-center">
        <p class="text-gray-400">Belum ada pesanan masuk saat ini.</p>
    </div>
    @endforelse
</div>
@endsection