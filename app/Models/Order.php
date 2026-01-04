<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Pastikan baris ini ada agar OrderDetail terbaca
use App\Models\OrderDetail;

class Order extends Model
{
    use HasFactory;

    // Bagian ini mengizinkan data disimpan (Fix Error Gambar 1)
    protected $fillable = [
        'user_id',
        'total_harga',
        'status',
        'status_pembayaran',
        'metode_pembayaran', // <--- Wajib ada
        'waktu_bayar',       // <--- Wajib ada
    ];

    // Bagian ini memperbaiki Error "Undefined relationship [details]" (Fix Error Gambar 2)
    public function details()
    {
        // Hubungkan Order ke OrderDetail
        return $this->hasMany(OrderDetail::class);
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
