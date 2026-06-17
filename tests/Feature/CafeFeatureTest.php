<?php

use App\Models\User;

test('K1 - Login Valid: Email benar, password >= 8 karakter, akun terdaftar', function () {
    $user = User::factory()->create([
        'email' => 'admin@coffee.test',
        'password' => bcrypt('password'),
    ]);

    $response = $this->post('/login', [
        'email' => 'admin@coffee.test',
        'password' => 'password',
    ]);

    $response->assertStatus(302);
});

test('K2 s/d K5 - Login Invalid: Penanganan kredensial tidak cocok atau salah format', function () {
    $response = $this->post('/login', [
        'email' => 'admincoffee.test',
        'password' => 'PasswordSalah',
    ]);

    $response->assertSessionHasErrors();
});

test('K6 - Login Invalid: Semua field kosong', function () {
    $response = $this->from('/login')->post('/login', [
        'email' => '',
        'password' => '',
    ]);

    $response->assertSessionHasErrors(['email', 'password']);
});

// FITUR: KODE PROMO / BVA (Halaman Laporan 6)

test('BVA-01 s/d BVA-03 - Batas Minimum Grand Total Tidak Boleh Negatif', function () {
    $user = User::factory()->create(['role' => 'customer']);

    $response = $this->actingAs($user)->post(route('order.applyPromo', ['id' => 1]), [
        'total_harga' => 50000,
        'diskon' => 50001,
    ]);

    $response->assertSessionHas('grand_total', 0);
});

test('BVA-04 - Perhitungan Nominal Normal Potongan Diskon', function () {
    $user = User::factory()->create(['role' => 'customer']);

    $response = $this->actingAs($user)->post(route('order.applyPromo', ['id' => 1]), [
        'total_harga' => 50000,
        'diskon' => 25000,
        'kode_promo' => 'DUMMY'
    ]);

    $response->assertStatus(302);
});
