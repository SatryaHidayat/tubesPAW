<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Promo;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $menus = Menu::all();
        return view('user.menus', compact('menus'));
    }

    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'jumlah' => 'required|integer|min:1',
            'kode_promo' => 'nullable|string|exists:promos,kode',
        ]);

        // 2. Ambil Data Menu & Hitung
        $menu = Menu::find($request->menu_id);
        $subtotal = $menu->harga * $request->jumlah;
        $total_bayar = $subtotal;
        $potongan = 0;

        // 3. Cek Promo
        if ($request->filled('kode_promo')) {
            $promo = Promo::where('kode', $request->kode_promo)->first();
            if ($promo && $promo->status == 'aktif') {
                $potongan = $promo->diskon;
                $total_bayar = $subtotal - $potongan;
                if ($total_bayar < 0) $total_bayar = 0;
            }
        }

        // 4. Simpan Order (Nota)
        $order = Order::create([
            'user_id' => Auth::id(),
            'status' => 'pending',
            'total_harga' => $total_bayar,
        ]);

        // 5. Simpan Detail (Barang)
        OrderDetail::create([
            'order_id' => $order->id,
            'menu_id' => $menu->id,
            'jumlah' => $request->jumlah,

            // PERUBAHAN DISINI (Menggunakan 'harga_saat_ini')
            'harga_saat_ini' => $menu->harga,

            'subtotal' => $subtotal,
            'catatan' => $request->catatan,
        ]);

        // 6. Redirect
        $pesan = "Pesanan berhasil!";
        if ($potongan > 0) {
            $pesan .= " Kamu hemat Rp" . number_format($potongan, 0, ',', '.');
        }

        return redirect()->route('order.history')->with('success', $pesan);
    }

    public function history()
    {
        $orders = Order::where('user_id', Auth::id())
                    ->with('details.menu')
                    ->orderByDesc('created_at')
                    ->get();

        return view('user.history', compact('orders'));
    }
}
