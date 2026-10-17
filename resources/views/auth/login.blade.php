<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Login - Pojok Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Memuat FontAwesome untuk Ikon di dalam input -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-black font-sans antialiased">
    <!-- Background Image dengan Overlay Gelap -->
    <div class="min-h-screen bg-cover bg-center relative" style="background-image: url('{{ asset('images/banner-cafe.jpg') }}');">
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>

        <!-- Container Utama -->
        <div class="relative z-10 min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
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

                <!-- Sisi Kanan: Form Login -->
                <div class="w-full max-w-md mx-auto">
                    <h2 class="text-3xl font-bold text-center text-white mb-8">Member Login</h2>

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

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Input Email -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <i class="fa-regular fa-envelope text-gray-300"></i>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email" 
                                   class="w-full bg-transparent border border-gray-400 text-white rounded-full py-3 pl-12 pr-4 focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition placeholder-gray-400">
                        </div>

                        <!-- Input Password -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <i class="fa-solid fa-lock text-gray-300"></i>
                            </div>
                            <input type="password" name="password" required placeholder="Enter your password" 
                                   class="w-full bg-transparent border border-gray-400 text-white rounded-full py-3 pl-12 pr-4 focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition placeholder-gray-400">
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between text-sm mt-4">
                            <label class="flex items-center text-gray-300 cursor-pointer">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-transparent border-gray-400 text-[#6B442A] focus:ring-[#6B442A] mr-2">
                                Remember me
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-gray-300 hover:text-white transition">Forgot Password?</a>
                            @endif
                        </div>

                        <!-- Tombol Login -->
                        <div class="pt-2">
                            <button type="submit" class="w-full bg-gradient-to-b from-[#6B442A] to-[#3E2723] hover:from-[#5c3a24] hover:to-[#2e1d1a] text-white font-bold rounded-full py-3 transition shadow-lg">
                                Login
                            </button>
                        </div>

                        <!-- Teks Pindah ke Sign Up -->
                        <div class="text-center mt-6">
                            <p class="text-gray-300 text-sm">
                                Belum punya akun? <a href="{{ route('register') }}" class="text-white font-bold hover:underline">Buat akun dan jadilah keluarga kami</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>