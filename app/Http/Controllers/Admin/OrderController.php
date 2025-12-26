<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'details.menu'])
                       ->orderByDesc('created_at')
                       ->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:diproses,siap,selesai,batal'
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }
}
