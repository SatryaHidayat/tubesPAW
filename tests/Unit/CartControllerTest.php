<?php

use App\Models\User;
use App\Models\Menu;

test('Path 1 - add cart gagal jika input tidak valid', function () {
    $user = User::factory()->create(['role' => 'customer']);
    $response = $this->actingAs($user)->post(route('user.checkout'), []);
    $response->assertSessionHasErrors();
});

test('Path 2 - add cart gagal jika pesanan tidak logis', function () {
    $user = User::factory()->create(['role' => 'customer']);
    $menu = Menu::factory()->create();

    $response = $this->actingAs($user)->post(route('user.checkout'), [
        'menu_id' => $menu->id,
        'kategori' => 'Kopi',
        'kustomisasi' => 'Ice',
        'pesanan' => [-5]
    ]);
    $response->assertSessionHas('error');
});

test('Path 3 - add cart berhasil dengan input valid', function () {
    $user = User::factory()->create(['role' => 'customer']);
    $menu = Menu::factory()->create();

    $response = $this->actingAs($user)->post(route('user.checkout'), [
        'menu_id' => $menu->id,
        'kategori' => 'Kopi',
        'kustomisasi' => 'Ice',
        'pesanan' => 2
    ]);
    $response->assertStatus(302);
});
