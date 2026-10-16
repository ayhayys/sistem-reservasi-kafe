<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Kafe Ramadhan') }}</title>
    <!-- Tailwind CSS (Gunakan Vite di production, ini CDN untuk testing cepat) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js untuk interaktivitas UI & Cart -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#D97D54',
                        darkBg: '#2D2D2D',
                        cardBg: '#3D3D3D',
                        borderDark: '#4D4D4D'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-darkBg text-white font-sans antialiased" x-data="cartSystem()">

    <!-- Navbar -->
    <nav class="bg-cardBg border-b border-borderDark sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo & Menu Kiri -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-primary">Pojok Cafe</a>
                    <div class="hidden md:ml-10 md:flex md:space-x-8">
                        <a href="{{ route('home') }}" class="hover:text-primary transition">Beranda</a>
                        <a href="{{ route('menu') }}" class="hover:text-primary transition">Daftar Menu</a>
                        <a href="{{ route('reservation') }}" class="hover:text-primary transition">Reservasi Meja</a>
                        <a href="{{ route('table.layout') }}" class="hover:text-primary transition">Denah Meja</a>
                    </div>
                </div>

                <!-- Menu Kanan (Auth & Cart) -->
                <div class="flex items-center space-x-6">
                    @auth
                        <a href="{{ route('history') }}" class="hover:text-primary">Riwayat</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-gray-300 hover:text-white">Logout</button>
                        </form>
                    @else
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('login') }}" class="text-sm text-gray-300 hover:text-primary transition">Login</a>
                            <a href="{{ route('register') }}" class="text-sm bg-primary hover:bg-orange-600 text-white px-4 py-2 rounded-lg transition font-semibold">Daftar</a>
                        </div>
                    @endauth

                    <!-- Cart Trigger -->
                    <button @click="cartOpen = true" class="relative text-white hover:text-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span x-show="cartTotalItems > 0" x-text="cartTotalItems" class="absolute -top-2 -right-2 bg-primary text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center"></span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-cardBg border-t border-borderDark py-8 text-center text-sm text-gray-400">
        &copy; 2026 Pojok Cafe. Deserves better.
    </footer>

    <!-- Sidebar Keranjang (Cart) -->
    <div x-show="cartOpen" class="fixed inset-0 z-50 overflow-hidden" style="display: none;">
        <div x-show="cartOpen" @click="cartOpen = false" class="absolute inset-0 bg-black bg-opacity-50 transition-opacity"></div>
        <div class="absolute inset-y-0 right-0 max-w-md w-full flex">
            <div class="h-full flex flex-col bg-cardBg shadow-xl w-full">
                <div class="px-4 py-6 border-b border-borderDark flex justify-between items-center">
                    <h2 class="text-lg font-bold text-white">Keranjang Pesanan</h2>
                    <button @click="cartOpen = false" class="text-gray-400 hover:text-white">&times; Tutup</button>
                </div>
                
                <!-- Cart Items -->
                <div class="flex-1 overflow-y-auto px-4 py-6 space-y-4">
                    <template x-if="cart.length === 0">
                        <p class="text-gray-400 text-center mt-10">Keranjang masih kosong.</p>
                    </template>
                    <template x-for="item in cart" :key="item.id">
                        <div class="flex justify-between items-center border-b border-borderDark pb-4">
                            <div>
                                <h3 class="text-white font-semibold" x-text="item.name"></h3>
                                <p class="text-primary text-sm">Rp <span x-text="item.price.toLocaleString('id-ID')"></span></p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <button @click="updateQty(item.id, -1)" class="bg-borderDark px-2 py-1 rounded text-white">-</button>
                                <span x-text="item.qty"></span>
                                <button @click="updateQty(item.id, 1)" class="bg-borderDark px-2 py-1 rounded text-white">+</button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Checkout Section -->
                <div class="border-t border-borderDark px-4 py-6">
                    <div class="flex justify-between text-lg font-bold mb-4">
                        <span>Total:</span>
                        <span>Rp <span x-text="cartTotal.toLocaleString('id-ID')"></span></span>
                    </div>
                    @auth
                        <form method="POST" action="{{ route('checkout') }}">
                            @csrf
                            <input type="hidden" name="cart_data" :value="JSON.stringify(cart)">
                            <button type="submit" class="w-full bg-primary hover:bg-orange-600 text-white font-bold py-3 rounded-lg transition">Checkout Sekarang</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block text-center w-full bg-borderDark hover:bg-gray-600 text-white font-bold py-3 rounded-lg transition">Login untuk Checkout</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js Logic -->
    <script>
        function cartSystem() {
            return {
                cartOpen: false,
                cart: JSON.parse(localStorage.getItem('kafe_cart') || '[]'),
                addToCart(menu) {
                    let existing = this.cart.find(i => i.id === menu.id);
                    if (existing) {
                        existing.qty++;
                    } else {
                        this.cart.push({ ...menu, qty: 1 });
                    }
                    this.saveCart();
                    this.cartOpen = true;
                },
                updateQty(id, change) {
                    let item = this.cart.find(i => i.id === id);
                    if (item) {
                        item.qty += change;
                        if (item.qty <= 0) {
                            this.cart = this.cart.filter(i => i.id !== id);
                        }
                    }
                    this.saveCart();
                },
                saveCart() {
                    localStorage.setItem('kafe_cart', JSON.stringify(this.cart));
                },
                get cartTotalItems() {
                    return this.cart.reduce((total, item) => total + item.qty, 0);
                },
                get cartTotal() {
                    return this.cart.reduce((total, item) => total + (item.price * item.qty), 0);
                }
            }
        }
    </script>
</body>
</html>