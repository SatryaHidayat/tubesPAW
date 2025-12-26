<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::all();
        return view('admin.promos.index', compact('promos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:promos,kode',
            'diskon' => 'required|numeric|min:0',
        ]);

        Promo::create([
            'kode' => strtoupper($request->kode), // Paksa jadi huruf besar
            'diskon' => $request->diskon,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->back()->with('success', 'Promo berhasil dibuat!');
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();
        return redirect()->back()->with('success', 'Promo dihapus!');
    }
}
