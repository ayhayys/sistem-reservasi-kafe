<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('Customer Dashboard', 'Pojok Cafe') }}</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
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
<body class="bg-darkBg text-white font-sans antialiased">

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
                    <button type="button" onclick="openCart()" class="relative text-white hover:text-primary transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span id="cart_badge" class="absolute -top-2 -right-2 bg-primary text-white text-[10px] font-bold rounded-full h-4 w-4 flex items-center justify-center hidden"></span>
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

    <!-- ========================================== -->
    <!-- SIDEBAR KERANJANG PESANAN -->
    <!-- ========================================== -->
    <div id="cartOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden transition-opacity" onclick="closeCart()"></div>

    <div id="cartSidebar" class="fixed top-0 right-0 h-full w-full sm:w-96 bg-cardBg shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
        <div class="px-4 py-4 border-b border-borderDark flex justify-between items-center bg-darkBg">
            <h3 class="text-white font-bold text-lg flex items-center">
                <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Keranjang Pesanan
            </h3>
            <button class="text-gray-400 hover:text-red-500 transition" onclick="closeCart()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div id="cart_items_list" class="flex-1 overflow-y-auto p-4 space-y-4">
            <!-- Item keranjang masuk ke sini -->
        </div>

        <form action="{{ route('checkout') }}" method="POST" id="checkoutForm" class="bg-cardBg border-t border-borderDark">
            @csrf
            <input type="hidden" name="cart_data" id="cart_data_input">
            
            <div class="p-4">
                <div class="mb-4">
                    <label class="block text-sm text-gray-300 mb-2">Tanggal Dihidangkan</label>
                    <input type="date" name="serve_date" required min="{{ date('Y-m-d') }}" 
                           class="w-full bg-darkBg border border-borderDark rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary">
                </div>

                <div class="mb-4">
                    <label class="block text-sm text-gray-300 mb-2">Metode Pembayaran</label>
                    <select name="payment_method" required 
                            class="w-full bg-darkBg border border-borderDark rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary appearance-none">
                        <option value="cash">Bayar di Tempat (Kasir)</option>
                        <option value="transfer">Bayar Sekarang (Transfer)</option>
                    </select>
                </div>

                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-400 font-medium">Total Pembayaran:</span>
                    <span class="text-white font-bold text-xl" id="cart_total_display">Rp 0</span>
                </div>
                
                <button type="submit" class="w-full bg-primary hover:bg-orange-600 text-white font-bold py-3 rounded-lg transition shadow-lg flex justify-center items-center">
                    Checkout Sekarang
                </button>
            </div>
        </form>
    </div>

    <!-- ========================================== -->
    <!-- SCRIPT LOGIKA KERANJANG -->
    <!-- ========================================== -->
    <script>
        let cart = JSON.parse(localStorage.getItem('kafe_cart')) || [];

        // Menghapus data keranjang lokal jika checkout berhasil
        @if(session('clear_cart'))
            localStorage.removeItem('kafe_cart');
            cart = [];
        @endif

        function openCart() {
            document.getElementById('cartSidebar').classList.remove('translate-x-full');
            document.getElementById('cartOverlay').classList.remove('hidden');
            renderCart(); 
        }

        function closeCart() {
            document.getElementById('cartSidebar').classList.add('translate-x-full');
            document.getElementById('cartOverlay').classList.add('hidden');
        }

        function addToCart(menu) {
            let existingItem = cart.find(item => item.id === menu.id);
            if (existingItem) {
                existingItem.qty += 1;
            } else {
                cart.push({ id: menu.id, name: menu.name, price: menu.price, qty: 1 });
            }
            saveCart();
            openCart();
        }

        function updateQty(id, change) {
            let item = cart.find(item => item.id === id);
            if (item) {
                item.qty += change;
                if (item.qty <= 0) {
                    cart = cart.filter(i => i.id !== id);
                }
                saveCart();
            }
        }

        function saveCart() {
            localStorage.setItem('kafe_cart', JSON.stringify(cart));
            renderCart();
        }

        function renderCart() {
            let cartList = document.getElementById('cart_items_list');
            let totalDisplay = document.getElementById('cart_total_display');
            let cartDataInput = document.getElementById('cart_data_input');
            let cartBadge = document.getElementById('cart_badge');
            
            if(!cartList) return;

            cartList.innerHTML = '';
            let total = 0;
            let totalItemsCount = 0;

            if (cart.length === 0) {
                cartList.innerHTML = '<div class="text-center text-gray-500 mt-10"><svg class="w-12 h-12 mx-auto mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg><p>Keranjang masih kosong</p></div>';
                if(cartBadge) cartBadge.classList.add('hidden');
            } else {
                cart.forEach(item => {
                    total += item.price * item.qty;
                    totalItemsCount += item.qty;
                    
                    cartList.innerHTML += `
                        <div class="flex justify-between items-center bg-darkBg p-3 rounded-lg border border-borderDark">
                            <div class="flex-1">
                                <h4 class="text-white text-sm font-bold truncate max-w-[150px]">${item.name}</h4>
                                <p class="text-primary text-xs font-semibold">Rp ${item.price.toLocaleString('id-ID')}</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <button type="button" onclick="updateQty(${item.id}, -1)" class="bg-cardBg hover:bg-gray-600 text-white w-6 h-6 rounded flex items-center justify-center transition">-</button>
                                <span class="text-white text-sm font-bold w-4 text-center">${item.qty}</span>
                                <button type="button" onclick="updateQty(${item.id}, 1)" class="bg-cardBg hover:bg-gray-600 text-white w-6 h-6 rounded flex items-center justify-center transition">+</button>
                            </div>
                        </div>
                    `;
                });
                
                if(cartBadge) {
                    cartBadge.innerText = totalItemsCount;
                    cartBadge.classList.remove('hidden');
                }
            }

            totalDisplay.innerText = 'Rp ' + total.toLocaleString('id-ID');
            cartDataInput.value = JSON.stringify(cart);
        }

        document.getElementById('checkoutForm')?.addEventListener('submit', function(e) {
            if(cart.length === 0) {
                e.preventDefault();
                alert('Keranjang belanja Anda masih kosong!');
            }
        });

        document.addEventListener('DOMContentLoaded', renderCart);
    </script>
</body>
</html>