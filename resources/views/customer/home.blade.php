@extends('layouts.customer')

@section('content')
<!-- ========================================== -->
<!-- BAGIAN HERO DENGAN BACKGROUND GAMBAR -->
<!-- ========================================== -->
<!-- Ubah nama 'hero-bg.jpg' di bawah ini dengan nama file gambar yang kamu taruh di folder public/images/ -->
<div class="relative w-full h-[400px] md:h-[500px] bg-cover bg-center" 
     style="background-image: url('{{ asset('images/banner-cafe.jpg') }}');">
    
    <!-- Overlay gelap agar teks putih tetap terbaca jelas -->
    <div class="absolute inset-0 bg-black bg-opacity-60"></div>

    <!-- Konten Hero -->
    <div class="relative z-10 flex flex-col items-center justify-center h-full text-center px-4">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Welcome to Pojok Cafe</h1>
        <p class="text-gray-300 text-sm md:text-base max-w-2xl mb-8">
            Pesan dan reservasi meja untuk pengalaman berbuka puasa terbaik anda, dan jadilah bagian dari keluarga kami.
        </p>
        <a href="{{ route('menu') }}" class="bg-primary hover:bg-orange-600 text-white font-bold py-3 px-8 rounded-lg transition shadow-lg">
            Lihat Menu
        </a>
    </div>
</div>

<!-- ========================================== -->
<!-- BAGIAN MENU POPULER -->
<!-- ========================================== -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h2 class="text-2xl font-bold text-white border-l-4 border-primary pl-4 mb-8">Menu Populer</h2>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($popularMenus as $menu)
        <div class="bg-cardBg rounded-xl overflow-hidden shadow-lg border border-borderDark flex flex-col">
            <img src="{{ $menu->image ? asset($menu->image) : 'https://via.placeholder.com/300x200?text=No+Image' }}" alt="{{ $menu->name }}" class="w-full h-48 object-cover">
            
            <div class="p-5 flex-1 flex flex-col">
                <span class="bg-darkBg text-primary text-xs font-bold px-2 py-1 rounded w-max">{{ $menu->category->name ?? 'Uncategorized' }}</span>
                <h3 class="text-xl font-semibold mt-3 text-white">{{ $menu->name }}</h3>
                <p class="text-sm text-gray-400 mt-1 line-clamp-2">{{ $menu->description }}</p>
                
                <div class="mt-auto pt-4 flex justify-between items-center">
                    <span class="text-lg font-bold text-primary">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                    <!-- Tombol Add to Cart -->
                    <button @click="addToCart({ id: {{ $menu->id }}, name: '{{ $menu->name }}', price: {{ $menu->price }} })" 
                            class="bg-primary hover:bg-orange-600 text-white p-2 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection