<?php

namespace Database\Factories;

use App\Models\TargetMingguan;
use App\Models\Kelompok;
use App\Models\Pengguna;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory untuk model TargetMingguan
 */
class TargetMingguanFactory extends Factory
{
    protected $model = TargetMingguan::class;

    public function definition(): array
    {
        return [
            'group_id' => Kelompok::factory(),
            'created_by' => Pengguna::factory()->dosen(),
            'week_number' => fake()->numberBetween(1, 16),
            'title' => 'Target Minggu ' . fake()->numberBetween(1, 16),
            'description' => fake()->paragraph(),
            'deadline' => now()->addDays(fake()->numberBetween(1, 14)),
            'submission_status' => 'pending',
            'is_completed' => false,
            'is_reviewed' => false,
            'is_open' => true,
        ];
    }

    public function submitted(): static
    {
        return $this->state(fn (array $attributes) => [
            'submission_status' => 'submitted',
            'is_completed' => true,
            'completed_at' => now(),
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'submission_status' => 'approved',
            'is_completed' => true,
            'is_reviewed' => true,
            'completed_at' => now(),
            'reviewed_at' => now(),
        ]);
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_open' => false,
        ]);
    }
}
