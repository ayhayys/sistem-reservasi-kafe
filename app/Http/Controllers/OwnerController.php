<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Order;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class OwnerController extends Controller
{
    // ==========================================
    // MANAJEMEN MENU & KATEGORI
    // ==========================================
    public function menuIndex()
    {
        $categories = Category::withCount('menus')->get();
        $menus = Menu::with('category')->latest()->get();
        return view('owner.menu', compact('categories', 'menus'));
    }

    // --- Kategori ---
    public function categoryStore(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:categories,name']);
        Category::create(['name' => $request->name]);
        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function categoryUpdate(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255|unique:categories,name,' . $id]);
        $category = Category::findOrFail($id);
        $category->update(['name' => $request->name]);
        return back()->with('success', 'Kategori berhasil diperbarui!');
    }

    public function categoryDestroy($id)
    {
        $category = Category::findOrFail($id);
        // Hapus kategori (Menu yang terkait bisa diatur cascade atau set null di database)
        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus permanen!');
    }

    // --- Menu ---
    public function menuStore(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi file gambar
        ]);

        $data = $request->all();

        // Proses Upload Gambar
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menu', 'public');
            $data['image'] = '/storage/' . $imagePath;
        }

        Menu::create($data);
        return back()->with('success', 'Menu berhasil ditambahkan!');
    }

    public function menuUpdate(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $menu = Menu::findOrFail($id);
        $data = $request->except(['image']);

        // Proses Update Gambar
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($menu->image && str_starts_with($menu->image, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $menu->image);
                Storage::disk('public')->delete($oldPath);
            }
            $imagePath = $request->file('image')->store('menu', 'public');
            $data['image'] = '/storage/' . $imagePath;
        }

        $menu->update($data);
        return back()->with('success', 'Menu berhasil diperbarui!');
    }

    public function menuDestroy($id)
    {
        $menu = Menu::findOrFail($id);
        
        // Hapus file gambar fisik dari folder storage
        if ($menu->image && str_starts_with($menu->image, '/storage/')) {
            $oldPath = str_replace('/storage/', '', $menu->image);
            Storage::disk('public')->delete($oldPath);
        }

        // Hard Delete
        $menu->delete(); 
        return back()->with('success', 'Menu berhasil dihapus!');
    }

    // ==========================================
    // LAPORAN PEMESANAN
    // ==========================================
    public function reportOrders(Request $request)
    {
        // 1. Ambil nilai filter periode dari URL (default: 'today')
        $period = $request->get('period', 'today'); 
        
        $query = Order::where('payment_status', 'paid');
        
        // 2. Terapkan filter berdasarkan periode yang dipilih
        if ($period == 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($period == 'yesterday') {
            $query->whereDate('created_at', Carbon::yesterday());
        } elseif ($period == 'week') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($period == 'month') {
            $query->whereMonth('created_at', Carbon::now()->month);
        }

        $orders = $query->latest()->get();
        
        // 3. Hitung Stat Cards
        $totalRevenue = $orders->sum('total');
        $totalOrders = $orders->count();
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // 4. Siapkan data Chart (Pendapatan 7 hari terakhir)
        $chartData = Order::where('payment_status', 'paid')
            ->whereDate('created_at', '>=', Carbon::now()->subDays(7))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'))
            ->groupBy('date')
            ->get();

        // 5. Kirim semua variabel ke View, termasuk $period
        return view('owner.reports.orders', compact('orders', 'totalRevenue', 'totalOrders', 'avgOrderValue', 'chartData', 'period'));
    }

    // ==========================================
    // LAPORAN RESERVASI
    // ==========================================
    public function reportReservations(Request $request)
    {
        // 1. Ambil nilai filter periode dari URL (default: 'today')
        $period = $request->get('period', 'today');
        
        $query = Reservation::query();
        
        // 2. Terapkan filter berdasarkan periode yang dipilih
        if ($period == 'today') {
            $query->whereDate('date', Carbon::today());
        } elseif ($period == 'yesterday') {
            $query->whereDate('date', Carbon::yesterday());
        } elseif ($period == 'week') {
            $query->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($period == 'month') {
            $query->whereMonth('date', Carbon::now()->month);
        }

        $reservations = $query->with('table', 'user')->latest('date')->get();
        
        // 3. Hitung Stat Cards
        $totalReservations = $reservations->count();
        $totalPax = $reservations->sum('pax');
        $completedReservations = $reservations->where('status', 'completed')->count();
        $noShowRate = $totalReservations > 0 ? ($reservations->where('status', 'cancelled')->count() / $totalReservations) * 100 : 0;

        // 4. Kirim semua variabel ke View, termasuk $period
        return view('owner.reports.reservations', compact('reservations', 'totalReservations', 'totalPax', 'completedReservations', 'noShowRate', 'period'));
    }
}