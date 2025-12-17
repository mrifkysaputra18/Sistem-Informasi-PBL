<?php

namespace Database\Factories;

use App\Models\Pengguna;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Factory untuk model Pengguna
 */
class PenggunaFactory extends Factory
{
    protected $model = Pengguna::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'nim' => fake()->unique()->numerify('##########'),
            'politala_id' => fake()->unique()->uuid(),
            'is_active' => true,
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => ['role' => 'admin']);
    }

    public function dosen(): static
    {
        return $this->state(fn (array $attributes) => ['role' => 'dosen']);
    }

    public function mahasiswa(): static
    {
        return $this->state(fn (array $attributes) => ['role' => 'mahasiswa']);
    }

    public function koordinator(): static
    {
        return $this->state(fn (array $attributes) => ['role' => 'koordinator']);
    }
}
