@extends('layouts.customer')

@section('content')
<!-- Hero Section -->
<div class="relative bg-black overflow-hidden h-[400px] flex items-center justify-center text-center">
    <div class="absolute inset-0 opacity-40 bg-[url('https://images.unsplash.com/photo-1554118811-1e0d58224f24?q=80&w=1000')] bg-cover bg-center"></div>
    <div class="relative z-10 px-4">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Welcome to KafeKu</h1>
        <p class="text-lg md:text-xl text-gray-200 mb-8">Pesan dan reservasi meja untuk pengalaman berbuka puasa terbaik</p>
        <a href="{{ route('menu') }}" class="bg-primary hover:bg-orange-600 text-white font-bold py-3 px-8 rounded-lg transition">Lihat Menu</a>
    </div>
</div>

<!-- Popular Menu Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h2 class="text-3xl font-bold mb-8 border-l-4 border-primary pl-4">Menu Populer</h2>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($popularMenus as $menu)
        <div class="bg-cardBg rounded-xl overflow-hidden shadow-lg border border-borderDark">
            <!-- Gunakan fallback gambar jika image di database kosong -->
            <img src="{{ $menu->image ?? 'https://via.placeholder.com/300x200?text=No+Image' }}" alt="{{ $menu->name }}" class="w-full h-48 object-cover">
            <div class="p-5">
                <span class="bg-darkBg text-primary text-xs font-bold px-2 py-1 rounded">{{ $menu->category->name ?? 'Uncategorized' }}</span>
                <h3 class="text-xl font-semibold mt-3 mb-2 text-white">{{ $menu->name }}</h3>
                <div class="flex justify-between items-center mt-4">
                    <span class="text-lg font-bold text-primary">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                    <button @click="addToCart({ id: {{ $menu->id }}, name: '{{ $menu->name }}', price: {{ $menu->price }} })" 
                            class="bg-primary hover:bg-orange-600 text-white p-2 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection