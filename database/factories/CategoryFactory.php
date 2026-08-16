<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);
        return [
            'name' => ucwords($name),
            'slug' => str()->slug($name) . '-' . fake()->unique()->numberBetween(10, 99),
            'description' => fake()->sentence(),
            'status' => 'active',
        ];
    }
}
