<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;


class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user');

        // filter status pembayaran
        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        }

        // filter metode pembayaran
        if ($request->filled('metode')) {
            $query->where('metode_pembayaran', $request->metode);
        }

        $orders = $query
            ->orderByDesc('waktu_bayar')
            ->get();

        return view('admin.pembayaran.index', compact('orders'));
    }
}
