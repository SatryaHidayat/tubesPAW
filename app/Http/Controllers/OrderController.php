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
    public function prosesPembayaran(Request $request, $id)
    {
        $request->validate([
            'metode_pembayaran' => 'required'
        ]);

        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status_pembayaran', 'belum_bayar')
            ->firstOrFail();

        $order->update([
            'metode_pembayaran' => $request->metode_pembayaran,
            'status_pembayaran' => 'dibayar',
            'waktu_bayar' => now(),
            'status' => 'selesai'
        ]);

        return redirect()
            ->route('order.history')
            ->with('success', 'Pembayaran berhasil!');
    }

    public function pembayaran($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('user.pembayaran', compact('order'));
    }

    // === 1. HALAMAN DAFTAR MENU ===
    public function index(Request $request)
    {
        $query = Menu::query();

        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        $menus = $query->get();

        return view('user.menu_list', [
            'menus' => $menus
        ]);
    }

    // === 2. PROSES CHECKOUT (BAGIAN YANG DIPERBAIKI) ===
    public function store(Request $request)
    {
        $request->validate([
            'pesanan' => 'required|array',
        ]);

        $items = array_filter($request->pesanan, function ($qty) {
            return $qty > 0;
        });

        if (empty($items)) {
            return redirect()->back()->with('error', 'Pilih minimal 1 menu!');
        }

        // Cek Promo
        $potongan = 0;
        if ($request->filled('kode_promo')) {
            $cekPromo = Promo::where('kode', strtoupper($request->kode_promo))->first();
            if (!$cekPromo) {
                return redirect()->back()->with('error', 'Kode Promo Salah / Tidak Ditemukan!');
            }
            $potongan = $cekPromo->diskon;
        }

        DB::transaction(function () use ($items, $potongan) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => 'diproses',
                'total_harga' => 0,
                'status_pembayaran' => 'belum_bayar',
            ]);

            $totalBelanja = 0;

            foreach ($items as $menu_id => $qty) {
                $menu = Menu::find($menu_id);

                // --- PERBAIKAN DIMULAI DI SINI ---

                // 1. Hitung subtotal
                $subtotal = $menu->harga * $qty;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu_id,
                    'jumlah' => $qty,
                    'harga_saat_ini' => $menu->harga,
                    'subtotal' => $subtotal, // 2. Masukkan subtotal ke database
                ]);

                $totalBelanja += $subtotal;

                // --- PERBAIKAN SELESAI ---
            }

            $hasilKurang = $totalBelanja - $potongan;
            $grandTotal = ($hasilKurang < 0) ? 0 : $hasilKurang;

            $order->update(['total_harga' => $grandTotal]);
        });

        $msg = 'Pesanan berhasil dibuat!';
        if ($potongan > 0) {
            $msg .= " (Anda hemat Rp " . number_format($potongan, 0, ',', '.') . ")";
        }

        return redirect()->route('order.history')->with('success', $msg);
    }

    // === 3. HALAMAN RIWAYAT ===
    public function history()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('details.menu')
            ->orderByDesc('created_at')
            ->get();

        return view('user.history', [
            'orders' => $orders
        ]);
    }
}
