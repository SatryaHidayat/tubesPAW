<?php

use App\Models\User;
use App\Models\Menu;

test('Path 1 - Alur pendaftaran akun baru (Register)', function () {
    $registrationData = [
        'name' => 'Danang Fahrurrozi',
        'email' => 'danang@coffee.test',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ];

    $response = $this->post('/register', $registrationData);

    $response->assertStatus(302);
    $this->assertDatabaseHas('users', ['email' => 'danang@coffee.test']);
});

test('Path 2 - Alur masuk sistem utama (Login)', function () {
    $user = User::factory()->create([
        'email' => 'customer@coffee.test',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post('/login', [
        'email' => 'customer@coffee.test',
        'password' => 'password123',
    ]);

    $response->assertRedirect('/menus');
    $this->assertAuthenticatedAs($user);
});

test('Path 3 - Alur penanganan menu saat kondisi keranjang kosong', function () {
    $user = User::factory()->create(['role' => 'customer']);
    $menu = Menu::factory()->create();

    $response = $this->actingAs($user)->get(route('user.menus'));
    $response->assertStatus(200);

    $responseCart = $this->actingAs($user)->post(route('user.checkout'), [
        'menu_id' => $menu->id,
        'kategori' => $menu->kategori,
        'kustomisasi' => 'Ice',
        'kuantitas' => 1
    ]);
    $responseCart->assertStatus(302);
});

test('Path 4 - Alur keluar dari sistem (Logout)', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $response->assertRedirect('/');
    $this->assertGuest();
});
