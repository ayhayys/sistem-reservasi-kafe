<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman form registrasi
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Memproses data registrasi
    public function register(Request $request)
    {
        // Validasi sesuai FR-AUTH-002
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'email.unique' => 'Email sudah terdaftar.'
        ]);

        // Simpan user ke database dengan role otomatis 'customer'
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        // Langsung login-kan user setelah berhasil mendaftar
        Auth::login($user);

        // Arahkan kembali ke beranda dengan pesan sukses
        return redirect()->route('home')->with('success', 'Akun berhasil dibuat! Selamat datang di KafeKu.');
    }
}