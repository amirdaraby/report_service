<?php

namespace Database\Factories;

use App\Enums\Frequency;
use App\Enums\Status;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Report>
 */
class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->words(3, true),
            'status' => Status::ACTIVE,
            'frequency' => Frequency::DAILY,
            'keywords' => [fake()->word()],
            'next_run_at' => now()->addDay()->startOfDay(),
            'last_run_at' => null,
        ];
    }

    public function daily(): static
    {
        return $this->state(fn () => ['frequency' => Frequency::DAILY]);
    }

    public function weekly(): static
    {
        return $this->state(fn () => ['frequency' => Frequency::WEEKLY]);
    }

    public function active(): static
    {
        return $this->state(fn () => ['status' => Status::ACTIVE]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['status' => Status::INACTIVE]);
    }

    public function due(): static
    {
        return $this->state(fn () => [
            'status' => Status::ACTIVE,
            'next_run_at' => now()->subMinute(),
        ]);
    }
}
