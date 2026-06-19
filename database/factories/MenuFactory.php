<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_menu' => $this->faker->word() . ' Coffee',
            'kategori'  => 'Kopi',
            'harga'     => 15000,
            'foto'      => 'kopi.jpg',
        ];
    }
}
