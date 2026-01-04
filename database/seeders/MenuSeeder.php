<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run()
    {
        $menus = [
            // --- COFFEE ---
            [
                'nama_menu' => 'Americano',
                'harga' => 12000,
                'kategori' => 'coffee',
                'foto' => 'menus/american.jpeg', // Sesuaikan nama file gambarmu
                'deskripsi' => 'Kopi hitam murni tanpa gula',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'nama_menu' => 'Kopi Susu',
                'harga' => 12000,
                'kategori' => 'coffee',
                'foto' => 'menus/kopi_sus.jpeg',
                'deskripsi' => 'Perpaduan kopi dan susu segar',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'nama_menu' => 'Capucino',
                'harga' => 15000,
                'kategori' => 'coffee',
                'foto' => 'menus/capucin.jpeg',
                'deskripsi' => 'Espresso, susu, dan foam tebal',
                'created_at' => now(), 'updated_at' => now(),
            ],

            // --- FOOD ---
            [
                'nama_menu' => 'Mie Goreng',
                'harga' => 13000,
                'kategori' => 'food',
                'foto' => 'menus/mie_goren.jpeg',
                'deskripsi' => 'Mie goreng spesial telur',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'nama_menu' => 'Nasi Ayam',
                'harga' => 15000,
                'kategori' => 'food',
                'foto' => 'menus/nasi_aya.jpeg',
                'deskripsi' => 'Nasi dengan ayam bumbu rempah',
                'created_at' => now(), 'updated_at' => now(),
            ],
        ];

        DB::table('menus')->insert($menus);
    }
}
