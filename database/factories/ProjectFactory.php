<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $title = fake()->unique()->catchPhrase();
        return [
            'category_id' => Category::factory(),
            'title' => $title,
            'slug' => str()->slug($title) . '-' . fake()->unique()->numberBetween(100, 999),
            'description' => fake()->paragraphs(2, true),
            'client_name' => fake()->company(),
            'project_url' => fake()->optional()->url(),
            'image' => null,
            'status' => fake()->randomElement(['draft', 'published', 'archived']),
            'published_at' => fake()->optional()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
