<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Promo; // JANGAN LUPA INI
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $menus = Menu::all();
        return view('user.menu_list', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate(['pesanan' => 'required|array']);

        // Filter pesanan kosong
        $items = array_filter($request->pesanan, function ($qty) { return $qty > 0; });
        if (empty($items)) return redirect()->back()->with('error', 'Pilih minimal 1 menu!');

        // --- CEK KODE PROMO ---
        $potongan = 0;
        if ($request->filled('kode_promo')) {
            $cekPromo = Promo::where('kode', strtoupper($request->kode_promo))->first();
            if (!$cekPromo) {
                return redirect()->back()->with('error', 'Kode Promo Salah / Tidak Ditemukan!');
            }
            $potongan = $cekPromo->diskon;
        }

        DB::transaction(function () use ($items, $potongan) {
            // Buat Order
            $order = Order::create([
                'user_id' => auth()->id(),
                'status' => 'diproses',
                'total_harga' => 0,
            ]);

            $total = 0;
            foreach ($items as $menu_id => $qty) {
                $menu = Menu::find($menu_id);
                OrderDetail::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu_id,
                    'jumlah' => $qty,
                    'harga_saat_ini' => $menu->harga,
                ]);
                $total += $menu->harga * $qty;
            }

            // Hitung Grand Total (Total Belanja - Diskon)
            // Pakai max(0, ...) biar harga tidak minus kalau diskon kegedean
            $grandTotal = max(0, $total - $potongan);

            $order->update(['total_harga' => $grandTotal]);
        });

        $msg = 'Pesanan berhasil!';
        if($potongan > 0) $msg .= " (Hemat Rp " . number_format($potongan) . ")";

        return redirect()->route('order.history')->with('success', $msg);
    }

    public function history()
    {
        $orders = Order::where('user_id', auth()->id())
                        ->with('details.menu')
                        ->orderByDesc('created_at')
                        ->get();

        return view('user.history', compact('orders'));
    }
}
