<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Table;
use App\Models\Reservation;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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
        // Mengambil daftar reservasi user beserta relasi meja & pesanan pre-order yang terhubung
        $reservations = Reservation::with(['table', 'orders'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Mengambil daftar pesanan/pre-order user beserta detail item menu & reservasi
        $orders = Order::with(['orderItems.menu', 'reservation.table'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.history', compact('reservations', 'orders'));
    }

    public function checkout(Request $request)
    {
        // 1. Validasi Input Form
        $request->validate([
            'cart_data' => 'required',
            'serve_date' => 'required|date|after_or_equal:today',
            'payment_method' => 'required|in:cash,transfer',
        ]);

        // 2. Decode data JSON keranjang dari Javascript
        $cartData = json_decode($request->input('cart_data'), true);

        if (!$cartData || count($cartData) === 0) {
            return back()->withErrors(['error' => 'Keranjang pesanan Anda kosong.']);
        }

        DB::beginTransaction();
        try {
            // Hitung Subtotal
            $subtotal = collect($cartData)->sum(function($item) {
                return $item['price'] * $item['qty'];
            });

            // Cari reservasi aktif hari ini
            $activeReservation = Reservation::where('user_id', auth()->id())
                ->where('date', '>=', date('Y-m-d'))
                ->whereIn('status', ['pending', 'confirmed'])
                ->first();

            // 3. Simpan Tabel Orders
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(4));
            
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => auth()->id(),
                'reservation_id' => $activeReservation ? $activeReservation->id : null,
                'serve_date' => $request->serve_date,
                'subtotal' => $subtotal,
                'tax' => 0,
                'total' => $subtotal,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                // PERBAIKAN: Menggunakan 'unpaid' agar tidak ditolak oleh ENUM database
                'payment_status' => 'unpaid',
            ]);

            // 4. Simpan Tabel Order Items (Detail Keranjang)
            foreach ($cartData as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                ]);
            }

            DB::commit();

            // 5. Redirect ke riwayat dan bersihkan cache localStorage keranjang
            return redirect()->route('history')
                ->with('clear_cart', true) 
                ->with('success', 'Pesanan sukses! Nomor Pesanan Anda: ' . $orderNumber);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Sistem gagal menyimpan pesanan: ' . $e->getMessage()]);
        }
    }
}