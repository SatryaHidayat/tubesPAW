<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
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
        $request->validate([
            'pesanan' => 'required|array',
        ]);

        $items = array_filter($request->pesanan, function ($qty) {
            return $qty > 0;
        });

        if (empty($items)) {
            return redirect()->back()->with('error', 'Silakan pilih minimal 1 menu!');
        }

        DB::transaction(function () use ($items) {
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

            $order->update(['total_harga' => $total]);
        });

        return redirect()->route('order.history')->with('success', 'Pesanan berhasil dibuat! Mohon tunggu pesanan Anda.');
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
