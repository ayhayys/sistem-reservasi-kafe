<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Reservation;

class CashierController extends Controller
{
    // ==========================================
    // PESANAN MASUK
    // ==========================================
    public function orders()
    {
        // Menampilkan pesanan terbaru, diurutkan dari yang belum dibayar terlebih dahulu
        $orders = Order::with(['user', 'orderItems.menu', 'reservation'])
            ->orderByRaw("FIELD(payment_status, 'unpaid', 'pending', 'paid')")
            ->latest()
            ->get();
            
        return view('cashier.orders', compact('orders'));
    }

    public function confirmPayment($id)
    {
        $order = Order::findOrFail($id);
        
        // PERBAIKAN: Hanya memperbarui status pembayaran. 
        // Baris update status 'confirmed' dihapus agar tidak bentrok dengan ENUM database.
        $order->update([
            'payment_status' => 'paid'
        ]);

        return back()->with('success', 'Pembayaran pesanan ' . $order->order_number . ' berhasil dikonfirmasi.');
    }

    // ==========================================
    // DAFTAR RESERVASI
    // ==========================================
    public function reservations()
    {
        $reservations = Reservation::with(['user', 'table'])
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->get();
            
        return view('cashier.reservations', compact('reservations'));
    }
}