@extends('layouts.customer')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-cardBg border border-borderDark rounded-xl p-8 shadow-xl">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-white mb-2">Daftar Akun Baru</h2>
            <p class="text-gray-400 text-sm">Buat akun untuk mulai reservasi dan pre-order menu dan jadilah bagian dari keluarga kami.</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-500 bg-opacity-20 border border-red-500 text-red-500 p-4 rounded-lg mb-6 text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf
            
            <!-- Nama Lengkap -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                       class="w-full bg-darkBg border border-borderDark rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary text-white">
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full bg-darkBg border border-borderDark rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary text-white">
            </div>

            <!-- Nomor Telepon -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Nomor Telepon (WhatsApp)</label>
                <input type="text" name="phone" value="{{ old('phone') }}" required
                       class="w-full bg-darkBg border border-borderDark rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary text-white"
                       placeholder="0812xxxxxx">
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Password</label>
                <input type="password" name="password" required minlength="8"
                       class="w-full bg-darkBg border border-borderDark rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary text-white"
                       placeholder="Minimal 8 karakter">
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required minlength="8"
                       class="w-full bg-darkBg border border-borderDark rounded-lg px-4 py-2.5 focus:outline-none focus:border-primary text-white">
            </div>

            <button type="submit" class="w-full bg-primary hover:bg-orange-600 text-white font-bold py-3 rounded-lg transition mt-4">
                Daftar Sekarang
            </button>
        </form>

        <p class="text-center text-gray-400 text-sm mt-6">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="text-primary hover:underline font-semibold">Login di sini</a>
        </p>
    </div>
</div>
@endsection