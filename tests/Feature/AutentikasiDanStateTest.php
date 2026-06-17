<?php

use App\Models\User;

test('T1 - user berhasil login dan masuk ke state authenticated', function () {
    $user = User::factory()->create(['role' => 'customer', 'password' => bcrypt('password123')]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $response->assertRedirect('/menus');
    $this->assertAuthenticatedAs($user);
});

test('T2 - user authenticated berhasil mengakses halaman menu', function () {
    $user = User::factory()->create(['role' => 'customer']);
    $response = $this->actingAs($user)->get(route('user.menus'));
    $response->assertStatus(200);
});

test('T3 - tamu tidak terautentikasi ditolak mengakses dashboard admin', function () {
    $response = $this->get(route('admin.dashboard'));
    $response->assertRedirect('/login');
});

test('T4 - user berhasil logout dan kembali ke state unauthenticated', function () {
    $user = User::factory()->create(['role' => 'customer']);
    $response = $this->actingAs($user)->post('/logout');
    $response->assertRedirect('/');
    $this->assertGuest();
});
