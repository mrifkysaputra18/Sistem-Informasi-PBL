<?php

namespace Database\Factories;

use App\Models\RuangKelas;
use App\Models\Pengguna;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory untuk model RuangKelas
 */
class RuangKelasFactory extends Factory
{
    protected $model = RuangKelas::class;

    public function definition(): array
    {
        $randomNum = fake()->unique()->randomNumber(3);
        return [
            'name' => 'TI-' . $randomNum . fake()->randomLetter(),
            'code' => 'TI' . $randomNum . fake()->randomLetter(),
            'program_studi' => 'Teknik Informatika',
            'max_groups' => 5,
            'is_active' => true,
        ];
    }
}
