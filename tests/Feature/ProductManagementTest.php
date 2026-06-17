<?php

use App\Models\User;
use App\Models\Menu;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('EP Valid - admin berhasil tambah menu baru', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    Storage::fake('public');

    $response = $this->actingAs($admin)->post(route('admin.menus.store'), [
        'nama_menu' => 'Kopi Espresso Enak',
        'kategori' => 'Kopi',
        'harga' => 15000,
        'foto' => UploadedFile::fake()->create('kopi.jpg', 100)
    ]);

    $response->assertRedirect(route('admin.menus.index'));
    $this->assertDatabaseHas('menus', ['nama_menu' => 'Kopi Espresso Enak']);
});

test('EP Invalid - tambah menu gagal jika nama_menu kosong', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    Storage::fake('public');

    $response = $this->actingAs($admin)->post(route('admin.menus.store'), [
        'nama_menu' => '', // Kosong
        'kategori' => 'Kopi',
        'harga' => 15000,
        'foto' => UploadedFile::fake()->create('kopi.jpg', 100)
    ]);

    $response->assertSessionHasErrors('nama_menu');
});

test('EP Invalid - user bukan admin diblokir dari halaman manajemen menu', function () {
    $user = User::factory()->create(['role' => 'customer']);

    $response = $this->actingAs($user)->post(route('admin.menus.store'), [
        'nama_menu' => 'Kopi Ilegal',
        'kategori' => 'Kopi',
        'harga' => 15000,
    ]);

    $response->assertStatus(302);
});

test('EP Valid - admin berhasil menghapus data menu', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $menu = Menu::factory()->create();

    $response = $this->actingAs($admin)->delete(route('admin.menus.destroy', $menu->id));

    $this->assertDatabaseMissing('menus', ['id' => $menu->id]);
});
