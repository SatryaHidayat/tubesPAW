<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;  // Import Model Menu
use App\Models\Order; // Import Model Order (jika dibutuhkan untuk checkout)

class OrderController extends Controller
{
    /**
     * Menampilkan halaman daftar menu (Sesuai Desain Figma)
     */
    public function index()
    {
        // Ambil semua menu
        $menus = Menu::all();

        // Kirim ke view 'user.menus' (pastikan filenya ada di resources/views/user/menus.blade.php)
        return view('user.menus', compact('menus'));
    }

    /**
     * Menangani proses checkout/pemesanan
     */
    public function store(Request $request)
    {
        // Logika checkout kamu (biarkan atau sesuaikan nanti)
        return redirect()->route('order.history')->with('success', 'Pesanan berhasil dibuat!');
    }

    /**
     * Menampilkan riwayat pesanan user
     */
    public function history()
    {
        // Logika history kamu
        return view('user.history');
    }
}
