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

    // === FUNGSI APPLY PROMO DI HALAMAN PEMBAYARAN ===
    public function applyPromoPembayaran(Request $request, $id)
    {
        $request->validate([
            'kode_promo' => 'required'
        ]);

        $promo = Promo::where('kode', strtoupper($request->kode_promo))->first();

        if (!$promo) {
            return redirect()->back()->with('error', 'Kode Promo Salah / Tidak Ditemukan!');
        }

        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status_pembayaran', 'belum_bayar')
            ->firstOrFail();

        // Cegah penggunaan promo ganda
        if ($order->diskon > 0) {
            return redirect()->back()->with('error', 'Promo sudah terpasang pada pesanan ini!');
        }

        $potongan = $promo->diskon;
        $hasilKurang = $order->total_harga - $potongan;
        $grandTotal = ($hasilKurang < 0) ? 0 : $hasilKurang;

        $order->update([
            'total_harga' => $grandTotal,
            'diskon'      => $potongan
        ]);

        return redirect()->back()->with('success', 'Promo berhasil digunakan! Hemat Rp ' . number_format($potongan, 0, ',', '.'));
    }

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

    // === PROSES CHECKOUT (Logika Promo di Sini Dihapus) ===
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

        $orderId = DB::transaction(function () use ($items) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => 'diproses',
                'total_harga' => 0,
                'diskon' => 0, // Default 0, diisi nanti di halaman pembayaran
                'status_pembayaran' => 'belum_bayar',
            ]);

            $totalBelanja = 0;

            foreach ($items as $menu_id => $qty) {
                $menu = Menu::find($menu_id);
                $subtotal = $menu->harga * $qty;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu_id,
                    'jumlah' => $qty,
                    'harga_saat_ini' => $menu->harga,
                    'subtotal' => $subtotal,
                ]);

                $totalBelanja += $subtotal;
            }

            $order->update(['total_harga' => $totalBelanja]);

            return $order->id;
        });

        return redirect()->route('order.pembayaran', $orderId)->with('success', 'Pesanan berhasil dibuat!');
    }

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

    public function halamanPembayaran($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id()) // Pastikan pesanan ini memang milik user yang sedang login
            ->where('status_pembayaran', 'belum_bayar')
            ->firstOrFail();

        return view('user.pembayaran', compact('order'));
    }
}
