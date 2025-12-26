<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'menu_id', 'jumlah', 'harga_saat_ini'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
