<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Pastikan semua model di bawah ini ter-import agar tidak terjadi error "Class not found"
use App\Models\Menu;
use App\Models\Category;
use App\Models\Table;
use App\Models\Reservation;
use App\Models\Order;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index()
    {
        $popularMenus = Menu::with('category')->where('is_active', true)->take(4)->get();
        return view('customer.home', compact('popularMenus'));
    }

    public function menu(Request $request)
    {
        $categories = Category::all();
        $menus = Menu::with('category')->where('is_active', true)->get();
        return view('customer.menu', compact('menus', 'categories'));
    }

    public function reservation(Request $request)
    {
        $date = $request->get('date', date('Y-m-d', strtotime('+1 day')));
        $time = $request->get('time', '17:00');
        $selectedTableId = $request->get('table_id');

        // Mengambil ID meja yang sudah di-booking
        $reservedTableIds = Reservation::where('date', $date)
            ->where('time', $time)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('table_id')
            ->toArray();

        $tables = Table::all();

        return view('customer.reservation', compact('tables', 'reservedTableIds', 'selectedTableId', 'date', 'time'));
    }

    public function storeReservation(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:tomorrow',
            'time' => 'required',
            'pax' => 'required|integer|min:1|max:10',
            'table_id' => 'required|exists:tables,id',
        ]);

        $isReserved = Reservation::where('table_id', $request->table_id)
            ->where('date', $request->date)
            ->where('time', $request->time)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($isReserved) {
            return back()->withErrors(['table_id' => 'Meja ini sudah dibooking pada tanggal dan jam tersebut. Silakan pilih meja lain.'])->withInput();
        }

        $bookingCode = 'RES-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        Reservation::create([
            'booking_code' => $bookingCode,
            'user_id' => auth()->id(),
            'table_id' => $request->table_id,
            'date' => $request->date,
            'time' => $request->time,
            'pax' => $request->pax,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        return redirect()->route('history')->with('success', 'Reservasi berhasil dibuat! Kode Booking Anda: ' . $bookingCode);
    }

    public function tableLayout(Request $request)
    {
        $date = $request->get('date', date('Y-m-d', strtotime('+1 day')));
        $time = $request->get('time', '17:00');

        $reservedTableIds = Reservation::where('date', $date)
            ->where('time', $time)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('table_id')
            ->toArray();

        $tables = Table::all();

        return view('customer.table-layout', compact('tables', 'reservedTableIds', 'date', 'time'));
    }

    public function history()
    {
        $reservations = Reservation::with('table')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        $orders = Order::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.history', compact('reservations', 'orders'));
    }

    public function checkout(Request $request)
    {
        return response()->json(['status' => 'success', 'message' => 'Pesanan berhasil dikonfirmasi']);
    }
}