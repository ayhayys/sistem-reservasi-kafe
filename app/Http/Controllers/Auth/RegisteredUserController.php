<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Menampilkan halaman registrasi.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Menangani proses pendaftaran user baru.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi Input (termasuk nomor telepon dan minimal 8 karakter password)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:20'], // Validasi nomor telepon
            'password' => ['required', 'confirmed', 'min:8'], // Minimal 8 karakter
        ]);

        // 2. Simpan Data ke Database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone, // Menyimpan input nomor telepon
            'password' => Hash::make($request->password),
            'role' => 'customer', // Pastikan role default adalah customer
        ]);

        event(new Registered($user));

        // 3. Login otomatis setelah berhasil daftar
        Auth::login($user);

        // 4. Redirect langsung ke halaman home customer
        return redirect()->route('home');
    }
}