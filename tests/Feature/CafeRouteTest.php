<?php

use App\Models\User;

test('halaman utama otomatis redirect ke halaman login', function () {
    $response = $this->get('/');

    // Sesuai baris 13 di web.php kamu: return redirect('/login');
    $response->assertRedirect('/login');
});

test('tamu tidak bisa membuka dashboard admin dan dilempar ke login', function () {
    $response = $this->get(route('admin.dashboard'));

    $response->assertRedirect('/login');
});

test('user dengan role admin bisa mengakses dashboard admin', function () {
    // Kita bypass phpMyAdmin dengan membuat user simulasi langsung berpangkat admin
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertStatus(200);
});
