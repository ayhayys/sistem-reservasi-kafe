<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
   public function store(\App\Http\Requests\Auth\LoginRequest $request): \Illuminate\Http\RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Ambil role dari user yang baru saja login
        $role = $request->user()->role;

        // Arahkan ke dashboard sesuai dengan role masing-masing
        if ($role === 'cashier') {
            return redirect()->route('cashier.dashboard');
        } elseif ($role === 'owner') {
            return redirect()->route('owner.dashboard');
        }

        // Default redirect untuk Customer (Pelanggan)
        return redirect()->route('home');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
