@extends('layouts.customer')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="bg-cardBg border border-borderDark rounded-xl p-8 shadow-xl">
        <h2 class="text-3xl font-bold mb-2 text-primary">Reservasi Meja</h2>
        <p class="text-gray-400 mb-8">Pastikan tempat duduk Anda untuk berbuka puasa.</p>

        @if ($errors->any())
            <div class="bg-red-500 bg-opacity-20 border border-red-500 text-red-500 p-4 rounded-lg mb-6">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('reservation.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tanggal & Waktu -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tanggal Reservasi</label>
                    <input type="date" name="date" value="{{ old('date', $date) }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required
                           class="w-full bg-darkBg border border-borderDark rounded-lg px-4 py-3 focus:outline-none focus:border-primary text-white">
                    <p class="text-xs text-gray-500 mt-1">Minimal reservasi H+1</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Waktu Kedatangan</label>
                    <select name="time" required class="w-full bg-darkBg border border-borderDark rounded-lg px-4 py-3 focus:outline-none focus:border-primary text-white">
                        <option value="16:00" {{ old('time', $time) == '16:00' ? 'selected' : '' }}>16:00 (Persiapan Buka)</option>
                        <option value="17:00" {{ old('time', $time) == '17:00' ? 'selected' : '' }}>17:00 (Mendekati Maghrib)</option>
                        <option value="18:30" {{ old('time', $time) == '18:30' ? 'selected' : '' }}>18:30 (Setelah Maghrib)</option>
                        <option value="19:30" {{ old('time', $time) == '19:30' ? 'selected' : '' }}>19:30 (Tarawih/Malam)</option>
                    </select>
                </div>

                <!-- Jumlah Orang & Pilihan Meja dari DB -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Jumlah Orang</label>
                    <input type="number" name="pax" min="1" max="10" value="{{ old('pax', 2) }}" required
                           class="w-full bg-darkBg border border-borderDark rounded-lg px-4 py-3 focus:outline-none focus:border-primary text-white">
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-medium text-gray-300">Pilih Meja</label>
                        <a href="{{ route('table.layout') }}" class="text-xs text-primary hover:underline">Lihat Denah Visual</a>
                    </div>
                    <select name="table_id" required class="w-full bg-darkBg border border-borderDark rounded-lg px-4 py-3 focus:outline-none focus:border-primary text-white">
                        <option value="">Pilih Meja...</option>
                        @foreach($tables as $table)
                            @php
                                $isBooked = in_array($table->id, $reservedTableIds);
                                $isSelected = old('table_id', $selectedTableId) == $table->id;
                            @endphp
                            <option value="{{ $table->id }}" 
                                    {{ $isBooked ? 'disabled' : '' }} 
                                    {{ $isSelected && !$isBooked ? 'selected' : '' }}>
                                {{ $table->table_number }} (Kapasitas: {{ $table->capacity }} Orang) 
                                {{ $isBooked ? '- [SUDAH DIBOOKING]' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Catatan Khusus -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Catatan Khusus (Opsional)</label>
                <textarea name="notes" rows="3" placeholder="Misal: Sediakan kursi bayi"
                          class="w-full bg-darkBg border border-borderDark rounded-lg px-4 py-3 focus:outline-none focus:border-primary text-white">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="w-full bg-primary hover:bg-orange-600 text-white font-bold py-3 px-4 rounded-lg transition text-lg mt-4">
                Konfirmasi Reservasi
            </button>
        </form>
    </div>
</div>
@endsection