<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'menu_id',
        'jumlah',
        'harga_saat_ini', // Pastikan nama ini sama dengan di controller & database
        'subtotal',       // <--- INI WAJIB ADA
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
