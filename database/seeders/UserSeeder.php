<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun ADMIN
        DB::table('users')->insert([
            'name' => 'Admin Ganteng',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'), // passwordnya: password
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Akun USER Biasa
        DB::table('users')->insert([
            'name' => 'Pelanggan Setia',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password'), // passwordnya: password
            'role' => 'user',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
