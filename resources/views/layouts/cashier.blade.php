<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Dashboard - Pojok Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
<body class="bg-darkBg text-white font-sans antialiased min-h-screen flex flex-col">

    <!-- Navbar Atas -->
    <nav class="bg-cardBg border-b border-borderDark sticky top-0 z-50 shadow-lg" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Kiri: Logo -->
                <div class="flex items-center">
                    <a href="{{ route('cashier.dashboard') }}" class="flex-shrink-0 flex items-center">
                        <span class="text-2xl font-bold text-primary tracking-wide">Pojok Cafe <span class="text-white text-lg font-normal">Cashier</span></span>
                    </a>
                </div>

                <!-- Kanan: Menu Desktop & Tombol Logout -->
                <div class="hidden md:flex items-center space-x-6">
                    <div class="flex space-x-2">
                        <a href="{{ route('cashier.orders') }}" 
                           class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('cashier.orders') ? 'bg-primary text-white shadow' : 'text-gray-300 hover:bg-darkBg hover:text-white' }}">
                            Pesanan Masuk
                        </a>
                        <a href="{{ route('cashier.reservations') }}" 
                           class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('cashier.reservations') ? 'bg-primary text-white shadow' : 'text-gray-300 hover:bg-darkBg hover:text-white' }}">
                            Daftar Reservasi
                        </a>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="border border-red-500/50 text-red-400 hover:bg-red-500 hover:text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                            Logout
                        </button>
                    </form>
                </div>
                
                <!-- Hamburger Mobile -->
                <div class="-mr-2 flex items-center md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-darkBg focus:outline-none transition">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Menu Mobile Dropdown -->
        <div x-show="mobileMenuOpen" class="md:hidden bg-cardBg border-t border-borderDark transition" style="display: none;">
            <div class="px-4 pt-2 pb-4 space-y-2 sm:px-6">
                <a href="{{ route('cashier.orders') }}" class="block px-3 py-3 rounded-md text-base font-medium {{ request()->routeIs('cashier.orders') ? 'bg-primary text-white' : 'text-gray-300 hover:bg-darkBg hover:text-white' }}">Pesanan Masuk</a>
                <a href="{{ route('cashier.reservations') }}" class="block px-3 py-3 rounded-md text-base font-medium {{ request()->routeIs('cashier.reservations') ? 'bg-primary text-white' : 'text-gray-300 hover:bg-darkBg hover:text-white' }}">Daftar Reservasi</a>
                <form method="POST" action="{{ route('logout') }}" class="pt-4 border-t border-borderDark">
                    @csrf
                    <button type="submit" class="block w-full text-left px-3 py-3 rounded-md text-base font-bold text-red-400 hover:bg-red-500 hover:text-white transition">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="bg-green-500 bg-opacity-20 border border-green-500 text-green-500 px-4 py-3 rounded-lg mb-6 flex items-center shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif
        
        @yield('content')
    </main>

</body>
</html>