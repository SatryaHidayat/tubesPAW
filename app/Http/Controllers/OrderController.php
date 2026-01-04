<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Promo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // === 1. HALAMAN DAFTAR MENU (Dengan Filter Kategori) ===
    public function index(Request $request)
    {
        // Siapkan Query
        $query = Menu::query();

        // Cek apakah ada filter Kategori dari Sidebar?
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        // Ambil data (get)
        $menus = $query->get();

        return view('user.menu_list', compact('menus'));
    }

    // === 2. PROSES CHECKOUT (Simpan Pesanan) ===
    public function store(Request $request)
    {
        $request->validate([
            'pesanan' => 'required|array',
        ]);

        // Filter: Hanya ambil menu yang jumlahnya > 0
        $items = array_filter($request->pesanan, function ($qty) {
            return $qty > 0;
        });

        if (empty($items)) {
            return redirect()->back()->with('error', 'Pilih minimal 1 menu!');
        }

        // --- LOGIKA CEK PROMO ---
        $potongan = 0;
        if ($request->filled('kode_promo')) {
            $cekPromo = Promo::where('kode', strtoupper($request->kode_promo))->first();

            if (!$cekPromo) {
                return redirect()->back()->with('error', 'Kode Promo Salah / Tidak Ditemukan!');
            }

            $potongan = $cekPromo->diskon;
        }

        // --- MULAI TRANSAKSI DATABASE ---
        DB::transaction(function () use ($items, $potongan) {
            // 1. Buat Order Baru
            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => 'diproses',
                'total_harga' => 0, // Nanti diupdate
            ]);

            $totalBelanja = 0;

            // 2. Simpan Detail Item
            foreach ($items as $menu_id => $qty) {
                $menu = Menu::find($menu_id);

                OrderDetail::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu_id,
                    'jumlah' => $qty,
                    'harga_saat_ini' => $menu->harga,
                    // Hapus 'subtotal' agar tidak error SQL
                ]);

                $totalBelanja += ($menu->harga * $qty);
            }

            // 3. Hitung Grand Total (Total - Diskon)
            // Pakai max(0) supaya tidak minus kalau diskon lebih besar dari belanja
            $grandTotal = max(0, $totalBelanja - $potongan);

            // 4. Update Total Harga di Tabel Order
            $order->update(['total_harga' => $grandTotal]);
        });

        // Pesan Sukses
        $msg = 'Pesanan berhasil dibuat!';
        if($potongan > 0) {
            $msg .= " (Anda hemat Rp " . number_format($potongan, 0, ',', '.') . ")";
        }

        return redirect()->route('order.history')->with('success', $msg);
    }

    // === 3. HALAMAN RIWAYAT PESANAN ===
    public function history()
    {
        $orders = Order::where('user_id', Auth::id())
                        ->with('details.menu')
                        ->orderByDesc('created_at')
                        ->get();

        return view('user.history', compact('orders'));
    }
}
