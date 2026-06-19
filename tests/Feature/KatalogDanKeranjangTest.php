<?php

use App\Models\User;
use App\Models\Menu;

test('EP Valid - berhasil menambahkan produk valid ke keranjang', function () {
    $user = User::factory()->create(['role' => 'customer']);
    $menu = Menu::factory()->create();

    $response = $this->actingAs($user)->post(route('user.checkout'), [
        'menu_id' => $menu->id,
        'kategori' => 'Kopi',
        'kustomisasi' => 'Ice',
        'kuantitas' => 1
    ]);
    $response->assertStatus(302);
});

test('BVA - qty 0 ditolak dengan error validasi', function () {
    $user = User::factory()->create(['role' => 'customer']);
    $menu = Menu::factory()->create();

    $response = $this->actingAs($user)->post(route('user.checkout'), [
        'menu_id' => $menu->id,
        'kategori' => 'Kopi',
        'kustomisasi' => 'Hot',
        'pesanan' => 0
    ]);
    $response->assertSessionHasErrors('pesanan');
});

test('EP Invalid - tambah ke keranjang gagal jika user belum login', function () {
    $menu = Menu::factory()->create();

    $response = $this->post(route('user.checkout'), [
        'menu_id' => $menu->id,
        'kategori' => 'Kopi',
        'kustomisasi' => 'Ice',
        'kuantitas' => 1
    ]);
    $response->assertRedirect('/login');
});
