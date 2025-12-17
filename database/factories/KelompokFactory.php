<?php

namespace Database\Factories;

use App\Models\Kelompok;
use App\Models\RuangKelas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory untuk model Kelompok
 */
class KelompokFactory extends Factory
{
    protected $model = Kelompok::class;

    public function definition(): array
    {
        return [
            'name' => 'Kelompok ' . fake()->randomNumber(1),
            'class_room_id' => RuangKelas::factory(),
            'max_members' => 5,
        ];
    }
}
