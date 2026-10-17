<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Sign Up - Pojok Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Memuat FontAwesome untuk Ikon di dalam input -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-black font-sans antialiased">
    <!-- Background Image dengan Overlay Gelap (Sama seperti halaman Login) -->
    <div class="min-h-screen bg-cover bg-center relative" style="background-image: url('{{ asset('images/banner-cafe.jpg') }}');">
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>

        <!-- Container Utama -->
        <div class="relative z-10 min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-10">
            <div class="max-w-6xl w-full grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                
                <!-- Sisi Kiri: Teks dan Tombol Get Started -->
                <div class="hidden md:block text-white">
                    <h1 class="text-5xl lg:text-6xl font-extrabold mb-4 leading-tight">Pojok Cafe</h1>
                    <p class="text-gray-300 mb-8 max-w-md text-lg">
                        Di antara kabut pegunungan dan udara yang dingin menyegarkan, Pojok Cafe hadir sebagai tempat berhenti. Tempat kecil di sudut pegunungan, untuk jeda yang kamu cari untuk menikmati waktu berbukamu.
                    </p>
                    <p class="text-gray-300 mb-8 max-w-md text-lg">
                        - Pojok Cafe
                    </p>
                    <a href="#" class="inline-block border border-white text-white rounded-full px-8 py-2.5 font-semibold hover:bg-white hover:text-black transition duration-300">
                        Get Started
                    </a>
                </div>

                <!-- Sisi Kanan: Form Sign Up -->
                <div class="w-full max-w-md mx-auto">
                    <h2 class="text-3xl font-bold text-center text-white mb-6">Member Sign Up</h2>

                    <!-- Pesan Error Validasi -->
                    @if ($errors->any())
                        <div class="mb-4 bg-red-500 bg-opacity-20 border border-red-500 text-red-100 px-4 py-3 rounded-lg">
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf

                        <!-- Input Nama -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <i class="fa-regular fa-user text-gray-300"></i>
                            </div>
                            <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Enter your full name" 
                                   class="w-full bg-transparent border border-gray-400 text-white rounded-full py-3 pl-12 pr-4 focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition placeholder-gray-400">
                        </div>

                        <!-- Input Email -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <i class="fa-regular fa-envelope text-gray-300"></i>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="Enter your email" 
                                   class="w-full bg-transparent border border-gray-400 text-white rounded-full py-3 pl-12 pr-4 focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition placeholder-gray-400">
                        </div>

                        <!-- Input Nomor Telepon (BARU) -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <i class="fa-solid fa-phone text-gray-300"></i>
                            </div>
                            <!-- name="phone" disesuaikan dengan kolom di database kamu -->
                            <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="Enter your phone number" 
                                   class="w-full bg-transparent border border-gray-400 text-white rounded-full py-3 pl-12 pr-4 focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition placeholder-gray-400">
                        </div>

                        <!-- Input Password (minlength="8") -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <i class="fa-solid fa-lock text-gray-300"></i>
                            </div>
                            <input type="password" name="password" required minlength="8" placeholder="Create a password (min. 8 chars)" 
                                   class="w-full bg-transparent border border-gray-400 text-white rounded-full py-3 pl-12 pr-4 focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition placeholder-gray-400">
                        </div>

                        <!-- Konfirmasi Password (minlength="8") -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <i class="fa-solid fa-lock text-gray-300"></i>
                            </div>
                            <input type="password" name="password_confirmation" required minlength="8" placeholder="Confirm your password" 
                                   class="w-full bg-transparent border border-gray-400 text-white rounded-full py-3 pl-12 pr-4 focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition placeholder-gray-400">
                        </div>

                        <!-- Tombol Sign Up -->
                        <div class="pt-4">
                            <button type="submit" class="w-full bg-gradient-to-b from-[#6B442A] to-[#3E2723] hover:from-[#5c3a24] hover:to-[#2e1d1a] text-white font-bold rounded-full py-3 transition shadow-lg">
                                Sign Up
                            </button>
                        </div>

                        <!-- Teks Pindah ke Login -->
                        <div class="text-center mt-4">
                            <p class="text-gray-300 text-sm">
                                Sudah punya akun? <a href="{{ route('login') }}" class="text-white font-bold hover:underline">Masuk sekarang</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>