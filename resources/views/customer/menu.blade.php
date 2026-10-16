@extends('layouts.customer')

@section('content')
<!-- Alpine.js x-data menyimpan state pencarian dan kategori yang dipilih -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ search: '', categorySelected: 'All' }">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <h2 class="text-3xl font-bold text-white border-l-4 border-primary pl-4">Daftar Menu</h2>
        
        <!-- Filter & Search -->
        <div class="flex space-x-4 w-full md:w-auto">
            <input type="text" x-model="search" placeholder="Cari menu..." 
                   class="bg-darkBg border border-borderDark rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary w-full md:w-64">
            
            <select x-model="categorySelected" class="bg-darkBg border border-borderDark rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary">
                <option value="All">Semua Kategori</option>
                <!-- Looping dinamis data kategori dari database -->
                @foreach($categories as $cat)
                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Grid Menu -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($menus as $menu)
        <!-- Alpine logic: Sembunyikan card jika kategori tidak cocok atau nama tidak sesuai pencarian -->
        <div class="bg-cardBg rounded-xl overflow-hidden shadow-lg border border-borderDark flex flex-col"
             x-show="(categorySelected === 'All' || categorySelected === '{{ $menu->category->name ?? '' }}') && '{{ strtolower($menu->name) }}'.includes(search.toLowerCase())">
            
            <img src="{{ $menu->image ?? 'https://via.placeholder.com/300x200?text=No+Image' }}" alt="{{ $menu->name }}" class="w-full h-48 object-cover">
            
            <div class="p-5 flex-1 flex flex-col">
                <span class="bg-darkBg text-primary text-xs font-bold px-2 py-1 rounded w-max">{{ $menu->category->name ?? 'Uncategorized' }}</span>
                <h3 class="text-xl font-semibold mt-3 text-white">{{ $menu->name }}</h3>
                <p class="text-sm text-gray-400 mt-1 line-clamp-2">{{ $menu->description }}</p>
                
                <div class="mt-auto pt-4 flex justify-between items-center">
                    <span class="text-lg font-bold text-primary">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                    <!-- Trigger ke fungsi cartSystem() di layouts/customer.blade.php -->
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