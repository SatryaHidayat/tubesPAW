<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Menampilkan semua pesanan masuk (Halaman Admin)
     */
    public function index()
    {
        // Mengambil data order dengan relasi user dan detail menu, diurutkan terbaru
        $orders = Order::with(['user', 'details.menu'])
                        ->orderByDesc('created_at')
                        ->get();

        // Return ke view ADMIN (bukan user.menus)
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Update status pesanan (Diproses/Selesai/Batal)
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:diproses,siap,selesai,batal'
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }
}
