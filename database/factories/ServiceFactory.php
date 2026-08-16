<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'description' => fake()->paragraph(),
            'icon' => fake()->randomElement(['code-2', 'smartphone', 'brain-circuit', 'cloud', 'workflow']),
            'status' => 'active',
            'sort_order' => fake()->numberBetween(1, 99),
        ];
    }
}
