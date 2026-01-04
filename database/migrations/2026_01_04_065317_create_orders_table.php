<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // Pastikan relasi user_id ini sesuai dengan tabel user kamu
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->decimal('total_harga', 10, 2);
            $table->string('status')->default('menunggu'); // status pesanan (masak/selesai)

            // === BAGIAN PEMBAYARAN LENGKAP ===
            $table->string('status_pembayaran')->default('belum_bayar');
            $table->string('metode_pembayaran')->nullable(); // cash/qris
            $table->timestamp('waktu_bayar')->nullable();
            // =================================

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
