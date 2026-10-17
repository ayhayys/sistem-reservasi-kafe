@extends('layouts.customer')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-white">Denah Meja Kafe</h2>
        <p class="text-gray-400 mt-2">Pilih tanggal dan jam kedatangan untuk melihat ketersediaan meja secara real-time.</p>
    </div>

    <!-- Filter Tanggal & Jam Kedatangan -->
    <form method="GET" action="{{ route('table.layout') }}" class="max-w-2xl mx-auto mb-8 bg-cardBg border border-borderDark p-4 rounded-xl flex flex-col sm:flex-row gap-4 items-center justify-between">
        <div class="w-full sm:w-1/2">
            <label class="block text-xs text-gray-400 mb-1">Tanggal</label>
            <input type="date" name="date" value="{{ $date }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="w-full bg-darkBg border border-borderDark rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary">
        </div>
        <div class="w-full sm:w-1/2">
            <label class="block text-xs text-gray-400 mb-1">Waktu</label>
            <select name="time" class="w-full bg-darkBg border border-borderDark rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary">
                <option value="16:00" {{ $time == '16:00' ? 'selected' : '' }}>16:00 WIB</option>
                <option value="17:00" {{ $time == '17:00' ? 'selected' : '' }}>17:00 WIB</option>
                <option value="18:30" {{ $time == '18:30' ? 'selected' : '' }}>18:30 WIB</option>
                <option value="19:30" {{ $time == '19:30' ? 'selected' : '' }}>19:30 WIB</option>
            </select>
        </div>
        <div class="w-full sm:w-auto sm:self-end">
            <button type="submit" class="w-full bg-primary hover:bg-orange-600 text-white font-bold px-4 py-2 rounded-lg text-sm transition">
                Cek Ketersediaan
            </button>
        </div>
    </form>

    <!-- Keterangan Status / Legend -->
    <div class="flex justify-center space-x-6 mb-8">
        <div class="flex items-center"><div class="w-4 h-4 bg-green-500 rounded mr-2"></div><span class="text-sm text-gray-300">Tersedia</span></div>
        <div class="flex items-center"><div class="w-4 h-4 bg-red-500 rounded mr-2"></div><span class="text-sm text-gray-300">Terisi / Dibooking</span></div>
    </div>

    <!-- Visualisation Denah Meja -->
    <div class="bg-cardBg border border-borderDark rounded-xl p-8 max-w-4xl mx-auto">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @if(count($tables) > 0)
                @foreach($tables as $table)
                    @php
                        $isBooked = in_array($table->id,$reservedTableIds);
                        $bgColor =$isBooked ? 'bg-red-500 opacity-60 cursor-not-allowed' : 'bg-green-500 hover:bg-green-600 cursor-pointer';
                        $targetUrl = $isBooked ? '#' : route('reservation', ['table_id' => $table->id, 'date' => $date, 'time' =>$time]);
                    @endphp
                    <div class="relative group">
                        <a href="{{ $targetUrl }}" class="block {{ $bgColor }} rounded-xl p-6 text-center transition shadow-lg border-b-4 border-black border-opacity-20">
                            <span class="block text-2xl font-bold text-white mb-1">{{ $table->table_number }}</span>
                            <span class="text-xs text-white opacity-90">Kapasitas: {{ $table->capacity }} Orang</span>
                        </a>
                        <div class="absolute inset-x-0 bottom-full mb-2 hidden group-hover:block text-center z-10">
                            <div class="bg-black text-white text-xs py-1 px-2 rounded shadow-lg">
                                {{ $isBooked ? 'Sudah dibooking pada jam ini' : 'Klik untuk pesan meja ini' }}
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-span-4 text-center text-gray-400 py-8">
                    Belum ada data meja di database. Silakan isi tabel `tables` terlebih dahulu.
                </div>
            @endif
        </div>
        
        <div class="mt-8 p-4 bg-darkBg rounded-lg border border-borderDark text-center">
            <p class="text-gray-400 text-sm">Area Kasir & Dapur Utama berada di sisi utara ruangan.</p>
        </div>
    </div>
</div>
@endsection