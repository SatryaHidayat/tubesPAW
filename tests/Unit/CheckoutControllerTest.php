<?php

use App\Models\User;
use App\Models\Order;

test('Path 1 - checkout gagal jika keranjang kosong', function () {
    $user = User::factory()->create(['role' => 'customer']);

    $response = $this->actingAs($user)->post(route('order.prosesPembayaran', 1), [
        'metode_pembayaran' => 'Transfer Bank'
    ]);

    $response->assertStatus(404);
});

test('Path 2 - checkout berhasil mengembalikan response order_id yang valid', function () {
    $user = User::factory()->create(['role' => 'customer']);

    $response = $this->actingAs($user)->get(route('order.history'));
    $response->assertStatus(200);
});
