<?php

declare(strict_types=1);

namespace Sabiowebcom\ArtisanGui\Database\Factories;

use Sabiowebcom\ArtisanGui\Models\ArtisanCommandRun;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArtisanCommandRunFactory extends Factory
{
    protected $model = ArtisanCommandRun::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'command' => $this->faker->randomElement(['cache:clear', 'config:clear', 'route:clear']),
            'parameters' => [],
            'status' => $this->faker->randomElement(['success', 'failed']),
            'output' => $this->faker->text(),
            'error' => null,
            'log_path' => storage_path('logs/artisan-gui/test.log'),
            'exit_code' => 0,
            'executed_by' => null,
            'started_at' => now(),
            'finished_at' => now()->addSeconds(rand(1, 10)),
        ];
    }

    /**
     * Indicate that the command run was successful.
     */
    public function successful(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'success',
            'exit_code' => 0,
            'error' => null,
        ]);
    }

    /**
     * Indicate that the command run failed.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
            'exit_code' => 1,
            'error' => 'Command execution failed',
        ]);
    }
}

