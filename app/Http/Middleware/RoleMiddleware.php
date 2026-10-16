<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Cek apakah user sudah login. Jika belum, arahkan ke halaman login.
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Cek apakah role user saat ini cocok dengan role yang diminta di routing.
        if (Auth::user()->role !== $role) {
            // Jika tidak cocok (misal: pelanggan mencoba akses dashboard kasir), tolak aksesnya
            abort(403, 'Akses tidak diizinkan. Halaman ini hanya untuk hak akses: ' . $role);
        }

        // 3. Jika aman, lanjutkan request ke halaman yang dituju
        return $next($request);
    }
}